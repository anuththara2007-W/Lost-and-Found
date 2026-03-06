/**
 * Lost & Found — Auth JS
 * public/assets/js/auth.js
 *
 * Handles: password toggle, password strength meter,
 * client-side validation, remember-me, avatar preview,
 * and form submit UX.
 */

"use strict";

/* ═══════════════════════════════════════════════
   Password Visibility Toggle
   ═══════════════════════════════════════════════ */

document.querySelectorAll(".toggle-password").forEach((btn) => {
  btn.addEventListener("click", () => {
    const targetId = btn.dataset.target;
    const input = document.getElementById(targetId);
    if (!input) return;

    const isText = input.type === "text";
    input.type = isText ? "password" : "text";

    const iconHide = btn.querySelector(".icon-hide");
    const iconShow = btn.querySelector(".icon-show");
    if (iconHide) iconHide.style.display = isText ? "block" : "none";
    if (iconShow) iconShow.style.display = isText ? "none" : "block";

    btn.setAttribute("aria-label", isText ? "Show password" : "Hide password");
  });
});

/* ═══════════════════════════════════════════════
   Password Strength Meter
   ═══════════════════════════════════════════════ */

const strengthInput = document.getElementById("password");
const strengthFill = document.querySelector(".strength-fill");
const strengthLabel = document.querySelector(".strength-label");

if (strengthInput && strengthFill && strengthLabel) {
  strengthInput.addEventListener("input", () => {
    const val = strengthInput.value;
    const score = calcStrength(val);

    const levels = [
      { label: "", color: "transparent", width: "0%" },
      { label: "Weak", color: "#C96442", width: "25%" },
      { label: "Fair", color: "#D4940A", width: "55%" },
      { label: "Good", color: "#5C7A65", width: "80%" },
      { label: "Strong", color: "#3B7A5C", width: "100%" },
    ];

    const lvl = levels[score];
    strengthFill.style.width = lvl.width;
    strengthFill.style.background = lvl.color;
    strengthLabel.textContent = val.length ? lvl.label : "";
    strengthLabel.style.color = lvl.color;
  });
}

/**
 * Returns 0-4 strength score.
 */
function calcStrength(pw) {
  if (!pw) return 0;
  let score = 0;
  if (pw.length >= 8) score++;
  if (pw.length >= 12) score++;
  if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++;
  if (/[0-9]/.test(pw)) score++;
  if (/[^a-zA-Z0-9]/.test(pw)) score++;
  return Math.min(4, score);
}

/* ═══════════════════════════════════════════════
   Confirm Password Match
   ═══════════════════════════════════════════════ */

const confirmInput = document.getElementById("confirm_password");

if (confirmInput && strengthInput) {
  const showMatch = () => {
    const msg = document.getElementById("confirm-msg");
    if (!msg) return;

    if (!confirmInput.value) {
      msg.classList.remove("visible", "error", "ok");
      return;
    }

    if (confirmInput.value === strengthInput.value) {
      msg.textContent = "✓ Passwords match";
      msg.className = "field-msg visible ok";
      confirmInput.classList.remove("is-invalid");
      confirmInput.classList.add("is-valid");
    } else {
      msg.textContent = "Passwords do not match";
      msg.className = "field-msg visible error";
      confirmInput.classList.remove("is-valid");
      confirmInput.classList.add("is-invalid");
    }
  };

  confirmInput.addEventListener("input", showMatch);
  strengthInput.addEventListener("input", () => {
    if (confirmInput.value) showMatch();
  });
}

/* ═══════════════════════════════════════════════
   Email Real-Time Validation
   ═══════════════════════════════════════════════ */

const emailInput = document.getElementById("email");

if (emailInput) {
  let emailTimer = null;

  emailInput.addEventListener("input", () => {
    clearTimeout(emailTimer);
    emailTimer = setTimeout(() => validateEmail(), 400);
  });

  emailInput.addEventListener("blur", validateEmail);

  function validateEmail() {
    const val = emailInput.value.trim();
    const msg = document.getElementById("email-msg");
    if (!val || !msg) return;

    const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
    if (valid) {
      emailInput.classList.remove("is-invalid");
      emailInput.classList.add("is-valid");
      msg.className = "field-msg";
    } else {
      emailInput.classList.remove("is-valid");
      emailInput.classList.add("is-invalid");
      msg.textContent = "Please enter a valid email address.";
      msg.className = "field-msg visible error";
    }
  }
}

/* ═══════════════════════════════════════════════
   Username Availability (debounced fetch)
   ═══════════════════════════════════════════════ */

const usernameInput = document.getElementById("username");

