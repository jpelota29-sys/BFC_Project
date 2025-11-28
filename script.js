const productBoxes = document.querySelectorAll('.product-box');
const detailsBoxes = document.querySelectorAll('.details-box');
const productDetailsContainer = document.querySelector('.product-details');
const productSection = document.querySelector('.product-grid');

// Hide all details and expand product grid
function hideAllDetails() {
  detailsBoxes.forEach(box => box.style.display = 'none');
  productDetailsContainer.style.display = 'none';  
  productSection.style.width = '100%'; // restore full width
}

// Initially hide details
hideAllDetails();

// When a product is clicked
productBoxes.forEach(box => {
  box.addEventListener('click', e => {
    e.preventDefault();
    const targetId = box.getAttribute('href').substring(1);

    hideAllDetails();

    const targetBox = document.getElementById(targetId);
    if (targetBox) {
      targetBox.style.display = 'flex';
      targetBox.style.flexDirection = 'column';
      productDetailsContainer.style.display = 'flex';
      productSection.style.width = '40%'; // shrink grid
    }
  });

  // Close button inside each details box
  document.querySelectorAll('.close-details').forEach(btn => {
    btn.addEventListener('click', e => {
      e.stopPropagation(); // prevent triggering other click events
      hideAllDetails();
    });
  });
  
});


// Click outside details to hide
productDetailsContainer.addEventListener('click', e => {
  if (e.target === productDetailsContainer) {
    hideAllDetails();
  }
});

// Cart array to track client-side items
let cart = [];
const cartEl = document.querySelector('.cart');
const cartItemsEl = document.querySelector('.cart-items');
const cartTotalEl = document.querySelector('.total-price');
const openCartBtn = document.querySelector('.open-cart');

// Fetch cart from DB on page load
function loadCart() {
  fetch('get_cart.php')
    .then(res => res.json())
    .then(data => {
      cart = data.map(item => ({
        id: item.product_id,
        name: item.name,
        price: parseFloat(item.price),
        quantity: parseInt(item.quantity)
      }));
      updateCartUI();
    });
}

// Update cart UI
function updateCartUI() {
  cartItemsEl.innerHTML = '';
  let total = 0;

  cart.forEach((item, index) => {
    const li = document.createElement('li');
    li.innerHTML = `
      ${item.name} - ₱${(item.price * item.quantity).toFixed(2)} (x${item.quantity})
      <button class="remove-btn" data-index="${index}" data-id="${item.id}">Remove</button>
    `;
    cartItemsEl.appendChild(li);
    total += item.price * item.quantity;
  });

  cartTotalEl.textContent = `₱${total.toFixed(2)}`;

  // Remove button functionality
  document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const idx = parseInt(btn.dataset.index);
      const productId = btn.dataset.id;

      // Remove from DB
      fetch('remove_from_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${productId}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          // Remove from local cart array
          cart.splice(idx, 1);
          updateCartUI();
        }
      });
    });
  });
}

// Add to cart buttons
document.querySelectorAll('.add-to-cart').forEach(button => {
  button.addEventListener('click', () => {
    const productId = button.dataset.id;
    const productName = button.dataset.name;
    const productPrice = parseFloat(button.dataset.price);

    // Add to DB
    fetch('add_to_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `product_id=${productId}&quantity=1`
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        // Update local cart array
        const existing = cart.find(item => item.id == productId);
        if (existing) {
          existing.quantity += 1;
        } else {
          cart.push({ id: productId, name: productName, price: productPrice, quantity: 1 });
        }
        updateCartUI();

        // Show cart
        cartEl.style.display = 'block';
        setTimeout(() => cartEl.classList.add('open'), 50);
      }
    });
  });
});

// Open cart manually
openCartBtn.addEventListener('click', () => {
  cartEl.style.display = 'block';
  setTimeout(() => cartEl.classList.add('open'), 50);
});

// Close cart
document.querySelector('.close-cart').addEventListener('click', () => {
  cartEl.classList.remove('open');
  setTimeout(() => cartEl.style.display = 'none', 400);
});

// Load cart when page loads
loadCart();

// Checkout
const checkoutModal = document.getElementById('checkoutModal');
const checkoutForm = document.getElementById('checkoutForm');
const checkoutItemsEl = checkoutForm.querySelector('.checkout-items');
const checkoutTotalEl = checkoutForm.querySelector('.checkout-total');
const checkoutBtn = document.querySelector('.checkout-btn');
const closeCheckoutBtn = document.querySelector('.close-checkout');

function openCheckout() {
  checkoutItemsEl.innerHTML = '';
  let total = 0;
  cart.forEach(item => {
    const li = document.createElement('li');
    li.textContent = `${item.name} - ₱${(item.price * item.quantity).toFixed(2)} (x${item.quantity})`;
    checkoutItemsEl.appendChild(li);
    total += item.price * item.quantity;
  });
  checkoutTotalEl.textContent = `₱${total.toFixed(2)}`;
  checkoutModal.style.display = 'flex';
}

// Open modal
checkoutBtn.addEventListener('click', openCheckout);

// Close modal
closeCheckoutBtn.addEventListener('click', () => {
  checkoutModal.style.display = 'none';
});
window.addEventListener('click', e => {
  if (e.target === checkoutModal) checkoutModal.style.display = 'none';
});

// Handle checkout submission
checkoutForm.addEventListener('submit', e => {
  e.preventDefault();

  const customerName = document.getElementById('customerName').value;
  const customerAddress = document.getElementById('customerAddress').value; // Updated to address
  const customerPhone = document.getElementById('customerPhone').value;
  const paymentMethod = document.getElementById('paymentMethod').value;

  if (!paymentMethod) return alert('Please select a payment method.');

  fetch('checkout_process.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      customerName,
      customerAddress,
      customerPhone,
      paymentMethod
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {

      // CLEAR CART & CLOSE MODAL
      cart = [];
      updateCartUI();
      checkoutModal.style.display = 'none';

      // ====== SHOW ORDER COMPLETE POPUP ======
      const notification = document.createElement('div');
      notification.classList.add('order-notification');
      notification.textContent = '✅ Order Complete! Thank you for your purchase.';
      document.body.appendChild(notification);

      // Show popup with animation
      setTimeout(() => notification.classList.add('show'), 50);

      // Hide after 3 seconds
      setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => document.body.removeChild(notification), 300);
      }, 3000);

    } else {
      alert('⚠️ Something went wrong. Please try again.');
    }
  })
  .catch(err => console.error(err));
});// Handle checkout submission
checkoutForm.addEventListener('submit', e => {
  e.preventDefault();

  const customerName = document.getElementById('customerName').value;
  const customerAddress = document.getElementById('customerAddress').value;
  const customerPhone = document.getElementById('customerPhone').value;
  const paymentMethod = document.getElementById('paymentMethod').value;

  if (!paymentMethod) return alert('Please select a payment method.');

  fetch('checkout_process.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ customerName, customerAddress, customerPhone, paymentMethod })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {

      // CLEAR CART & CLOSE MODAL
      cart = [];
      updateCartUI();
      checkoutModal.style.display = 'none';

      // SHOW NOTIFICATION (existing div)
      const notification = document.getElementById('orderNotification');
      notification.classList.add('show');

      // Hide after 3s
      setTimeout(() => {
        notification.classList.remove('show');
      }, 3000);
    }
  })
  .catch(err => console.error(err));
});






