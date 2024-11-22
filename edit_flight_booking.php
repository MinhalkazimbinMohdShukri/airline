<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$dbname = 'flight_booking'; // Update to flight database
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the booking ID (id) from the URL
    $booking_id = isset($_GET['id']) ? $_GET['id'] : null;

    // Check if the booking ID is provided
    if (!$booking_id) {
        die("Booking ID is missing. Please return to the <a href='admin_dashboard.php'>admin dashboard</a> and select a booking to edit.");
    }

    // Fetch the booking details from the database
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = :id");
    $stmt->bindParam(':id', $booking_id, PDO::PARAM_INT);
    $stmt->execute();
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no booking found, display an error message
    if (!$booking) {
        die("Booking not found.");
    }

    // Pre-fill form data with existing booking data, with checks for missing values
    $customerName = isset($booking['customer_name']) ? $booking['customer_name'] : '';
    $departureDate = isset($booking['departure_date']) ? (new DateTime($booking['departure_date']))->format('Y-m-d') : '';
    $returnDate = isset($booking['return_date']) && !empty($booking['return_date']) ? (new DateTime($booking['return_date']))->format('Y-m-d') : ''; // Nullable handling
    $departure = isset($booking['departure']) ? $booking['departure'] : '';
    $arrival = isset($booking['arrival']) ? $booking['arrival'] : '';
    $seatClass = isset($booking['seat_class']) ? $booking['seat_class'] : '';
    $numPassengers = isset($booking['num_passengers']) ? $booking['num_passengers'] : '';
    $totalPayment = isset($booking['total_payment']) ? $booking['total_payment'] : '';

    // Handle form submission (to update booking)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the form data
        $customerName = $_POST['customer_name'];
        $departureDate = $_POST['departure_date'];
        $returnDate = isset($_POST['return_date']) ? $_POST['return_date'] : null;// Set to NULL if no return date is provided
        $departure = $_POST['departure'];
        $arrival = $_POST['arrival'];
        $seatClass = $_POST['seat_class'] ?? '';  // Set to an empty string if 'seat_class' is not set
		$numPassengers = $_POST['num_passengers'];
        $totalPayment = $_POST['total_payment'];

        // Update the booking record in the database
        $updateStmt = $pdo->prepare("UPDATE bookings SET customer_name = :customerName, departure_date = :departureDate, return_date = :returnDate, departure = :departure, arrival = :arrival, seat_class = :seatClass, num_passengers = :numPassengers, total_payment = :totalPayment WHERE id = :id");
        $updateStmt->bindParam(':customerName', $customerName);
        $updateStmt->bindParam(':departureDate', $departureDate);
        $updateStmt->bindParam(':returnDate', $returnDate, PDO::PARAM_STR); // Use NULL if no date is provided
        $updateStmt->bindParam(':departure', $departure);
        $updateStmt->bindParam(':arrival', $arrival);
        $updateStmt->bindParam(':seatClass', $seatClass);
        $updateStmt->bindParam(':numPassengers', $numPassengers);
        $updateStmt->bindParam(':totalPayment', $totalPayment);
        $updateStmt->bindParam(':id', $booking_id, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            echo "Booking updated successfully!";
        } else {
            echo "Failed to update booking.";
        }
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edit flight booking record at Zym Airline Corporate.">
    <title>Edit Flight Booking Record</title>
    <style>
        /* Style for the form */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #000000;
        }

        .container {
            max-width: 600px;
            margin: 3rem auto;
            padding: 2rem;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        label {
            font-weight: bold;
            margin-top: 1rem;
            display: block;
        }

        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        textarea {
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 0.8rem;
            margin-top: 1.5rem;
            background-color: #ff0000;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #000000;
            color: white;
            transition: background-color 0.3s ease-in;
        }

        a {
            display: block;
            width: 100%;
            padding: 0.8rem;
            margin-top: 1rem;
            background-color: #ff0000;
            color: white;
            text-align: center;
            font-size: 1.2rem;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
        }

        a:hover {
            background-color: #000000;
            color: white;
            transition: background-color 0.3s ease-in;
        }
    </style>

	echo "
    <script>
        window.onload = function() {
            var modal = document.getElementById('successModal');
            modal.style.display = 'block'; // Show the modal
        }
    </script>
";
?>

<!-- Modal HTML -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('successModal').style.display='none'">&times;</span>
        <h2>Booking Updated Successfully!</h2>
        <p>Your booking details have been successfully updated.</p>
        <button onclick="window.location.href='admin_dashboard.php';">Close</button>
    </div>
</div>

<!-- Modal Styles -->
<style>
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed;
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0,0,0,0.4); /* Black w/opacity */
        padding-top: 60px;
    }

    /* Modal Content */
    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
        text-align: center;
    }

    /* Close Button */
    .close-btn {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        position: absolute;
        top: 10px;
        right: 25px;
        font-family: Arial, sans-serif;
    }

    .close-btn:hover,
    .close-btn:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Button Styling */
    button {
        background-color: #ff0000;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    button:hover {
        background-color: #000000;
    }
