<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="navbar">
    <div class="logo">
        <a href="index.php">VirtualKitchen</a>
    </div>
    <div class="nav-links">
        <?php if (isset($_SESSION['uid'])): ?>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="dashboard.php">Dashboard</a>
            <a href="add_recipe.php">Add Recipe</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</div>