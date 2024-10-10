const authForm = document.getElementById("authForm");
const passwordInput = document.getElementById("passwordInput");
const confirmPasswordInput = document.getElementById("confirmPasswordInput");
const passwordMismatch = document.getElementById("passwordMismatch");
const togglePassword = document.getElementById("togglePassword");
const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

// Add an event listener to validate passwords on form submit
authForm.addEventListener("submit", function (event) {
  // Prevent form submission if passwords don't match
  if (passwordInput.value !== confirmPasswordInput.value) {
    event.preventDefault(); // Stop form from submitting
    alert("Passwords must match before signing up!");
    passwordMismatch.style.display = "block"; // Show mismatch message
  } else {
    passwordMismatch.style.display = "none"; // Hide mismatch message
  }
});

// Real-time check when typing in the confirm password field
confirmPasswordInput.addEventListener("input", function () {
  if (passwordInput.value !== confirmPasswordInput.value) {
    passwordMismatch.style.display = "block"; // Show mismatch message
  } else {
    passwordMismatch.style.display = "none"; // Hide mismatch message
  }
});

// Toggle password visibility
togglePassword.addEventListener("click", function () {
  console.log("see button");
  const type = passwordInput.type === "password" ? "text" : "password";
  passwordInput.type = type;
  this.textContent = type === "password" ? "See" : "Hide"; // Change button text
});

// Toggle confirm password visibility
toggleConfirmPassword.addEventListener("click", function () {
  const type = confirmPasswordInput.type === "password" ? "text" : "password";
  confirmPasswordInput.type = type;
  this.textContent = type === "password" ? "See" : "Hide"; // Change button text
});
