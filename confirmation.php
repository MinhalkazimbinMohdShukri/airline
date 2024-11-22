<?php
session_start();

// Retrieve booking information from the session
$bookingInfo = $_SESSION['booking_info'] ?? null;

if (!$bookingInfo) {
    echo "No booking information available.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            text-align: left;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #ff0000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        td, th {
            padding: 10px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Booking Confirmation</h1>
        <table>
            <tr><th>Customer Name</th><td><?= htmlspecialchars($bookingInfo['customerName'] ?? 'N/A') ?></td></tr>
            <tr><th>Departure</th><td><?= htmlspecialchars($bookingInfo['departure'] ?? 'N/A') ?></td></tr>
            <tr><th>Arrival</th><td><?= htmlspecialchars($bookingInfo['arrival'] ?? 'N/A') ?></td></tr>
            <tr><th>Departure Date</th><td><?= htmlspecialchars($bookingInfo['departureDate'] ?? 'N/A') ?></td></tr>
            <tr><th>Return Date</th><td><?= htmlspecialchars($bookingInfo['returnDate'] ?? 'N/A') ?></td></tr>
            <tr><th>Number of Passengers</th><td><?= htmlspecialchars($bookingInfo['numPassengers'] ?? 'N/A') ?></td></tr>
            <tr><th>Seat Class</th><td><?= htmlspecialchars($bookingInfo['seatClass'] ?? 'N/A') ?></td></tr>
            <tr><th>Total Payment</th><td>RM<?= htmlspecialchars($bookingInfo['totalPayment'] ?? '0.00') ?></td></tr>
        </table>
        <p>Thank you for choosing ZymAirlines!</p>
        <div style="text-align: center; margin-top: 20px;">
            <a href="airline.html">
                <button style="padding: 10px 20px; font-size: 16px; background-color: #ff0000; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    Back to Homepage
                </button>
            </a>
			
        </div>
    </div>
</body>
</html>
