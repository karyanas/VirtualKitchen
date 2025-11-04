<?php
session_start();
include 'inc/db.php';

$recipes = [];
$q = "";

if (isset($_GET['q']) && $_GET['q'] !== "") {
    $q = $_GET['q'];
    $sql = "SELECT rid, name, type, description FROM recipes WHERE name LIKE ? OR type LIKE ? ORDER BY rid DESC";
    $stmt = mysqli_prepare($conn, $sql);
    $search = "%" . $q . "%";
    mysqli_stmt_bind_param($stmt, "ss", $search, $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $sql = "SELECT rid, name, type, description FROM recipes ORDER BY rid DESC";
    $result = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Virtual Kitchen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include 'inc/nav.php'; ?>

    <section class="hero-section text-center py-5 mb-5 bg-light">
        <div class="container">
            <?php if (isset($_SESSION['uid'])): ?>
                <h1 class="display-4 mb-3">Welcome back, <?php
                $username = htmlspecialchars($_SESSION['username']);
                echo strlen($username) > 15 ? substr($username, 0, 12) . '...' : $username;
                ?>!</h1>
                <p class="lead mb-4">Ready to cook up something new?<br>Share your latest creation or explore fresh recipes.
                </p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="add_recipe.php" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-plus-circle me-2"></i>New Recipe
                    </a>
                    <a href="dashboard.php" class="btn btn-outline-secondary btn-lg px-5">
                        <i class="fas fa-user-edit me-2"></i>Your Recipes
                    </a>
                </div>
            <?php else: ?>
                <h1 class="display-4 mb-3">Welcome to Virtual Kitchen</h1>
                <p class="lead mb-4">Discover, share, and create delicious recipes from around the world.<br>Join our
                    community of food enthusiasts today!</p>
                <a href="register.php" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-sign-in-alt me-2"></i>Get Started
                </a>
            <?php endif; ?>
        </div>
    </section>

    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5><i class="fas fa-utensils me-2"></i>Explore Recipes</h5>
                        <p class="text-muted">Browse thousands of recipes from home cooks worldwide</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5><i class="fas fa-share-alt me-2"></i>Share Creations</h5>
                        <p class="text-muted">Contribute your own recipes to our growing community</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5><i class="fas fa-comments me-2"></i>Get Inspired</h5>
                        <p class="text-muted">Learn new techniques and discover trending dishes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">

        <form method="get" action="index.php" class="p-4 border rounded bg-light mb-4">
            <div class="d-flex gap-2">
                <input type="text" name="q" class="form-control flex-grow-1" placeholder="Search by name or type..."
                    value="<?php echo htmlspecialchars($q); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <h2 class="mb-3">
            <?php echo $q !== "" ? "Results for '" . htmlspecialchars($q) . "'" : "All Recipes"; ?>
        </h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="recipe_detail.php?rid=<?php echo $row['rid']; ?>">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </a>
                        </h4>
                        <h6 class="card-subtitle text-muted mb-2">Type: <?php echo htmlspecialchars($row['type']); ?></h6>
                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">No recipes found.</div>
        <?php endif; ?>
    </div>

    <?php include 'inc/footer.php'; ?>
</body>

</html>