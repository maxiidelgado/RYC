class Carrito {
    constructor() {
        // Inicializa el carrito desde localStorage o un objeto vacío si no existe.
        this.carrito = JSON.parse(localStorage.getItem('carrito')) || {};
        this.renderCarrito();
    }

    // Agregar un producto al carrito
    agregarProducto(id, nombre, precio) {
        // Si el producto ya existe, aumenta su cantidad
        if (this.carrito[id]) {
            this.carrito[id].cantidad++;
        } else {
            // Si no existe, lo añade al carrito con cantidad 1
            this.carrito[id] = { id, nombre, precio, cantidad: 1 };
        }
        // Guarda el carrito en localStorage y lo renderiza
        this.guardarCarrito();
        this.renderCarrito();
    }

    // Actualizar la cantidad de un producto
    actualizarCantidad(id, nuevaCantidad) {
        if (this.carrito[id]) {
            // Si la cantidad nueva es 0 o menor, se elimina el producto
            if (nuevaCantidad <= 0) {
                this.eliminarProducto(id);
            } else {
                this.carrito[id].cantidad = nuevaCantidad;
                this.guardarCarrito();
                this.renderCarrito();
            }
        }
    }

    // Eliminar un producto del carrito
    eliminarProducto(id) {
        delete this.carrito[id];
        this.guardarCarrito();
        this.renderCarrito();
    }

    // Guardar el carrito en localStorage
    guardarCarrito() {
        localStorage.setItem('carrito', JSON.stringify(this.carrito));
    }

    // Renderizar el carrito en el contenedor del HTML
    renderCarrito() {
        const carritoContainer = document.getElementById('carrito-container');
        carritoContainer.innerHTML = '';

        let total = 0;

        for (let id in this.carrito) {
            const producto = this.carrito[id];
            total += producto.precio * producto.cantidad;

            carritoContainer.innerHTML += `
                <div class="producto-carrito">
                    <span>${producto.nombre}</span>
                    <input type="number" value="${producto.cantidad}" min="1" class="cantidad-producto" data-id="${producto.id}">
                    <span>Precio Unitario: $${producto.precio.toFixed(2)}</span>
                    <span>Total: $${(producto.cantidad * producto.precio).toFixed(2)}</span>
                    <button class="eliminar-producto" data-id="${producto.id}">Eliminar</button>
                </div>
            `;
        }

        document.getElementById('total-carrito').textContent = `$${total.toFixed(2)}`;

        // Vuelve a agregar los event listeners después de renderizar
        this.agregarEventListeners();
    }

    // Agregar los event listeners a los inputs y botones
    agregarEventListeners() {
        // Cambia la cantidad de productos
        document.querySelectorAll('.cantidad-producto').forEach(input => {
            input.addEventListener('change', (e) => {
                const id = e.target.dataset.id;
                const nuevaCantidad = parseInt(e.target.value);
                // Evita que se ponga cantidad negativa o nula
                if (nuevaCantidad >= 1) {
                    this.actualizarCantidad(id, nuevaCantidad);
                } else {
                    this.eliminarProducto(id);
                }
            });
        });

        // Elimina un producto del carrito
        document.querySelectorAll('.eliminar-producto').forEach(button => {
            button.addEventListener('click', (e) => {
                const id = e.target.dataset.id;
                this.eliminarProducto(id);
            });
        });
    }
}

// Inicializar el carrito
const carrito = new Carrito();

// Función global para agregar productos desde los botones en el HTML
function agregarProductoAlCarrito(id, nombre, precio) {
    carrito.agregarProducto(id, nombre, precio);
}
