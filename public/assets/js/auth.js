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
