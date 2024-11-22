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
    $returnDate = isset($booking['return_date']) && $booking['return_date'] ? (new DateTime($booking['return_date']))->format('Y-m-d') : ''; // Nullable handling
    $departure = isset($booking['departure']) ? $booking['departure'] : '';
    $arrival = isset($booking['arrival']) ? $booking['arrival'] : '';
    $seatClass = isset($booking['seatClass']) ? $booking['seatClass'] : '';
    $numPassengers = isset($booking['num_passengers']) ? $booking['num_passengers'] : '';
    $totalPayment = isset($booking['total_payment']) ? $booking['total_payment'] : '';

    // Handle form submission (to update booking)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the form data
        $customerName = $_POST['customer_name'];
        $departureDate = $_POST['departure_date'];
        $returnDate = $_POST['return_date'] ? $_POST['return_date'] : null; // Set to NULL if no arrival date is provided
        $departure = $_POST['departure'];
        $arrival = $_POST['arrival'];
        $seatClass = $_POST['seatClass'];
        $numPassengers = $_POST['num_passengers'];
        $totalPayment = $_POST['total_payment'];

        // Update the booking record in the database
        $updateStmt = $pdo->prepare("UPDATE bookings SET customer_name = :customerName, departure_date = :departureDate, return_date = :returnDate, departure = :departure, arrival = :arrival, seatClass = :seatClass, num_passengers = :numPassengers, total_payment = :totalPayment WHERE id = :id");
        $updateStmt->bindParam(':customerName', $customerName);
        $updateStmt->bindParam(':departureDate', $departureDate);
        $updateStmt->bindParam(':returnDate', $arrivalDate, PDO::PARAM_STR); // Use NULL if no date is provided
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
            padding: 0.8rem; /* Reduced padding for smaller button */
            margin-top: 1.5rem;
            background-color: #ff0000;
            color: white;
            font-size: 1rem; /* Smaller font size */
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const seatClassSelect = document.getElementById('seatClass');
            const departureDateInput = document.getElementById('departure-date');
            const arrivalDateInput = document.getElementById('return-date');
            const totalPaymentInput = document.getElementById('total-payment');
            const numPassengersInput = document.getElementById('num-passengers');

            function calculateTotalPayment() {
                // Get the class price based on the selected class type
                let classPrice = 0;
                switch (seatClassSelect.value) {
                    case 'economy':
                        classPrice = 150;
                        break;
                    case 'business':
                        classPrice = 250;
                        break;
                    case 'first':
                        classPrice = 1000;
                        break;
                }

                // Calculate the total payment (based only on class price and number of passengers)
				const numPassengers = parseInt(numPassengersInput.value || 0);
                const totalPayment = numPassengersInput.value * classPrice;
                totalPaymentInput.value = totalPayment.toFixed(2); // Update the total payment field
            }

            // Event listeners to trigger the calculation when any relevant field changes
            seatClassSelect.addEventListener('change', calculateTotalPayment);
            numPassengersInput.addEventListener('input', calculateTotalPayment);

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

            <label for="seatClass">seatClass</label>
            <select id="seatClass" name="seatClass" required>
                <option value="economy" <?php echo $seatClass == 'economy' ? 'selected' : ''; ?>>Economy</option>
                <option value="business" <?php echo $seatClass == 'business' ? 'selected' : ''; ?>>Business</option>
                <option value="first" <?php echo $seatClass == 'first' ? 'selected' : ''; ?>>First Class</option>
            </select>

            <label for="num-passengers">Number of Passengers</label>
            <input type="number" id="num-passengers" name="num_passengers" value="<?php echo htmlspecialchars($numPassengers); ?>" min="1" required>

            <label for="total-payment">Total Payment ($)</label>
            <input type="text" id="total-payment" name="total_payment" value="<?php echo htmlspecialchars($totalPayment); ?>" readonly>

            <button type="submit">Update Booking</button>
        </form>
        <a href="admin_dashboard.php" class="btn btn-danger">Back to Dashboard</a>
    </div>
</body>
</html>
