let orderMethods = document.querySelectorAll('.order-method');
orderMethods.forEach(method => {
    let titleBtn = method.querySelector('.order-method__title');
    titleBtn.addEventListener('click', (e) =>{
        orderMethods.forEach(m => {
            if(m == method)
                return;
            m.classList.remove('active');
        });
        method.classList.add('active');
    });
});