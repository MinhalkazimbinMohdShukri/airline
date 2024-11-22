<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Zym Airline Coperate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #000000;
        }
        header {
            background-color: #ffffff;
            color: white;
            padding: 1rem 0;
            text-align: center;
        }
        .admin-container {
            max-width: 900px;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            border-radius: 8px;
        }
        h1 {
            color: #ff0000;
        }
        .button {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            margin-top: 2rem;
            color: white;
            background-color: #ff0000;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #000000;
        }
    </style>
</head>
<body>
    <header>
        <h1>Zym Airline Coperate</h1>
    </header>

    <div class="admin-container">
        <h1>Welcome, Admin</h1>
		<p>Thank you for managing the Zym Airline system. You have full access to the admin dashboard.</p>
        <a href="admin_dashboard.php" class="button">Go to Admin Dashboard</a>
        <p style="margin-top: 2rem;">
            <a href="logout.php" class="button" style="background-color: #ff0000;">Logout</a>
        </p>
    </div>
</body>
</html>