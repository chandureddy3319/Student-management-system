<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Records Management</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
  <header class="container">
    <h1 style="color:var(--accent);">Student Records Management System</h1>
    <nav>
      <?php if (isset($_SESSION['user_id'])): ?>
        <span>Welcome, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></span>
        <button class="btn" onclick="logoutUser()">Logout</button>
      <?php else: ?>
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn" style="background:var(--secondary);color:#222;">Register</a>
      <?php endif; ?>
    </nav>
  </header>
  <main class="container">
    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
      <input id="search-input" type="text" placeholder="Search students..." style="flex:1;padding:0.7em 1em;border-radius:4px;border:1px solid #ccc;font-size:1rem;">
      <button class="btn" onclick="searchStudents()">Search</button>
    </div>
    <div id="loader" class="loader" style="display:none;"></div>
    <div id="students-list"></div>
  </main>
  <!-- Modals for Edit/Delete (to be implemented) -->
  <div id="modal" class="modal"><div class="modal-content" id="modal-content"></div></div>
  <footer>
    Developed by [Your Name], [Your University] &copy; <?php echo date('Y'); ?>
  </footer>
  <script src="js/main.js"></script>
</body>
</html> 