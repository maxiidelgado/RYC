// Función para calcular el total del carrito
function calcularTotal() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || {};
    let total = 0;

    
    Object.values(carrito).forEach(producto => {
        total += producto.precio * producto.cantidad;
    });

    return total;
}

function procesarCheckout() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || {};
    const total = calcularTotal();

    
    const productos = Object.values(carrito).map(item => ({
        id: item.id,
        cantidad: item.cantidad,
        precio: item.precio
    }));

    
    fetch('path/to/checkout.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            total: total,
            productos: JSON.stringify(productos) 
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            
            localStorage.removeItem('carrito');
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
