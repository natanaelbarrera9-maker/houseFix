/* public/js/pos.js */

document.addEventListener('alpine:init', () => {
    Alpine.data('posSystem', (productsData, salesRoute, csrfToken) => ({
        search: '',
        allProducts: productsData,
        cart: [],
        processing: false,

        // Filtrado en tiempo real
        get filteredProducts() {
            if (this.search === '') return this.allProducts;
            const term = this.search.toLowerCase();
            return this.allProducts.filter(product => {
                const name = product.name.toLowerCase();
                const sku = product.sku ? product.sku.toLowerCase() : '';
                return name.includes(term) || sku.includes(term);
            });
        },

        // Total calculado
        get total() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        // Agregar al carrito con "Toast" (notificación pequeña)
        addToCart(product) {
            let existingItem = this.cart.find(item => item.id === product.id);

            if (existingItem) {
                if (existingItem.quantity + 1 <= product.quantity) {
                    existingItem.quantity++;
                    this.showToast('Cantidad actualizada', 'info');
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stock Límite',
                        text: `Solo tienes ${product.quantity} unidades disponibles de este producto.`,
                        confirmButtonColor: '#f59e0b'
                    });
                }
            } else {
                if (product.quantity > 0) {
                    this.cart.push({
                        id: product.id,
                        name: product.name,
                        price: parseFloat(product.price),
                        quantity: 1,
                        max_stock: product.quantity
                    });
                    this.showToast('Producto agregado', 'success');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Agotado',
                        text: 'Este producto no tiene existencias.',
                        confirmButtonColor: '#ef4444'
                    });
                }
            }
        },

        // Eliminar con confirmación suave
        removeFromCart(index) {
            // No preguntamos si es solo 1 click rápido, mejor experiencia de usuario
            // O podemos usar un toast de "Eliminado"
            this.cart.splice(index, 1);
            this.showToast('Producto eliminado', 'warning');
        },

        // Procesar Venta con SweetAlert bonito
        processSale() {
            if (this.cart.length === 0) return;

            // Ventana Emergente Profesional
            Swal.fire({
                title: '¿Cobrar Venta?',
                html: `Total a pagar: <b>$${this.total.toFixed(2)}</b>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5', // Indigo-600
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, Cobrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.executeTransaction();
                }
            });
        },

        // Lógica interna de envío
        executeTransaction() {
            this.processing = true;

            fetch(salesRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ cart: this.cart })
            })
            .then(response => response.json())
            .then(data => {
                this.processing = false;
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Venta Exitosa!',
                        text: 'El inventario ha sido actualizado.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        this.cart = [];
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                this.processing = false;
                Swal.fire('Error', 'Fallo de conexión con el servidor', 'error');
                console.error(error);
            });
        },

        // Utilidad: Notificación pequeña en la esquina (Toast)
        showToast(title, icon) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: icon,
                title: title
            });
        }
    }));
});