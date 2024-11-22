<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the admin credentials
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'ZYM123'); // Update to your desired credentials

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate credentials
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['is_admin'] = true; // Set session variable
        header("Location: admin.php"); // Redirect to admin page
        exit;
    } else {
        $error = "Invalid username or password."; // Show error if credentials are wrong
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Zym Airline Coperate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            text-align: center;
            width: 400px;
        }

        h1 {
            color: #ff0000;
            margin-bottom: 1rem;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            margin: 0.5rem 0;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .button {
            background-color: #ff0000;
            color: white;
            border: none;
            padding: 0.8rem;
            margin-top: 1rem;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #000000;
        }

        .error {
            color: red;
            margin-bottom: 1rem;
        }

        .home-button {
            background-color: #ff0000;
            color: white;
            border: none;
            padding: 0.8rem;
            margin-top: 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .home-button:hover {
            background-color: #000000;
        }
    </style>
</head>
<body>
<div class="login-container">
        <h1>Admin Login</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="button">Login</button>
        </form>
		
    </div>
</body>
</html>