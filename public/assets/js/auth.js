//   Password Visibility Toggle

const passwordButtons = document.querySelectorAll(".toggle-password");

passwordButtons.forEach(function (button) {
  button.addEventListener("click", function () {
    const targetId = button.dataset.target;
    const inputField = document.getElementById(targetId);

    if (!inputField) {
      return;
    }

    let currentType = inputField.type;

    if (currentType === "password") {
      inputField.type = "text";
    } else {
      inputField.type = "password";
    }

    const hideIcon = button.querySelector(".icon-hide");
    const showIcon = button.querySelector(".icon-show");

    if (currentType === "password") {
      if (hideIcon) hideIcon.style.display = "none";
      if (showIcon) showIcon.style.display = "block";
      button.setAttribute("aria-label", "Hide password");
    } else {
      if (hideIcon) hideIcon.style.display = "block";
      if (showIcon) showIcon.style.display = "none";
      button.setAttribute("aria-label", "Show password");
    }
  });
});

//   Password Strength Checker

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
