<?php
require 'db_connection.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the ID

    // Prepare the SQL statement
    $sql = "DELETE FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error); // Debugging: Check for preparation errors
    }

    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?message=Record deleted successfully");
        exit;
    } else {
        die("Error executing statement: " . $stmt->error); // Debugging: Check for execution errors
    }
} else {
    die("Error: Invalid or missing 'id' parameter in URL."); // Debugging: Missing or invalid ID
}
?>