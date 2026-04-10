<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/form.css" />
  <link rel="icon" type="image/x-icon" href="../images/favicon.jpg">
  <title>Reset Password</title>
</head>

<body>

  <a href="user_signup.php" class="back-btn">← Back</a>

  <div class="container" id="container" style="min-height: 340px; width: 420px;">
    <div class="form-container sign-in-container" style="width: 100%; position: relative;">
      <form method="POST" action="../__back-end_processes/auth_reset_password.php" id="resetForm">
        <h1>Reset Password</h1>
        <p>Enter your new password below.</p>

        <?php if (isset($_GET['error'])): ?>
          <p class="error show"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <!-- Pass token and email through hidden fields -->
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">

        <input type="password" placeholder="New Password" name="password" id="resetPassword" required oninput="validateResetPassword(this.value)">
        <div class="pwd-requirements" id="resetPwdRequirements">
          <span id="reset-req-length" class="req fail">&#10007; 8&ndash;16 characters</span>
          <span id="reset-req-number" class="req fail">&#10007; At least 1 number</span>
        </div>

        <input type="password" placeholder="Confirm Password" name="confirm_password" id="confirmPassword" required>

        <button type="submit">Reset Password</button>
      </form>
    </div>
  </div>

  <script>
    // Inline here since this is a standalone page
    const resetForm = document.getElementById('resetForm');
    const resetPwdInput = document.getElementById('resetPassword');
    const confirmPwdInput = document.getElementById('confirmPassword');
    const reqBox = document.getElementById('resetPwdRequirements');
    const reqLength = document.getElementById('reset-req-length');
    const reqNumber = document.getElementById('reset-req-number');

    function setReq(el, valid, text) {
      el.textContent = (valid ? '✓ ' : '✗ ') + text;
      el.className = 'req ' + (valid ? 'pass' : 'fail');
    }

    function validateResetPassword(value) {
      reqBox.style.display = value.length > 0 ? 'flex' : 'none';
      setReq(reqLength, value.length >= 8 && value.length <= 16, '8–16 characters');
      setReq(reqNumber, /[0-9]/.test(value), 'At least 1 number');
    }

    resetForm.addEventListener('submit', function (e) {
      const pwd = resetPwdInput.value;
      const confirm = confirmPwdInput.value;
      const validLength = pwd.length >= 8 && pwd.length <= 16;
      const hasNumber = /[0-9]/.test(pwd);

      if (!validLength || !hasNumber) {
        e.preventDefault();
        reqBox.style.display = 'flex';
        validateResetPassword(pwd);
        resetPwdInput.focus();
        return;
      }

      if (pwd !== confirm) {
        e.preventDefault();
        alert('Passwords do not match.');
        confirmPwdInput.focus();
      }
    });
  </script>
</body>

</html>