<?php
include 'inc/db.php';
session_start();

if (!isset($_GET['rid'])) {
    header("Location: index.php");
    exit();
}

$rid = (int) $_GET['rid'];
$sql = "SELECT recipes.*, users.username 
        FROM recipes 
        JOIN users ON recipes.uid = users.uid 
        WHERE recipes.rid = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $rid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {
    $recipe = mysqli_fetch_assoc($result);
} else {
    $error = "Recipe not found.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Recipe Detail - Virtual Kitchen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <?php include 'inc/nav.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Recipe Details</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title"><?php echo htmlspecialchars($recipe['name']); ?></h2>
                    <h6 class="card-subtitle mb-2 text-muted">By: <?php echo htmlspecialchars($recipe['username']); ?></h6>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($recipe['type']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($recipe['description']); ?></p>
                    <p><strong>Cooking Time:</strong> <?php echo (int) $recipe['Cookingtime']; ?> minutes</p>
                    <p><strong>Ingredients:</strong><br> <?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?></p>
                    <p><strong>Instructions:</strong><br> <?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?>
                    </p>

                    <?php if (isset($_SESSION['uid']) && $_SESSION['uid'] == $recipe['uid']): ?>
                        <a class="btn btn-warning" href="update_recipe.php?rid=<?php echo $recipe['rid']; ?>">✏️ Edit Recipe</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <p class="mt-4"><a href="index.php" class="btn btn-secondary">⬅ Back to Home</a></p>
    </div>
</body>

</html>