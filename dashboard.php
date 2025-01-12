<?php
// Start session and include database connection
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "telemedicine";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user_id is set in the session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // Defaulting to 1 for now

// Fetch upcoming appointments for logged-in user
$sql = "SELECT a.appointment_date, a.appointment_time, d.name AS doctor_name, d.address 
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.doctor_id
        WHERE a.user_id = ? AND a.appointment_date >= CURDATE()
        ORDER BY a.appointment_date, a.appointment_time";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeleMedicine</title>
    <!-- Link to the external css file-->
    <link rel="stylesheet" href="style.css">
    <style>
        .notifications {
            position: relative;
            display: inline-block;
        }

        #notificationDropdown {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 250px;
            border: 1px solid #ccc;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            padding: 10px;
        }

        #notificationDropdown ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #notificationDropdown li {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        #notificationDropdown li:last-child {
            border-bottom: none;
        }

        #notificationBtn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        #notificationBtn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- header -->
    <div class="header">
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <img src="logo.png" width="160px">
                </div>
                <nav>
                    <ul id="MenuItems">
                        <li><a href="home.php" class="active">Home</a></li>
                        <li><a href="services.php" class="active">Services</a></li>
                        <li><a href="help.php" class="active">Help</a></li>
                        <li><a href="advisor.php" class="active">Advisor</a></li>
                        <li><a href="symptoms.php" class="active">Symptoms</a></li>
                        <li><a href="follow_up.php" class="active">Follow-Up</a></li>
                        <li><a href="aboutus.php" class="active">About Us</a></li>
                    </ul>
                </nav>
                <div class="right">
                    <a href="login.php"> <button class="btn" id="loginBtn">Log Out</button></a>
                </div>
            </div>

            <!-- Notifications Button -->
            <div class="notifications">
                <button id="notificationBtn">Appointments</button>
                <div id="notificationDropdown">
                    <?php if ($result->num_rows > 0): ?>
                        <ul>
                        <?php
// Assuming you're fetching the appointments and storing in $result
while ($row = $result->fetch_assoc()): 
    $appointment_date = new DateTime($row['appointment_date']);
?>
    <li>
        <strong><?php echo htmlspecialchars($row['doctor_name']); ?></strong><br>
        Date: <?php echo $appointment_date->format('Y-m-d'); ?><br>  <!-- Displaying only the date -->
        Time: <?php echo htmlspecialchars($row['appointment_time']); ?><br>
        Address: <?php echo htmlspecialchars($row['address']); ?>
    </li>
<?php endwhile; ?>

                        </ul>
                    <?php else: ?>
                        <p>No upcoming appointments.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <h1>TeleCare</h1>
                    <p>Connecting Patients with Care, ANYTIME, ANYWHERE</p>
                </div>
                <div class="col-2">
                    <img src="home.png">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('notificationBtn').addEventListener('click', function() {
            var dropdown = document.getElementById('notificationDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        });
    </script>

</body>
</html>