if (usernameInput) {
  let usernameTimer = null;

  usernameInput.addEventListener("input", () => {
    clearTimeout(usernameTimer);
    const val = usernameInput.value.trim();
    const msg = document.getElementById("username-msg");

    if (!val || val.length < 3) {
      if (msg) msg.className = "field-msg";
      usernameInput.classList.remove("is-valid", "is-invalid");
      return;
    }

    if (!/^[a-zA-Z0-9_]+$/.test(val)) {
      if (msg) {
        msg.textContent = "Only letters, numbers, and underscores allowed.";
        msg.className = "field-msg visible error";
      }
      usernameInput.classList.add("is-invalid");
      usernameInput.classList.remove("is-valid");
      return;
    }

    usernameTimer = setTimeout(() => checkUsernameAvailability(val), 500);
  });

  async function checkUsernameAvailability(username) {
    const msg = document.getElementById("username-msg");
    try {
      const resp = await fetch(
        `/api/check-username?username=${encodeURIComponent(username)}`,
      );
      const data = await resp.json();

      if (data.available) {
        usernameInput.classList.add("is-valid");
        usernameInput.classList.remove("is-invalid");
        if (msg) {
          msg.textContent = "✓ Username is available";
          msg.className = "field-msg visible ok";
        }
      } else {
        usernameInput.classList.add("is-invalid");
        usernameInput.classList.remove("is-valid");
        if (msg) {
          msg.textContent = "That username is already taken.";
          msg.className = "field-msg visible error";
        }
      }
    } catch (_) {
      // Silently fail — server-side will validate
    }
  }
}

/* ═══════════════════════════════════════════════
   Profile Image Preview
   ═══════════════════════════════════════════════ */

const avatarInput = document.getElementById("profile_image");
const avatarPreview = document.getElementById("avatar-preview-img");

if (avatarInput && avatarPreview) {
  avatarInput.addEventListener("change", () => {
    const file = avatarInput.files[0];
    if (!file) return;

    const allowed = ["image/jpeg", "image/png", "image/webp", "image/gif"];
    if (!allowed.includes(file.type)) {
      showToast("Please select a valid image (JPG, PNG, WebP).", "error");
      avatarInput.value = "";
      return;
    }

    if (file.size > 2 * 1024 * 1024) {
      showToast("Image must be under 2 MB.", "error");
      avatarInput.value = "";
      return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
      avatarPreview.src = e.target.result;
      avatarPreview.style.display = "block";
      const placeholder = document.querySelector(".avatar-placeholder");
      if (placeholder) placeholder.style.display = "none";
    };
    reader.readAsDataURL(file);
  });
}

/* ═══════════════════════════════════════════════
   Form Submit — Loading State
   ═══════════════════════════════════════════════ */

document.querySelectorAll("form.auth-form").forEach((form) => {
  form.addEventListener("submit", function (e) {
    const submitBtn = form.querySelector(".btn-submit");
    if (!submitBtn) return;

    // Terms checkbox guard (register only)
    const termsCheck = form.querySelector("#terms");
    if (termsCheck && !termsCheck.checked) {
      e.preventDefault();
      showToast("Please accept the terms to continue.", "error");
      termsCheck.closest(".terms-wrap")?.classList.add("shake");
      setTimeout(
        () => termsCheck.closest(".terms-wrap")?.classList.remove("shake"),
        500,
      );
      return;
    }

    // Activate loading state
    submitBtn.disabled = true;
    const spinner = submitBtn.querySelector(".btn-spinner");
    const btnText = submitBtn.querySelector(".btn-text");
    if (spinner) spinner.style.display = "block";
    if (btnText) btnText.style.opacity = ".6";
  });
});

/* ═══════════════════════════════════════════════
   Shake form on page load if errors present
   ═══════════════════════════════════════════════ */

const alertError = document.querySelector(".alert-error");
if (alertError) {
  const wrap = document.querySelector(".auth-form-wrap");
  if (wrap) {
    wrap.classList.add("shake");
    wrap.addEventListener(
      "animationend",
      () => wrap.classList.remove("shake"),
      { once: true },
    );
  }
}

/* ═══════════════════════════════════════════════
   Toast notification
   ═══════════════════════════════════════════════ */

/**
 * @param {string} message
 * @param {'success'|'error'|'info'} type
 */
function showToast(message, type = "info") {
  let container = document.getElementById("toast-container");
  if (!container) {
    container = document.createElement("div");
    container.id = "toast-container";
    Object.assign(container.style, {
      position: "fixed",
      bottom: "24px",
      right: "24px",
      zIndex: "9999",
      display: "flex",
      flexDirection: "column",
      gap: "10px",
    });
    document.body.appendChild(container);
  }

  const colors = {
    error: { bg: "#EDD5C8", color: "#C96442", border: "rgba(201,100,66,.25)" },
    success: {
      bg: "#D1DFCF",
      color: "#5C7A65",
      border: "rgba(92,122,101,.25)",
    },
    info: { bg: "#E8E3D8", color: "#3A3830", border: "rgba(58,56,48,.15)" },
  };

  const c = colors[type] || colors.info;
  const toast = document.createElement("div");
  Object.assign(toast.style, {
    background: c.bg,
    color: c.color,
    border: `1px solid ${c.border}`,
    borderRadius: "10px",
    padding: "12px 18px",
    fontSize: "13px",
    fontFamily: "'DM Sans', sans-serif",
    boxShadow: "0 4px 20px rgba(30,32,39,.1)",
    opacity: "0",
    transform: "translateY(10px)",
    transition: "opacity .25s ease, transform .25s ease",
    maxWidth: "320px",
    lineHeight: "1.5",
  });

  toast.textContent = message;
  container.appendChild(toast);

  requestAnimationFrame(() => {
    toast.style.opacity = "1";
    toast.style.transform = "translateY(0)";
  });

  setTimeout(() => {
    toast.style.opacity = "0";
    toast.style.transform = "translateY(10px)";
    setTimeout(() => toast.remove(), 300);
  }, 3500);
}

window.showToast = showToast;
