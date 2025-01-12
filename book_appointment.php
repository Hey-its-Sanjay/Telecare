<?php
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

// Fetch doctor ID from URL
$doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;

// Fetch doctor details securely
$stmt = $conn->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$doctor_result = $stmt->get_result();
$doctor = $doctor_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }
        input, button {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .doctor-info {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Appointment with <?php echo htmlspecialchars($doctor['name']); ?></h1>
        <div class="doctor-info">
            <img src="<?php echo htmlspecialchars($doctor['image']); ?>" alt="Doctor Image" style="width: 150px; height: 150px; border-radius: 50%;">
            <p><strong>Specialization:</strong> <?php echo htmlspecialchars($doctor['specialization']); ?></p>
            <p><strong>Experience:</strong> <?php echo htmlspecialchars($doctor['experience']); ?> years</p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($doctor['address']); ?></p>
        </div>
        <form method="POST" action="submit_appointment.php">
            <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctor['doctor_id']); ?>">
            <label for="date">Choose a Date:</label>
            <input type="date" id="date" name="date" required>
            
            <label for="time">Choose a Time:</label>
            <input type="time" id="time" name="time" required>
            
            <button type="submit">Book Appointment</button>
        </form>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
