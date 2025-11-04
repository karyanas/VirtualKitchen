<?php
include 'inc/db.php';
session_start();

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['uid'];

$sql = "SELECT * FROM recipes WHERE uid = ? ORDER BY rid DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $uid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Dashboard - Virtual Kitchen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <?php include 'inc/nav.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">My Dashboard</h1>

        <p>
            <a class="btn btn-secondary me-2" href="index.php">⬅ Back to Home</a>
            <a class="btn btn-success" href="add_recipe.php">➕ Add New Recipe</a>
        </p>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($recipe = mysqli_fetch_assoc($result)): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlspecialchars($recipe['name']); ?></h4>
                        <h6 class="card-subtitle text-muted mb-2">Type: <?php echo htmlspecialchars($recipe['type']); ?></h6>
                        <p class="card-text"><?php echo htmlspecialchars($recipe['description']); ?></p>
                        <a class="btn btn-outline-primary btn-sm" href="recipe_detail.php?rid=<?php echo $recipe['rid']; ?>">👀
                            View</a>
                        <a class="btn btn-outline-warning btn-sm" href="update_recipe.php?rid=<?php echo $recipe['rid']; ?>">✏️
                            Edit</a>
                        <a class="btn btn-outline-danger btn-sm" href="delete_recipe.php?rid=<?php echo $recipe['rid']; ?>"
                            onclick="return confirm('Are you sure you want to delete this recipe?');">❌ Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">You haven't added any recipes yet.</div>
        <?php endif; ?>
    </div>
</body>

</html>