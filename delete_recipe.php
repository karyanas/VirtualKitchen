<?php
include 'inc/db.php';
session_start();

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['rid'])) {
    $rid = (int) $_GET['rid'];
    $uid = $_SESSION['uid'];

    $sql = "DELETE FROM recipes WHERE rid = ? AND uid = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $rid, $uid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("Location: dashboard.php");
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>