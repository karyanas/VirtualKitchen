<?php
include 'inc/db.php';
session_start();

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $type = htmlspecialchars(trim($_POST['type']));
    $description = htmlspecialchars(trim($_POST['description']));
    $cookingtime = (int) $_POST['cookingtime'];
    $ingredients = htmlspecialchars(trim($_POST['ingredients']));
    $instructions = htmlspecialchars(trim($_POST['instructions']));
    $uid = $_SESSION['uid'];

    $sql = "INSERT INTO recipes (name, description, type, cookingtime, ingredients, instructions, uid)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "sssissi",
            $name,
            $description,
            $type,
            $cookingtime,
            $ingredients,
            $instructions,
            $uid
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        $error = "Database error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Recipe - Virtual Kitchen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <?php include 'inc/nav.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">Add a New Recipe</h2>

        <?php if (isset($error))
            echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="post" action="add_recipe.php" class="p-4 border rounded bg-light">
            <input type="text" name="name" class="form-control mb-3" placeholder="Recipe Name" required>

            <select name="type" class="form-select mb-3" required>
                <option value="">Select Type</option>
                <option value="French">French</option>
                <option value="Italian">Italian</option>
                <option value="Chinese">Chinese</option>
                <option value="Indian">Indian</option>
                <option value="Mexican">Mexican</option>
                <option value="Others">Others</option>
            </select>

            <textarea name="description" class="form-control mb-3" placeholder="Short Description" rows="3"
                required></textarea>
            <input type="number" name="cookingtime" class="form-control mb-3" min="1"
                placeholder="Cooking Time (minutes)" required>
            <textarea name="ingredients" class="form-control mb-3" placeholder="Ingredients (separated by commas)"
                rows="4" required></textarea>
            <textarea name="instructions" class="form-control mb-3" placeholder="Cooking Instructions" rows="5"
                required></textarea>

            <button type="submit" class="btn btn-success w-100">Add Recipe</button>
        </form>

        <p class="mt-3"><a href="index.php">⬅ Back to Home</a></p>
    </div>
</body>

</html>