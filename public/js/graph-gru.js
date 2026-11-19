function extractSibiLandmarks(multiHandLandmarks, multiHandedness) {
    const features = new Array(126).fill(0);
    let rightFilled = false;
    for (let handIndex = 0; handIndex < Math.min(multiHandLandmarks?.length || 0, 2); handIndex++) {
        const handedness = multiHandedness?.[handIndex];
        const label = handedness?.label || handedness?.classification?.[0]?.label;
        const offset = label === 'Left' ? 0 : label === 'Right' ? 63 : rightFilled ? 0 : 63;
        if (offset === 63) rightFilled = true;
        const landmarks = multiHandLandmarks[handIndex];
        const wrist = landmarks[0];
        for (let point = 0; point < 21; point++) {
            const landmark = landmarks[point];
            features[offset + point * 3] = landmark.x - wrist.x;
            features[offset + point * 3 + 1] = landmark.y - wrist.y;
            features[offset + point * 3 + 2] = landmark.z - wrist.z;
        }
    }
    return features;
}

async function loadFrozenGruModel(url, modelJson) {
    const manifest = modelJson.weightsManifest?.[0];
    const inputShape = modelJson.signature?.inputs?.input_layer?.tensorShape?.dim?.map(dim => Number(dim.size));
    const outputShape = modelJson.signature?.outputs?.output_0?.tensorShape?.dim?.map(dim => Number(dim.size));
    if (!manifest || inputShape?.join(',') !== '-1,30,126' || outputShape?.join(',') !== '-1,60') {
        throw new Error('Bentuk model GRU tidak sesuai (30×126 → 60)');
    }

    const response = await fetch(new URL(manifest.paths[0], new URL(url, location.href)));
    if (!response.ok) throw new Error(`Gagal mengambil bobot model: HTTP ${response.status}`);
    const buffer = await response.arrayBuffer();
    const prefix = 'StatefulPartitionedCall/sequential_1/';
    const names = {
        k1: `${prefix}gru_1/while/body/_22/sequential_1/gru_1/while/gru_cell_1/Cast/ReadVariableOp`,
        r1: `${prefix}gru_1/while/body/_22/sequential_1/gru_1/while/gru_cell_1/Cast_1/ReadVariableOp`,
        b1: 'Func/StatefulPartitionedCall/input/_1',
        m1: `${prefix}batch_normalization_1/batchnorm/mul`,
        s1: `${prefix}batch_normalization_1/batchnorm/sub`,
        k2: `${prefix}gru_1_2/while/body/_61/sequential_1/gru_1_2/while/gru_cell_1/Cast/ReadVariableOp`,
        r2: `${prefix}gru_1_2/while/body/_61/sequential_1/gru_1_2/while/gru_cell_1/Cast_1/ReadVariableOp`,
        b2: 'Func/StatefulPartitionedCall/input/_8',
        m2: `${prefix}batch_normalization_1_2/batchnorm/mul`,
        s2: `${prefix}batch_normalization_1_2/batchnorm/sub`,
        dense: `${prefix}dense_1/Cast/ReadVariableOp`,
        denseBias: `${prefix}dense_1/BiasAdd/ReadVariableOp`,
        out: `${prefix}dense_1_2/Cast/ReadVariableOp`,
        outBias: `${prefix}dense_1_2/BiasAdd/ReadVariableOp`
    };
    const weights = {};
    let offset = 0;
    for (const spec of manifest.weights) {
        const bytes = spec.shape.reduce((size, dim) => size * dim, 1) * 4;
        if (Object.values(names).includes(spec.name)) {
            if (spec.dtype !== 'float32') throw new Error(`Tipe bobot ${spec.name} tidak didukung`);
            weights[spec.name] = tf.tensor(new Float32Array(buffer.slice(offset, offset + bytes)), spec.shape);
        }
        offset += bytes;
    }
    if (offset !== buffer.byteLength || Object.values(names).some(name => !weights[name])) {
        Object.values(weights).forEach(weight => weight.dispose());
        throw new Error('Bobot model GRU tidak lengkap');
    }
    const w = key => weights[names[key]];

    function gruStep(x, h, kernel, recurrent, bias) {
        const [inputBias, recurrentBias] = tf.unstack(bias);
        const [xz, xr, xh] = tf.split(tf.add(tf.matMul(x, kernel), inputBias), 3, 1);
        const [hz, hr, hh] = tf.split(tf.add(tf.matMul(h, recurrent), recurrentBias), 3, 1);
        const z = tf.sigmoid(tf.add(xz, hz));
        const r = tf.sigmoid(tf.add(xr, hr));
        const candidate = tf.tanh(tf.add(xh, tf.mul(r, hh)));
        return tf.add(tf.mul(z, h), tf.mul(tf.sub(1, z), candidate));
    }

    return {
        inputs: [{ shape: [null, 30, 126] }],
        predict(input) {
            return tf.tidy(() => {
                let h1 = tf.zeros([1, 128]);
                const states = [];
                for (const frame of tf.unstack(input, 1)) {
                    h1 = gruStep(frame, h1, w('k1'), w('r1'), w('b1'));
                    states.push(h1);
                }
                const normalized = tf.add(tf.mul(tf.stack(states, 1), w('m1')), w('s1'));
                let h2 = tf.zeros([1, 64]);
                for (const frame of tf.unstack(normalized, 1)) {
                    h2 = gruStep(frame, h2, w('k2'), w('r2'), w('b2'));
                }
                const normalizedFinal = tf.add(tf.mul(h2, w('m2')), w('s2'));
                const hidden = tf.relu(tf.add(tf.matMul(normalizedFinal, w('dense')), w('denseBias')));
                return tf.softmax(tf.add(tf.matMul(hidden, w('out')), w('outBias')));
            });
        },
        dispose() { Object.values(weights).forEach(weight => weight.dispose()); }
    };
}
