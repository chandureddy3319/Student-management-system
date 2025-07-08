<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Student Records</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main class="container">
    <h2 style="color:var(--accent);">User Registration</h2>
    <form id="register-form" style="max-width:400px;margin:auto;">
      <label>Email</label>
      <input type="email" name="email" required style="width:100%;padding:0.7em 1em;margin-bottom:1em;border-radius:4px;border:1px solid #ccc;">
      <label>Username</label>
      <input type="text" name="username" required minlength="3" style="width:100%;padding:0.7em 1em;margin-bottom:1em;border-radius:4px;border:1px solid #ccc;">
      <label>Password</label>
      <input type="password" name="password" required minlength="6" style="width:100%;padding:0.7em 1em;margin-bottom:1em;border-radius:4px;border:1px solid #ccc;">
      <button type="submit" class="btn">Register</button>
      <div id="register-msg" style="margin-top:1em;"></div>
    </form>
    <p style="text-align:center;margin-top:1em;">Already have an account? <a href="login.php">Login</a></p>
  </main>
  <footer>
    Developed by [Your Name], [Your University] &copy; <?php echo date('Y'); ?>
  </footer>
  <script>
    document.getElementById('register-form').onsubmit = function(e) {
      e.preventDefault();
      const form = e.target;
      const data = {
        email: form.email.value,
        username: form.username.value,
        password: form.password.value
      };
      const msg = document.getElementById('register-msg');
      msg.innerHTML = '<div class="loader"></div>';
      fetch('php/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          msg.innerHTML = '<span style="color:var(--success);">Registration successful! <a href="login.php">Login now</a></span>';
          form.reset();
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