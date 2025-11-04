<?php
include 'inc/db.php';
session_start();

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['rid'])) {
    header("Location: index.php");
    exit();
}

$rid = (int) $_GET['rid'];
$uid = $_SESSION['uid'];

$sql = "SELECT * FROM recipes WHERE rid = ? AND uid = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $rid, $uid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) != 1) {
    die("You are not authorized to edit this recipe or recipe does not exist.");
}

$recipe = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $type = htmlspecialchars(trim($_POST['type']));
    $description = htmlspecialchars(trim($_POST['description']));
    $cookingtime = (int) $_POST['cookingtime'];
    $ingredients = htmlspecialchars(trim($_POST['ingredients']));
    $instructions = htmlspecialchars(trim($_POST['instructions']));

    $update_sql = "UPDATE recipes 
                   SET name = ?, type = ?, description = ?, cookingtime = ?, ingredients = ?, instructions = ?
                   WHERE rid = ? AND uid = ?";
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, "sssissii", $name, $type, $description, $cookingtime, $ingredients, $instructions, $rid, $uid);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: recipe_detail.php?rid=$rid");
        exit();
    } else {
        $error = "Error updating recipe: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Recipe - Virtual Kitchen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <?php include 'inc/nav.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">Update Recipe</h2>

        <?php if (isset($error))
            echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="post" action="update_recipe.php?rid=<?php echo $rid; ?>" class="p-4 border rounded bg-light">
            <input type="text" name="name" class="form-control mb-3"
                value="<?php echo htmlspecialchars($recipe['name']); ?>" required>

            <select name="type" class="form-select mb-3" required>
                <option value="">Select Type</option>
                <?php
                $types = ["French", "Italian", "Chinese", "Indian", "Mexican", "Others"];
                foreach ($types as $t) {
                    $selected = ($recipe['type'] === $t) ? "selected" : "";
                    echo "<option value=\"$t\" $selected>$t</option>";
                }
                ?>
            </select>

            <textarea name="description" class="form-control mb-3" rows="3"
                required><?php echo htmlspecialchars($recipe['description']); ?></textarea>
            <input type="number" name="cookingtime" class="form-control mb-3"
                value="<?php echo (int) $recipe['Cookingtime']; ?>" required>
            <textarea name="ingredients" class="form-control mb-3" rows="4"
                required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>
            <textarea name="instructions" class="form-control mb-3" rows="5"
                required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>

            <button type="submit" class="btn btn-warning w-100">Update Recipe</button>
        </form>

        <p class="mt-3"><a href="index.php" class="btn btn-secondary">⬅ Back to Home</a></p>
    </div>
</body>

</html>