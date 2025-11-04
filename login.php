<?php
include 'inc/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    $sql = "SELECT uid, username, password FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            if (password_verify($password, $row['password'])) {
                session_regenerate_id(true);
                $_SESSION['uid'] = $row['uid'];
                $_SESSION['username'] = $row['username'];
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $error = "Database error. Try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Virtual Kitchen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <?php include 'inc/nav.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Login</h2>

        <?php
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
        if (isset($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
        ?>

        <form method="post" action="login.php" class="p-4 border rounded bg-light mx-auto" style="max-width: 500px;">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>

</body>

</html>