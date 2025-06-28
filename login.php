<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    if ($username === "guest" && $password === "guest123") {
        $_SESSION["loggedin"] = true;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            width: 300px;
            margin: 80px auto;
            padding: 30px;
            background: #f5f5f5;
            border-radius: 10px;
            box-shadow: 2px 2px 8px rgba(0,0,0,0.2);
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            width: 90%;
            padding: 8px;
            margin: 6px 0;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <p style="color:red;"><?php echo $error; ?></p>
</div>

</body>
</html>
