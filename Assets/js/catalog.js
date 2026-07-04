let filters = document.querySelector('.filters');
let filterOpenBtn = document.getElementById('catalog-filter-btn');

filters.addEventListener('click', (e) => {
    if(e.target == filters) {
        filters.classList.remove('active');
        blockScroll(false);
    }
});
filterOpenBtn.addEventListener('click', (e) => {
    filters.classList.add('active');
    blockScroll(true);
});
