<?php
include('inc.header.php');
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = safe_input($_GET['id']);
    // Check if user exists
    $sql = "SELECT * FROM `users` WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) > 0) {
        // User exists, proceed to delete
        $sql = "DELETE FROM `users` WHERE id = '$id'";
        if(mysqli_query($conn, $sql)) {
            $_SESSION['success'] = "User deleted successfully.";
            header("Location: users.php");
            exit();            
        } else {
            $_SESSION['error'] = "Error deleting user: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "User not found.";
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}
?>
