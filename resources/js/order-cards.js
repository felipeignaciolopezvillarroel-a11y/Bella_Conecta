document.addEventListener('alpine:init', () => {
    Alpine.data('cartApp', () => ({
        cart: JSON.parse(localStorage.getItem('bella_cart')) || [],
        openCheckout: false,
        isSubmitting: false,
        
        isAuthenticated: false,
        loginUrl: '/login',
        hasClearCartFlag: false,

        init() {
            if (!this.isAuthenticated) {
                this.cart = [];
                localStorage.removeItem('bella_cart');
            }

            this.$watch('cart', value => {
                localStorage.setItem('bella_cart', JSON.stringify(value));
            });

            if (this.hasClearCartFlag) {
                this.cart = [];
                localStorage.removeItem('bella_cart');
            }
        },

        addToCart(id, name, price, image, stock) {
            if (!this.isAuthenticated) {
                this.showToast('Debes iniciar sesión para agregar productos.', 'error');
                setTimeout(() => {
                    window.location.href = this.loginUrl;
                }, 2000);
                return;
            }

            const existing = this.cart.find(item => item.id === id);
            
            if (existing) {
                // Validar stock
                if (existing.quantity >= stock) {
                    this.showToast(`Solo hay ${stock} unidades disponibles de "${name}".`, 'warning');
                    return;
                }
                existing.quantity++;
                existing.stock = stock; // Actualizamos el stock
            } else {
                if (stock <= 0) {
                    this.showToast(`"${name}" está agotado.`, 'error');
                    return;
                }
                this.cart.push({ id, name, price, image, quantity: 1, stock });
            }
            
            this.showToast('¡Producto agregado al carrito!', 'success');
        },

        updateQty(id, change) {
            const item = this.cart.find(item => item.id === id);
            if (!item) return;

            const newQty = item.quantity + change;

            if (change > 0 && newQty > item.stock) {
                this.showToast(`Solo hay ${item.stock} unidades disponibles.`, 'warning');
                return;
            }

            item.quantity = newQty;
            if (item.quantity <= 0) {
                this.cart = this.cart.filter(i => i.id !== id);
            }
        },

        get cartTotal() {
            return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        },

        formatPrice(value) {
            return new Intl.NumberFormat('es-CL').format(value);
        },

        showToast(message, type = 'success') {
            window.dispatchEvent(new CustomEvent('toast', { 
                detail: { message: message, type: type } 
            }));
        }
    }));
});