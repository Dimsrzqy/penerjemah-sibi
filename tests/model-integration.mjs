import assert from 'node:assert/strict';
import { readFileSync, statSync } from 'node:fs';
import { runInNewContext } from 'node:vm';

const root = new URL('../', import.meta.url);
const read = path => readFileSync(new URL(path, root), 'utf8');
const model = JSON.parse(read('public/models/model.json'));
const input = model.signature.inputs.input_layer.tensorShape.dim.map(dim => Number(dim.size));
const output = model.signature.outputs.output_0.tensorShape.dim.map(dim => Number(dim.size));
const manifest = model.weightsManifest[0];
const bytes = manifest.weights.reduce((total, weight) =>
    total + weight.shape.reduce((size, dimension) => size * dimension, 1) * 4, 0);
const runner = read('public/js/graph-gru.js');
const labels = JSON.parse(read('public/models/label_map.json'));

assert.deepEqual(input, [-1, 30, 126]);
assert.deepEqual(output, [-1, 60]);
assert.equal(bytes, statSync(new URL(`public/models/${manifest.paths[0]}`, root)).size);
assert.deepEqual(Object.values(labels).sort((a, b) => a - b), Array.from({ length: 60 }, (_, index) => index));
assert.equal(labels.Sabtu, 44);
const landmarks = Array.from({ length: 21 }, (_, index) => ({ x: 0.4 + index * 0.01, y: 0.3 + index * 0.02, z: index * 0.03 }));
const extract = runInNewContext(`${runner}; extractSibiLandmarks`);
const left = extract([landmarks], [{ label: 'Left' }]);
assert.equal(left.length, 126);
assert.ok(left.slice(0, 3).every(value => value === 0));
assert.ok(Math.abs(left[3] - 0.01) < 1e-10);
assert.ok(left.slice(63).every(value => value === 0));
const right = extract([landmarks], [{ label: 'Right' }]);
assert.ok(right.slice(0, 63).every(value => value === 0));
assert.ok(Math.abs(right[66] - 0.01) < 1e-10);
for (const name of ['gru_1/while/gru_cell_1/Cast/ReadVariableOp', 'gru_1_2/while/gru_cell_1/Cast/ReadVariableOp', 'dense_1_2/Cast/ReadVariableOp']) {
    assert.ok(manifest.weights.some(weight => weight.name.includes(name)));
    assert.ok(runner.includes(name));
}
for (const page of ['penerjemah', 'quiz']) {
    assert.match(read(`public/js/${page}.js`), /let modelUrl = '\/models\/model\.json'/);
    assert.match(read(`public/js/${page}.js`), /while \(seq\.length < (?:SEQUENCE_LENGTH|targetSeqLen)\) seq\.unshift\(seq\[0\]\)/);
    assert.match(read(`resources/views/${page}.blade.php`), /js\/graph-gru\.js/);
}
assert.match(read('public/js/penerjemah.js'), /!toggleAutoType \|\| toggleAutoType\.checked/);
assert.match(read('public/js/penerjemah.js'), /completeSequence && \(!toggleAutoType/);
assert.match(read('public/js/quiz.js'), /maxProb >= confidenceThreshold && sequenceBuffer\.length === SEQUENCE_LENGTH/);
console.log('Model lokal dan jalur inferensi awal valid.');