</style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const seatClassSelect = document.getElementById('seat_class');
            const departureDateInput = document.getElementById('departure_date');
            const returnDateInput = document.getElementById('return_date');
            const totalPaymentInput = document.getElementById('total_payment');
            const numPassengersInput = document.getElementById('num_passengers');

            function calculateTotalPayment() {
                let classPrice = 0;
                switch (seatClassSelect.value) {
                    case 'Economy':
                        classPrice = 150;
                        break;
                    case 'Business':
                        classPrice = 250;
                        break;
                    case 'First Class':
                        classPrice = 1000;
                        break;
                }

                const totalPayment = numPassengersInput.value * classPrice;
                totalPaymentInput.value = totalPayment.toFixed(2); // Update the total payment field
            }

            // Event listeners to trigger the calculation when any relevant field changes
            seatClassSelect.addEventListener('change', calculateTotalPayment);
            numPassengersInput.addEventListener('change', calculateTotalPayment);

            // Initialize the page with the correct total payment
            calculateTotalPayment();
        });
    </script>

</head>
<body>
    <div class="container">
        <h1>Edit Flight Booking Record</h1>
        <form action="edit_flight_booking.php?id=<?php echo $booking['id']; ?>" method="post">
            <label for="customer-name">Customer Name</label>
            <input type="text" id="customer-name" name="customer_name" value="<?php echo htmlspecialchars($customerName); ?>" required>

            <label for="departure-date">Departure Date</label>
            <input type="date" id="departure-date" name="departure_date" value="<?php echo htmlspecialchars($departureDate); ?>" required>

            <label for="return-date">Return Date (Optional)</label>
            <input type="date" id="return-date" name="return_date" value="<?php echo htmlspecialchars($returnDate); ?>">

            <label for="departure">Departure</label>
            <input type="text" id="departure" name="departure" value="<?php echo htmlspecialchars($departure); ?>" required>

            <label for="arrival">Arrival</label>
            <input type="text" id="arrival" name="arrival" value="<?php echo htmlspecialchars($arrival); ?>" required>

            <label for="seat_class">Seat Class</label>
            <select id="seat_class" name="seat_class" required>
                <option value="Economy" <?php echo ($seatClass == 'Economy') ? 'selected' : ''; ?>>Economy</option>
                <option value="Business" <?php echo ($seatClass == 'Business') ? 'selected' : ''; ?>>Business</option>
                <option value="First Class" <?php echo ($seatClass == 'First Class') ? 'selected' : ''; ?>>First Class</option>
            </select>

            <label for="num-passengers">Number of Passengers</label>
            <input type="number" id="num-passengers" name="num_passengers" value="<?php echo htmlspecialchars($numPassengers); ?>" required>

            <label for="total-payment">Total Payment</label>
            <input type="text" id="total-payment" name="total_payment" value="<?php echo htmlspecialchars($totalPayment); ?>" readonly>

            <button type="submit">Update Booking</button>
        </form>
    </div>
</body>
</html>
