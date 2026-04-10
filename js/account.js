const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
    container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
});

// ── Password Validation ──────────────────────────────────────

const signupForm = document.getElementById('signupForm');
const pwdInput = document.getElementById('signupPassword');
const reqBox = document.getElementById('pwdRequirements');
const reqLength = document.getElementById('req-length');
const reqNumber = document.getElementById('req-number');

function setReq(el, valid, text) {
  el.textContent = (valid ? '✓ ' : '✗ ') + text;
  el.className = 'req ' + (valid ? 'pass' : 'fail');
}

function validatePassword(value) {
  reqBox.style.display = value.length > 0 ? 'flex' : 'none';
  setReq(reqLength, value.length >= 8 && value.length <= 16, '8–16 characters');
  setReq(reqNumber, /[0-9]/.test(value), 'At least 1 number');
}

// Live feedback as user types
pwdInput.addEventListener('input', () => validatePassword(pwdInput.value));

// Block submit if password is invalid
signupForm.addEventListener('submit', function (e) {
  const val = pwdInput.value;
  const validLength = val.length >= 8 && val.length <= 16;
  const hasNumber = /[0-9]/.test(val);

  if (!validLength || !hasNumber) {
    e.preventDefault();         // stop form from submitting
    e.stopImmediatePropagation();
    reqBox.style.display = 'flex';
    validatePassword(val);      // make sure errors are visible
    pwdInput.focus();
  }
});

         