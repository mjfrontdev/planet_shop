// Initialize AOS
AOS.init({
  duration: 800,
  easing: "ease-in-out",
  once: true,
});

// Toast notification function
function showToast(message, type = "success") {
  if (window.Swal) {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: type === 'success' ? 'success' : 'error',
      title: message,
      showConfirmButton: false,
      timer: 2000,
      timerProgressBar: true
    });
  } else {
    const toast = document.createElement("div");
    toast.className = `toast ${type}`;
    toast.textContent = message;
    const container =
      document.querySelector(".toast-container") ||
      (() => {
        const container = document.createElement("div");
        container.className = "toast-container";
        document.body.appendChild(container);
        return container;
      })();
    container.appendChild(toast);
    setTimeout(() => {
      toast.style.opacity = "0";
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }
}

// Form validation
function validateForm(form) {
  const inputs = form.querySelectorAll("input, textarea");
  let isValid = true;

  inputs.forEach((input) => {
    if (input.hasAttribute("required") && !input.value.trim()) {
      isValid = false;
      input.classList.add("is-invalid");
    } else {
      input.classList.remove("is-invalid");
    }

    if (input.type === "email" && input.value) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(input.value)) {
        isValid = false;
        input.classList.add("is-invalid");
      }
    }
  });

  return isValid;
}

// Contact form handling
const contactForm = document.getElementById("contact-form");
if (contactForm) {
  contactForm.addEventListener("submit", function (e) {
    e.preventDefault();

    if (validateForm(this)) {
      // Show loading spinner
      const spinner = document.createElement("div");
      spinner.className = "spinner-overlay";
      spinner.innerHTML = '<div class="spinner"></div>';
      document.body.appendChild(spinner);

      // Simulate form submission
      setTimeout(() => {
        spinner.remove();
        showToast("پیام شما با موفقیت ارسال شد");
        this.reset();
      }, 1500);
    }
  });
}

// Product loading
function loadProducts() {
  const productsContainer = document.getElementById("products-container");
  if (!productsContainer) return;

  // Sample products data (in real application, this would come from the server)
  const products = [
    {
      id: 1,
      name: "گیاه آپارتمانی فیکوس",
      price: "۲۵۰,۰۰۰",
      image: "assets/images/kendal-TW2bfT_tWDI-unsplash.jpg",
      description: "گیاه فیکوس با برگ‌های براق و زیبا، مناسب برای فضای داخلی",
    },
    {
      id: 2,
      name: "گیاه دارویی آلوئه ورا",
      price: "۱۸۰,۰۰۰",
      image: "assets/images/ceyda-ciftci-dDVU6D_6T80-unsplash.jpg",
      description: "گیاه آلوئه ورا با خواص درمانی فراوان",
    },
    {
      id: 3,
      name: "گیاه زینتی کاکتوس",
      price: "۱۲۰,۰۰۰",
      image: "assets/images/jackie-dilorenzo-RyLsRzy9jIA-unsplash.jpg",
      description: "کاکتوس زیبا و مقاوم، مناسب برای نگهداری آسان",
    },
  ];

  // Create product cards
  products.forEach((product) => {
    const card = document.createElement("div");
    card.className = "col-md-4 mb-4";
    card.innerHTML = `
            <div class="card product-card" data-aos="fade-up">
                <img src="${product.image}" class="card-img-top" alt="${product.name}">
                <div class="card-body">
                    <h5 class="card-title">${product.name}</h5>
                    <p class="card-text">${product.description}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="price">${product.price} تومان</span>
                        <button class="btn btn-success add-to-cart" data-id="${product.id}" data-name="${product.name}" data-price="${product.price}" data-image="${product.image}">
                            <i class="fas fa-shopping-cart"></i> افزودن به سبد
                        </button>
                    </div>
                </div>
            </div>
        `;
    productsContainer.appendChild(card);
  });
  bindAddToCartButtons();
}

// Add to cart functionality
function updateCartBadge(count) {
  if (window.localStorage) localStorage.setItem("cart_count", count);
  const badge = document.getElementById("cart-count");
  if (badge) {
    badge.textContent = count;
    badge.style.display = count > 0 ? "inline-block" : "none";
  }
}

function bindAddToCartButtons() {
  document.querySelectorAll(".add-to-cart").forEach(function (btn) {
    btn.onclick = function () {
      const id = this.getAttribute("data-id");
      const name = this.getAttribute("data-name");
      let price = this.getAttribute("data-price");
      price = price.replace(/,/g, ""); // حذف ویرگول از قیمت
      const image = this.getAttribute("data-image");
      fetch("add_to_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id=${id}&name=${encodeURIComponent(
          name
        )}&price=${price}&image=${encodeURIComponent(image)}`,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            updateCartBadge(data.count);
            showToast("محصول به سبد خرید اضافه شد");
          } else {
            showToast("خطا در افزودن به سبد خرید", "error");
          }
        });
    };
  });
}

document.addEventListener("DOMContentLoaded", function () {
  bindAddToCartButtons();
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  });
});
