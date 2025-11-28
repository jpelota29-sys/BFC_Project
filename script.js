// ==============================
// PRODUCT DETAILS TOGGLE
// ==============================

const productBoxes = document.querySelectorAll('.product-box');
const detailsBoxes = document.querySelectorAll('.details-box');
const productDetailsContainer = document.querySelector('.product-details');
const productSection = document.querySelector('.product-grid');

// Hide all details and reset layout
function hideAllDetails() {
  detailsBoxes.forEach(box => box.style.display = 'none');
  productDetailsContainer.style.display = 'none';
  productSection.style.width = '100%';
}

// Initial state
hideAllDetails();

// Click product → show details
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
      productSection.style.width = '40%';
    }
  });
});

// Close details
document.querySelectorAll('.close-details').forEach(btn => {
  btn.addEventListener('click', e => {
    e.stopPropagation();
    hideAllDetails();
  });
});

// Click outside → hide
productDetailsContainer.addEventListener('click', e => {
  if (e.target === productDetailsContainer) hideAllDetails();
});


// ==============================
// CART SYSTEM
// ==============================

let cart = [];
const cartEl = document.querySelector('.cart');
const cartItemsEl = document.querySelector('.cart-items');
const cartTotalEl = document.querySelector('.total-price');
const openCartBtn = document.querySelector('.open-cart');

// Load cart from DB
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

  // Remove item
  document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const index = parseInt(btn.dataset.index);
      const productId = btn.dataset.id;

      fetch('remove_from_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${productId}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          cart.splice(index, 1);
          updateCartUI();
        }
      });
    });
  });
}


// ==============================
// ADD TO CART (LOGIN REQUIRED)
// ==============================

document.querySelectorAll('.add-to-cart').forEach(button => {
  button.addEventListener('click', () => {

    if (!isLoggedIn) {
      document.getElementById("loginModal").style.display = "flex";
      return;
    }

    const productId = button.dataset.id;
    const productName = button.dataset.name;
    const productPrice = parseFloat(button.dataset.price);

    fetch('add_to_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `product_id=${productId}&quantity=1`
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        const existing = cart.find(item => item.id == productId);
        if (existing) {
          existing.quantity += 1;
        } else {
          cart.push({
            id: productId,
            name: productName,
            price: productPrice,
            quantity: 1
          });
        }
        updateCartUI();

        cartEl.style.display = 'block';
        setTimeout(() => cartEl.classList.add('open'), 50);
      }
    });
  });
});


// ==============================
// OPEN CART (LOGIN REQUIRED)
// ==============================

openCartBtn.addEventListener('click', () => {
  if (!isLoggedIn) {
    document.getElementById("loginModal").style.display = "flex";
    return;
  }

  cartEl.style.display = 'block';
  setTimeout(() => cartEl.classList.add('open'), 50);
});


// Close cart
document.querySelector('.close-cart').addEventListener('click', () => {
  cartEl.classList.remove('open');
  setTimeout(() => cartEl.style.display = 'none', 400);
});


// Load cart on page start
loadCart();


// ==============================
// CHECKOUT SYSTEM
// ==============================

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

// Open checkout modal
checkoutBtn.addEventListener('click', openCheckout);

// Close checkout
closeCheckoutBtn.addEventListener('click', () => {
  checkoutModal.style.display = 'none';
});

window.addEventListener('click', e => {
  if (e.target === checkoutModal) checkoutModal.style.display = 'none';
});

// Submit checkout
checkoutForm.addEventListener('submit', e => {
  e.preventDefault();

  const data = {
    customerName: document.getElementById('customerName').value,
    customerAddress: document.getElementById('customerAddress').value,
    customerPhone: document.getElementById('customerPhone').value,
    paymentMethod: document.getElementById('paymentMethod').value
  };

  if (!data.paymentMethod) return alert("Please choose a payment method.");

  fetch('checkout_process.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {

      cart = [];
      updateCartUI();
      checkoutModal.style.display = 'none';

      const notification = document.getElementById('orderNotification');
      notification.classList.add('show');

      setTimeout(() => {
        notification.classList.remove('show');
      }, 3000);
    }
  })
  .catch(err => console.error(err));
});
