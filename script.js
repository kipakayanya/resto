document.addEventListener('DOMContentLoaded', function() {
    const cartToggleBtn = document.getElementById('cart-toggle-btn');
    const cartSidebar = document.getElementById('cart-sidebar');
    const closeCartBtn = document.getElementById('close-cart-btn');
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
    const cartItemsContainer = document.getElementById('cart-items');
    const cartCountSpan = document.getElementById('cart-count');
    const cartTotalSpan = document.getElementById('cart-total');
    const checkoutBtn = document.getElementById('checkout-btn');
    const tableSelect = document.getElementById('tableSelect');

    let cart = [];

    cartToggleBtn.addEventListener('click', openCart);
    closeCartBtn.addEventListener('click', closeCart);
    addToCartBtns.forEach(btn => btn.addEventListener('click', addToCart));
    checkoutBtn.addEventListener('click', checkout);

    function openCart() { cartSidebar.classList.add('open'); }
    function closeCart() { cartSidebar.classList.remove('open'); }

    function addToCart(e) {
        const btn = e.target;
        const itemEl = btn.closest('.menu-item');
        const id = itemEl.dataset.id, name = itemEl.dataset.name, price = parseFloat(itemEl.dataset.price);
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) { existingItem.quantity++; } else { cart.push({ id, name, price, quantity: 1 }); }
        updateCartUI();
    }

    function removeFromCart(id) { cart = cart.filter(item => item.id !== id); updateCartUI(); }
    function changeQuantity(id, change) {
        const item = cart.find(item => item.id === id);
        if (item) { item.quantity += change; if (item.quantity <= 0) { removeFromCart(id); } else { updateCartUI(); } }
    }

    function updateCartUI() {
        cartItemsContainer.innerHTML = ''; let total = 0, itemCount = 0;
        if (cart.length === 0) { cartItemsContainer.innerHTML = '<p>Keranjang kosong.</p>'; }
        else {
            cart.forEach(item => {
                total += item.price * item.quantity; itemCount += item.quantity;
                const cartItemEl = document.createElement('div'); cartItemEl.className = 'cart-item';
                cartItemEl.innerHTML = `
                    <div class="cart-item-details"><div class="cart-item-name">${item.name}</div><div class="cart-item-price">Rp. ${item.price.toLocaleString('id-ID')}</div></div>
                    <div class="cart-item-quantity"><button onclick="changeQuantity('${item.id}', -1)">-</button><span>${item.quantity}</span><button onclick="changeQuantity('${item.id}', 1)">+</button></div>`;
                cartItemsContainer.appendChild(cartItemEl);
            });
        }
        cartCountSpan.textContent = itemCount; cartTotalSpan.textContent = `Rp. ${total.toLocaleString('id-ID')}`;
    }

    function checkout() {
        const id_meja = tableSelect.value, nama_pelanggan = document.getElementById('customer-name').value;
        if (!id_meja) { alert('Silakan pilih meja.'); return; } if (cart.length === 0) { alert('Keranjang kosong.'); return; }
        fetch('process_order.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams({ id_meja, nama_pelanggan, cart_items: JSON.stringify(cart) }) })
        .then(res => res.json()).then(data => {
            if (data.success) { alert(`Pesanan berhasil! Order ID: #${data.order_id}.`); cart = []; updateCartUI(); document.getElementById('customer-name').value=''; tableSelect.value=''; closeCart(); window.location.reload(); }
            else { alert('Error: ' + (data.message || 'Gagal mengirim pesanan.')); }
        }).catch(err => { console.error('Error:', err); alert('Gagal mengirim pesanan. Coba lagi.'); });
    }
    window.removeFromCart = removeFromCart; window.changeQuantity = changeQuantity;
    updateCartUI();
});