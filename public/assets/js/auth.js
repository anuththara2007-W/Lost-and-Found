"use strict"; // makes JavaScript more strict and error-safe

/* =========================================================
   Password Visibility Toggle
========================================================= */
const passwordButtons = document.querySelectorAll(".toggle-password"); // Get all toggle buttons

passwordButtons.forEach(function (button) {
  // Loop through each button

  button.addEventListener("click", function () {
    // On button click

    const targetId = button.dataset.target; // Get target input id
    const inputField = document.getElementById(targetId); // Find input field

    if (!inputField) {
      // If input not found
      return; // Stop execution
    }

    let currentType = inputField.type; // Get current input type

    if (currentType === "password") {
      // If password hidden
      inputField.type = "text"; // Show password
    } else {
      // If password visible
      inputField.type = "password"; // Hide password
    }

    const hideIcon = button.querySelector(".icon-hide"); // Get hide icon
    const showIcon = button.querySelector(".icon-show"); // Get show icon

    if (currentType === "password") {
      // If was hidden
      if (hideIcon) hideIcon.style.display = "none"; // Hide hide-icon
      if (showIcon) showIcon.style.display = "block"; // Show show-icon
      button.setAttribute("aria-label", "Hide password"); // Accessibility label
    } else {
      // If was visible
      if (hideIcon) hideIcon.style.display = "block"; // Show hide-icon
      if (showIcon) showIcon.style.display = "none"; // Hide show-icon
      button.setAttribute("aria-label", "Show password"); // Accessibility label
    }
  });
});

/* =========================================================
   Password Strength Checker
========================================================= */
//not implimented yet, but will be in the future. This is just a placeholder for now.
const passwordInput = document.getElementById("password");
const strengthBar = document.querySelector(".strength-fill");
const strengthText = document.querySelector(".strength-label");

if (passwordInput !== null && strengthBar !== null && strengthText !== null) {
  passwordInput.addEventListener("input", function () {
    let value = passwordInput.value;
    let score = getPasswordScore(value);

    let weak = { label: "Weak", color: "#C96442", width: "25%" };
    let fair = { label: "Fair", color: "#D4940A", width: "50%" };
    let good = { label: "Good", color: "#5C7A65", width: "75%" };
    let strong = { label: "Strong", color: "#3B7A5C", width: "100%" };
    let empty = { label: "", color: "transparent", width: "0%" };

    let result = empty;

    if (score === 1) result = weak;
    else if (score === 2) result = fair;
    else if (score === 3) result = good;
    else if (score >= 4) result = strong;

    strengthBar.style.width = result.width;
    strengthBar.style.backgroundColor = result.color;
    strengthText.textContent = value.length > 0 ? result.label : "";
    strengthText.style.color = result.color;
  });
}

/* Password score function */
function getPasswordScore(password) {
  if (!password) {
    return 0;
  }

  let score = 0;

  if (password.length >= 8) {
    score = score + 1;
  }

  if (password.length >= 12) {
    score = score + 1;
  }

  if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
    score = score + 1;
  }

  if (password.match(/[0-9]/)) {
    score = score + 1;
  }

  if (password.match(/[^a-zA-Z0-9]/)) {
    score = score + 1;
  }

  return score;
}

/* =========================================================
   Confirm Password Match
========================================================= */
const confirmPasswordInput = document.getElementById("confirm_password"); // Get confirm password field

if (confirmPasswordInput !== null && passwordInput !== null) {
  // Ensure both inputs exist

  function checkPasswordMatch() {
    // Function to validate passwords

    const messageBox = document.getElementById("confirm-msg"); // Get message display

    if (!messageBox) {
      // If message box missing
      return; // Stop execution
    }

    let passwordValue = passwordInput.value; // Get password value
    let confirmValue = confirmPasswordInput.value; // Get confirm value

    if (confirmValue.length === 0) {
      // If confirm empty
      messageBox.className = "field-msg"; // Reset message style
      return; // Stop further checks
    }

    if (passwordValue === confirmValue) {
      // If passwords match
      messageBox.textContent = "Passwords match"; // Success message
      messageBox.className = "field-msg visible ok"; // Success style

      confirmPasswordInput.classList.remove("is-invalid"); // Remove error class
      confirmPasswordInput.classList.add("is-valid"); // Add success class
    } else {
      // If passwords do not match
      messageBox.textContent = "Passwords do not match"; // Error message
      messageBox.className = "field-msg visible error"; // Error style

      confirmPasswordInput.classList.remove("is-valid"); // Remove success class
      confirmPasswordInput.classList.add("is-invalid"); // Add error class
    }
  }

  confirmPasswordInput.addEventListener("input", checkPasswordMatch); // Check on typing confirm
  passwordInput.addEventListener("input", checkPasswordMatch); // Check on typing password
}
/* =========================================================
   Email Validation
========================================================= */
const emailInput = document.getElementById("email"); // Get email input field

