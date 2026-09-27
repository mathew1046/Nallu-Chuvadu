<?php require_once 'config.php'; if (logged_in()) redirect('dashboard.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
  $stmt->execute([strtolower(trim($_POST['email'] ?? ''))]);
  $user = $stmt->fetch();
  if ($user && password_verify($_POST['password'] ?? '', $user['password_hash'])) { unset($user['password_hash']); $_SESSION['user'] = $user; redirect('dashboard.php'); }
  flash('error', 'That email and password do not match.'); redirect('login.php');
}
require 'header.php'; ?>
<section class="auth"><div class="auth-note"><p class="eyebrow">Welcome back</p><h1>Continue the<br>good you started.</h1><p>Today’s small action is waiting for you. Sign in and keep your ripple growing.</p><div class="quote">One kind choice can change the shape of a day.</div></div><form class="auth-card" method="post"><p class="eyebrow">Member login</p><h2>Welcome back</h2><label>Email address<input required type="email" name="email" placeholder="you@example.com"></label><label>Password<input required type="password" name="password" placeholder="Your password"></label><button class="button" type="submit">Log in →</button><p class="form-note">New here? <a href="register.php">Create an account</a></p></form></section>
<?php require 'footer.php'; ?>
