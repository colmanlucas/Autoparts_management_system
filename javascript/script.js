// Cart functionality
let cart = [];

function addToCart(productId, name, price, maxStock) {
    const existingItem = cart.find(item => item.id === productId);

    if (existingItem) {
        if (existingItem.quantity < maxStock) {
            existingItem.quantity++;
            existingItem.subtotal = existingItem.quantity * price;
        } else {
            alert('Maximum stock reached!');
            return;
        }
    } else {
        cart.push({
            id: productId,
            name: name,
            price: price,
            quantity: 1,
            subtotal: price,
            maxStock: maxStock
        });
    }

    updateCartUI();
    showNotification('Item added to cart');
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    updateCartUI();
}

function updateQuantity(productId, change) {
    const item = cart.find(item => item.id === productId);
    if (!item) return;

    const newQty = item.quantity + change;

    if (newQty <= 0) {
        removeFromCart(productId);
        return;
    }

    if (newQty > item.maxStock) {
        alert('Not enough stock available!');
        return;
    }

    item.quantity = newQty;
    item.subtotal = item.quantity * item.price;
    updateCartUI();
}

function updateCartUI() {
    const cartContainer = document.getElementById('cart-items');
    const cartInput = document.getElementById('cart-data');
    const totalElement = document.getElementById('cart-total');
    const checkoutBtn = document.getElementById('checkout-btn');

    if (!cartContainer) return;

    if (cart.length === 0) {
        cartContainer.innerHTML = '<p style="text-align:center;color:#999;padding:2rem 0;">Cart is empty</p>';
        if (totalElement) totalElement.textContent = '$0.00';
        if (checkoutBtn) checkoutBtn.disabled = true;
        if (cartInput) cartInput.value = '';
        return;
    }

    let total = 0;
    cartContainer.innerHTML = cart.map(item => {
        total += item.subtotal;
        return `
            <div class="cart-item">
                <div>
                    <div style="font-weight:600;">${item.name}</div>
                    <div style="font-size:0.85rem;color:#777;">$${item.price.toFixed(2)} each</div>
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <button type="button" onclick="updateQuantity(${item.id}, -1)" style="width:30px;height:30px;border:1px solid #ddd;background:white;border-radius:4px;cursor:pointer;">-</button>
                    <span style="min-width:30px;text-align:center;font-weight:600;">${item.quantity}</span>
                    <button type="button" onclick="updateQuantity(${item.id}, 1)" style="width:30px;height:30px;border:1px solid #ddd;background:white;border-radius:4px;cursor:pointer;">+</button>
                    <button type="button" onclick="removeFromCart(${item.id})" style="margin-left:0.5rem;color:#c62828;background:none;border:none;cursor:pointer;font-size:1.2rem;">×</button>
                </div>
            </div>
            <div style="text-align:right;font-size:0.9rem;color:#555;padding-bottom:0.5rem;">
                $${item.subtotal.toFixed(2)}
            </div>
        `;
    }).join('');

    if (totalElement) totalElement.textContent = '$' + total.toFixed(2);
    if (checkoutBtn) checkoutBtn.disabled = false;
    if (cartInput) cartInput.value = JSON.stringify(cart);
}

function showNotification(message) {
    const notif = document.createElement('div');
    notif.className = 'alert alert-success';
    notif.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;animation:slideIn 0.3s ease;';
    notif.textContent = message;
    document.body.appendChild(notif);

    setTimeout(() => {
        notif.remove();
    }, 3000);
}

// Modal functions
function openModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

// Search functionality
function searchProducts() {
    const query = document.getElementById('search-input').value.toLowerCase();
    const cards = document.querySelectorAll('.part-card');

    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(query) ? 'block' : 'none';
    });
}

// Print invoice
function printInvoice() {
    window.print();
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Close modals on outside click
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });
});