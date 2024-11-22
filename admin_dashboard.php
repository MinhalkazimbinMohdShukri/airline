<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit;
}

require 'db_connection.php'; // Include database connection

// Fetch all bookings
$sql = "SELECT * FROM bookings";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Flight Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #333;
            color: white;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            color: #0056b3;
        }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard - Flight Bookings</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Departure</th>
                    <th>Arrival</th>
                    <th>Departure Date</th>
                    <th>Return Date</th>
                    <th>Class</th>
                    <th>Total Payment</th>
                    <th>Passengers</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td><?= htmlspecialchars($row['departure']) ?></td>
                            <td><?= htmlspecialchars($row['arrival']) ?></td>
                            <td><?= htmlspecialchars($row['departure_date']) ?></td>
                            <td><?= htmlspecialchars($row['return_date'] ?: 'N/A') ?></td>
                            <td><?= htmlspecialchars($row['seat_class']) ?></td>
                            <td>RM<?= htmlspecialchars($row['total_payment']) ?></td>
                            <td><?= htmlspecialchars($row['num_passengers']) ?></td>
                            <td>
                                <a href="edit.php?id=<?= urlencode($row['id']) ?>" class="btn">Edit</a>
                                <a href="delete.php?id=<?= urlencode($row['id']) ?>" class="btn btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">No records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
