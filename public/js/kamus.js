/**
 * ============================================================
 * SIBI LEARN - KAMUS SIBI JAVASCRIPT
 * ============================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.dict-card');
    const noResults = document.getElementById('noResults');

    // Modal elements
    const detailModal = document.getElementById('detailModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalCategory = document.getElementById('modalCategory');
    const modalDesc = document.getElementById('modalDesc');
    const modalMediaContainer = document.getElementById('modalMediaContainer');
    const modalCloseBtn = document.getElementById('modalCloseBtn');
    const modalCloseActionBtn = document.getElementById('modalCloseActionBtn');

    let activeCategory = 'all';

    function filterCards() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        let visibleCount = 0;

        cards.forEach(card => {
            const word = (card.getAttribute('data-word') || '').toLowerCase();
            const cat = card.getAttribute('data-category') || '';
            const matchesSearch = word.includes(query);
            const matchesCategory = (activeCategory === 'all' || cat === activeCategory);

            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (noResults) {
            if (visibleCount === 0 && cards.length > 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterCards);
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => {
                b.className = 'filter-btn px-5 py-2.5 rounded-full bg-surface-container-high text-on-surface-variant hover:bg-surface-dim text-xs sm:text-sm font-semibold whitespace-nowrap transition-all';
            });
            btn.className = 'filter-btn active px-5 py-2.5 rounded-full bg-primary text-on-primary text-xs sm:text-sm font-semibold whitespace-nowrap shadow-sm transition-all';
            activeCategory = btn.getAttribute('data-category') || 'all';
            filterCards();
        });
    });

    // Handle Card Click to open Detail Modal with Looping Video
    cards.forEach(card => {
        card.addEventListener('click', () => {
            const title = card.getAttribute('data-title') || '';
            const category = card.getAttribute('data-category') || '';
            const desc = card.getAttribute('data-desc') || 'Kosakata bahasa isyarat SIBI.';
            const mediaUrl = card.getAttribute('data-media') || '';
            const isVideoAttr = card.getAttribute('data-is-video') === 'true';

            // Automatic detection for video files
            const isVideo = isVideoAttr || (mediaUrl && /\.(mp4|webm|ogg|mov|m4v)$/i.test(mediaUrl));

            if (modalTitle) modalTitle.textContent = title;
            if (modalCategory) modalCategory.textContent = category;
            if (modalDesc) modalDesc.textContent = desc;

            if (modalMediaContainer) {
                modalMediaContainer.innerHTML = '';
                if (mediaUrl && isVideo) {
                    const video = document.createElement('video');
                    video.src = mediaUrl;
                    video.controls = true;
                    video.autoplay = true;
                    video.loop = true; // Video otomatis di-loop saat popup terbuka
                    video.playsInline = true;
                    video.className = 'w-full h-full object-cover';
                    modalMediaContainer.appendChild(video);
                    video.play().catch(() => {
                        // Muted fallback jika browser memblokir autoplay bersuara
                        video.muted = true;
                        video.play();
                    });
                } else if (mediaUrl) {
                    const img = document.createElement('img');
                    img.src = mediaUrl;
                    img.alt = title;
                    img.className = 'w-full h-full object-cover';
                    modalMediaContainer.appendChild(img);
                } else {
                    modalMediaContainer.innerHTML = `
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-primary/20 to-primary/5 text-primary">
                            <span class="material-symbols-outlined text-6xl">sign_language</span>
                            <span class="text-sm font-bold mt-2">${title}</span>
                        </div>
                    `;
                }
            }

            openModal();
        });
    });

    function openModal() {
        if (!detailModal) return;
        detailModal.classList.remove('opacity-0', 'pointer-events-none');
        detailModal.classList.add('opacity-100');
        const modalContent = detailModal.querySelector('.transform');
        if (modalContent) {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }
    }

    function closeModal() {
        if (!detailModal) return;
        detailModal.classList.remove('opacity-100');
        detailModal.classList.add('opacity-0', 'pointer-events-none');
        const modalContent = detailModal.querySelector('.transform');
        if (modalContent) {
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
        }
        if (modalMediaContainer) {
            const video = modalMediaContainer.querySelector('video');
            if (video) video.pause();
        }
    }

    if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);
    if (modalCloseActionBtn) modalCloseActionBtn.addEventListener('click', closeModal);
    if (detailModal) {
        detailModal.addEventListener('click', (e) => {
            if (e.target === detailModal) closeModal();
        });
    }
});
