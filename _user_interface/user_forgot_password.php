<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/form.css" />
  <link rel="icon" type="image/x-icon" href="../images/favicon.jpg">
  <title>Forgot Password</title>
</head>

<body>

  <a href="user_signup.php" class="back-btn">← Back</a>

  <div class="container" id="container" style="min-height: 300px; width: 420px;">
    <div class="form-container sign-in-container" style="width: 100%; position: relative;">
      <form method="POST" action="../__back-end_processes/forgot_password_send_mail.php">
        <h1>Forgot Password</h1>
        <p>Enter your email and we'll send you a reset link.</p>

        <?php if (isset($_GET['error'])): ?>
          <p class="error show"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
          <p style="color: #69ff96; font-size: 14px; margin: 0 0 10px 0;">
            <?php echo htmlspecialchars($_GET['success']); ?>
          </p>
        <?php endif; ?>

        <input type="email" placeholder="Email Address" name="email" required>
        <button type="submit">Send Reset Link</button>
      </form>
    </div>
  </div>

  <script src="../js/account.js"></script>
</body>

</html>