if (emailInput !== null) {
  // Check input exists

  function validateEmailField() {
    // Email validation function

    let emailValue = emailInput.value.trim(); // Get trimmed value
    let messageBox = document.getElementById("email-msg"); // Get message box

    if (!messageBox) {
      // If message box missing
      return; // Stop execution
    }

    let pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Email regex pattern
    let isValid = pattern.test(emailValue); // Test email format

    if (emailValue.length === 0) {
      // If empty input
      return; // Skip validation
    }

    if (isValid === true) {
      // If valid email
      emailInput.classList.add("is-valid"); // Add valid class
      emailInput.classList.remove("is-invalid"); // Remove invalid class

      messageBox.className = "field-msg"; // Reset message style
    } else {
      // If invalid email
      emailInput.classList.add("is-invalid"); // Add error class
      emailInput.classList.remove("is-valid"); // Remove valid class

      messageBox.textContent = "Invalid email format"; // Show error text
      messageBox.className = "field-msg visible error"; // Apply error style
    }
  }

  let emailTimeout; // Store debounce timer

  emailInput.addEventListener("input", function () {
    // On typing
    clearTimeout(emailTimeout); // Clear previous timer

    emailTimeout = setTimeout(function () {
      // Delay validation
      validateEmailField(); // Run validation
    }, 500); // 500ms delay
  });

  emailInput.addEventListener("blur", validateEmailField); // Validate on focus out
}

/* =========================================================
   Username Check (API)
========================================================= */
const usernameInput = document.getElementById("username"); // Get username input

if (usernameInput !== null) {
  // Check input exists

  let usernameTimer; // Store debounce timer

  usernameInput.addEventListener("input", function () {
    // On typing
    clearTimeout(usernameTimer); // Clear previous timer

    let value = usernameInput.value.trim(); // Get trimmed value
    let messageBox = document.getElementById("username-msg"); // Get message box

    if (value.length < 3) {
      // If too short
      return; // Skip check
    }

    usernameTimer = setTimeout(function () {
      // Delay request
      checkUsername(value); // Call API check
    }, 500); // 500ms delay
  });

  async function checkUsername(username) {
    // Async API function
    try {
      const response = await fetch("/api/check-username?username=" + username); // Send request
      const data = await response.json(); // Parse JSON response

      const messageBox = document.getElementById("username-msg"); // Get message box

      if (data.available === true) {
        // If username free
        usernameInput.classList.add("is-valid"); // Add valid class
        usernameInput.classList.remove("is-invalid"); // Remove invalid class

        if (messageBox) {
          // If message box exists
          messageBox.textContent = "Username available"; // Success text
          messageBox.className = "field-msg visible ok"; // Success style
        }
      } else {
        // If username taken
        usernameInput.classList.add("is-invalid"); // Add error class
        usernameInput.classList.remove("is-valid"); // Remove valid class

        if (messageBox) {
          // If message box exists
          messageBox.textContent = "Username already taken"; // Error text
          messageBox.className = "field-msg visible error"; // Error style
        }
      }
    } catch (error) {
      // Handle request error
      // ignore error // Do nothing on error
    }
  }
}

/* =========================================================
   Profile Image Preview
========================================================= */

const imageInput = document.getElementById("profile_image"); // Get file input
const imagePreview = document.getElementById("avatar-preview-img"); // Get preview image

if (imageInput !== null && imagePreview !== null) {
  // Check elements exist

  imageInput.addEventListener("change", function () {
    // On file change

    let file = imageInput.files[0]; // Get selected file

    if (!file) {
      // If no file selected
      return; // Stop execution
    }

    let allowedTypes = ["image/jpeg", "image/png", "image/webp", "image/gif"]; // Allowed formats

    if (allowedTypes.indexOf(file.type) === -1) {
      // Check file type
      showToast("Invalid image type", "error"); // Show error
      imageInput.value = ""; // Reset input
      return; // Stop
    }

    if (file.size > 2 * 1024 * 1024) {
      // Check size > 2MB
      showToast("Image too large (max 2MB)", "error"); // Show error
      imageInput.value = ""; // Reset input
      return; // Stop
    }

    let reader = new FileReader(); // Create file reader

    reader.onload = function (e) {
      // When file loaded
      imagePreview.src = e.target.result; // Set preview image
      imagePreview.style.display = "block"; // Show preview
    };

    reader.readAsDataURL(file); // Convert file to URL
  });
}

/* =========================================================
   Toast System
========================================================= */

function showToast(message, type) {
  // Toast function

  if (!type) {
    // If no type given
    type = "info"; // Default type
  }

  let container = document.getElementById("toast-container"); // Get container

  if (!container) {
    // If container not exists
    container = document.createElement("div"); // Create container
    container.id = "toast-container"; // Set id

    container.style.position = "fixed"; // Fixed position
    container.style.bottom = "20px"; // Bottom spacing
    container.style.right = "20px"; // Right spacing

    document.body.appendChild(container); // Add to page
  }

  let toast = document.createElement("div"); // Create toast
  toast.textContent = message; // Set message

  toast.style.padding = "12px"; // Inner spacing
  toast.style.marginTop = "10px"; // Space between toasts

  container.appendChild(toast); // Add toast

  setTimeout(function () {
    // Auto remove
    toast.remove(); // Remove after time
  }, 3000); // 3 seconds
}

window.showToast = showToast; // Make function global
