/**
 * ============================================================
 * SIBI LEARN - KAMUS SIBI JAVASCRIPT
 * ============================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.dict-card');

    let activeCategory = 'all';

    function filterCards() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        cards.forEach(card => {
            const word = (card.getAttribute('data-word') || '').toLowerCase();
            const cat = card.getAttribute('data-category') || '';
            const matchesSearch = word.includes(query);
            const matchesCategory = (activeCategory === 'all' || cat === activeCategory);

            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
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
});
