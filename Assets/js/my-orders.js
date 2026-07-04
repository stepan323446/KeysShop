let orders = document.querySelectorAll('.my-order');

orders?.forEach(order => {
    let btnToOpen = order.querySelector('.btn-primary');
    let copyInputBtn = order.querySelector('.btn-copy');
    let input = order.querySelector('input');

    btnToOpen.addEventListener('click', (e) => {
        order.classList.add('show-key');
    });
    copyInputBtn.addEventListener('click', async (e) => {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            await navigator.clipboard.writeText(input.value);
        } else {
            input.select();
            document.execCommand("copy");
        }
        showToastify("Code is copied", 'info');
    });
});