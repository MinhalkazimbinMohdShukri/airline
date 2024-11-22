<?php
session_start();
include 'db_connection.php'; // Include the database connection file

// Capture form data
$customerName = $_POST['customerName'] ?? '';
$departure = $_POST['departure'] ?? '';
$arrival = $_POST['arrival'] ?? '';
$departureDate = $_POST['departureDate'] ?? '';
$returnDate = $_POST['returnDate'] ?? null;
$numPassengers = (int)($_POST['numPassengers'] ?? 0);
$seatClass = $_POST['seatClass'] ?? '';
$totalPayment = (float)($_POST['totalPayment'] ?? 0);

// Debugging: Output the form data
echo "<pre>";
var_dump($_POST);
echo "</pre>";

// Validate inputs
if (empty($customerName) || empty($departure) || empty($arrival) || empty($departureDate) || $numPassengers <= 0 || empty($seatClass)) {
    die("All required fields must be filled out.");
}

// Check seat availability
$query = "
    SELECT COUNT(*) as booked
    FROM bookings
    WHERE departure = ? AND arrival = ? AND departure_date = ? AND seat_class = ?
";
$stmt = mysqli_prepare($conn, $query);

// Check if the query was prepared successfully
if (!$stmt) {
    die("Failed to prepare the query: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, 'ssss', $departure, $arrival, $departureDate, $seatClass);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

// Assume a maximum capacity of 100 seats per class per flight
$maxSeats = 100;
if ($row['booked'] + $numPassengers > $maxSeats) {
    die("Not enough seats available for this flight and class.");
}

// Insert booking into the database
$query = "
    INSERT INTO bookings (customer_name, departure, arrival, departure_date, return_date, num_passengers, seat_class, total_payment)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
";
$stmt = mysqli_prepare($conn, $query);

// Check if the query was prepared successfully
if (!$stmt) {
    die("Failed to prepare the insert query: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, 'sssssisd', $customerName, $departure, $arrival, $departureDate, $returnDate, $numPassengers, $seatClass, $totalPayment);

// Execute the query
if (mysqli_stmt_execute($stmt)) {
    // Store booking information in the session
    $_SESSION['booking_info'] = [
        'customerName' => $customerName,
        'departure' => $departure,
        'arrival' => $arrival,
        'departureDate' => $departureDate,
        'returnDate' => $returnDate,
        'numPassengers' => $numPassengers,
        'seatClass' => $seatClass,
        'totalPayment' => $totalPayment
    ];

    // Redirect to confirmation page
    header("Location: confirmation.php");
    exit();
} else {
    die("Error executing query: " . mysqli_stmt_error($stmt));
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
