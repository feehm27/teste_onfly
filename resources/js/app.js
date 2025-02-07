import './bootstrap';

const userId = window.Laravel.user.id;
window.Echo.channel('user.' + userId)

window.Echo.channel('user.' + userId)
    .listen('OrderTravelUpdated', (event) => {
        console.log('Pedido atualizado:', event);
        alert('Seu pedido foi atualizado!');
    });
