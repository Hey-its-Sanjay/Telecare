<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "telemedicine";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in user ID (assuming it is stored in session)
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

// Validate and get form data
$doctor_id = isset($_POST['doctor_id']) ? intval($_POST['doctor_id']) : 0;
$date = isset($_POST['date']) ? $_POST['date'] : '';
$time = isset($_POST['time']) ? $_POST['time'] : '';

$current_date = date('Y-m-d');
// Check if the selected date is in the past
if ($date < $current_date) {
    echo "You cannot book an appointment for a past date.";
    exit();  // Stop further execution
}

// Insert appointment into the database securely
$stmt = $conn->prepare("INSERT INTO appointments (doctor_id, user_id, appointment_date, appointment_time) 
                        VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $doctor_id, $user_id, $date, $time);

if ($stmt->execute()) {
    // Redirect to dashboard with success notification
    header("Location: dashboard.php?success=Appointment booked successfully!");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<form id="appointmentForm" method="POST" action="submit_appointment.php">
    <input type="hidden" name="doctor_id" value="<?php echo $doctor['doctor_id']; ?>">

    <label for="date">Choose a Date:</label>
    <input type="date" id="date" name="date" required>

    <label for="time">Choose a Time:</label>
    <input type="time" id="time" name="time" required>

    <button type="submit">Book Appointment</button>
</form>

<script>
    document.getElementById("appointmentForm").addEventListener("submit", function(event) {
        var selectedDate = document.getElementById("date").value;
        var currentDate = new Date().toISOString().split("T")[0]; // Get current date in 'YYYY-MM-DD' format

        if (selectedDate < currentDate) {
            alert("You cannot book an appointment for a past date.");
            event.preventDefault();  // Prevent form submission
        }
    });
</script>

