<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Student Records</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main class="container">
    <h2 style="color:var(--accent);">User Login</h2>
    <form id="login-form" style="max-width:400px;margin:auto;">
      <label>Email or Username</label>
      <input type="text" name="user" required style="width:100%;padding:0.7em 1em;margin-bottom:1em;border-radius:4px;border:1px solid #ccc;">
      <label>Password</label>
      <input type="password" name="password" required minlength="6" style="width:100%;padding:0.7em 1em;margin-bottom:1em;border-radius:4px;border:1px solid #ccc;">
      <button type="submit" class="btn">Login</button>
      <div id="login-msg" style="margin-top:1em;"></div>
    </form>
    <p style="text-align:center;margin-top:1em;">Don't have an account? <a href="register.php">Register</a></p>
  </main>
  <footer>
    Developed by [Your Name], [Your University] &copy; <?php echo date('Y'); ?>
  </footer>
  <script>
    document.getElementById('login-form').onsubmit = function(e) {
      e.preventDefault();
      const form = e.target;
      const data = {
        user: form.user.value,
        password: form.password.value
      };
      const msg = document.getElementById('login-msg');
      msg.innerHTML = '<div class="loader"></div>';
      fetch('php/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          msg.innerHTML = '<span style="color:var(--success);">Login successful! Redirecting...</span>';
          setTimeout(() => window.location = 'index.php', 1000);
        } else {
          msg.innerHTML = res.errors.map(e => `<span style='color:var(--danger);'>${e}</span>`).join('<br>');
        }
      })
      .catch(() => {
        msg.innerHTML = '<span style="color:var(--danger);">Server error. Try again.</span>';
      });
    };
  </script>
</body>
</html> 