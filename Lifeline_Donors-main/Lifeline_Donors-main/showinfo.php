<?php
require_once "db.php"; // Ensure the database connection is correctly included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from POST request
    $name = htmlspecialchars($_POST["uname"]);
    $age = intval($_POST["age"]);
    $mobile = htmlspecialchars($_POST["mno"]);
    $hospital = htmlspecialchars($_POST["hospital"]);
    $datetime = htmlspecialchars($_POST["datetime"]);

    // Ensure all fields are filled
    if (empty($name) || empty($age) || empty($mobile) || empty($hospital) || empty($datetime)) {
        echo "<h2>Missing required information. Please go back and try again.</h2>";
        exit;
    }

    // Check if the database connection is valid
    if ($conn === false) {
        echo "<h2>Database connection failed.</h2>";
        exit;
    }

    // Prepare and bind the insert statement
    $stmt = $conn->prepare("INSERT INTO donors (name, age, mobile, hospital, datetime) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $name, $age, $mobile, $hospital, $datetime);

    // Execute the insertion
    if ($stmt->execute()) {
        // If successful, display the confirmation page
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Donation Confirmation</title>
            <link href='https://fonts.googleapis.com/css2?family=Satisfy&display=swap' rel='stylesheet'>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: url('background.png') no-repeat center center fixed;
                    background-size: cover;
                    margin: 0;
                    padding: 0;
                    text-align: center;
                    color: #333;
                }
                nav {
                    background-color: black;
                    padding: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }
                .logo {
                    font-size: 50px;
                    font-weight: bold;
                    margin-left: 20px;
                    font-family: 'Satisfy', cursive;
                    background: linear-gradient(to right, rgb(245, 2, 2), rgb(245, 2, 2));
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                }
                .container {
                    background-color: rgba(255, 255, 255, 0.3);
                    backdrop-filter: blur(10px);
                    margin: 50px auto;
                    padding: 30px;
                    max-width: 600px;
                    border-radius: 15px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
                }
                .checkmark-container {
                    width: 100px;
                    height: 100px;
                    margin: 0 auto 20px;
                }
                .checkmark-circle {
                    stroke-dasharray: 166;
                    stroke-dashoffset: 166;
                    stroke-width: 2;
                    stroke: #4CAF50;
                    fill: none;
                    animation: stroke 0.6s ease-in-out forwards;
                }
                .checkmark-check {
                    stroke-dasharray: 48;
                    stroke-dashoffset: 48;
                    stroke-width: 2;
                    stroke: #4CAF50;
                    fill: none;
                    animation: stroke 0.4s 0.6s ease-in-out forwards;
                }
                @keyframes stroke {
                    100% {
                        stroke-dashoffset: 0;
                    }
                }
                h2 {
                    color: green;
                }
                p {
                    font-size: 18px;
                    margin: 10px 0;
                }
            </style>
        </head>
        <body>
            <nav>
                <div class='logo'>Lifeline Donor's</div>
            </nav>

            <div class='container'>
                <div class='checkmark-container'>
                    <svg viewBox='0 0 52 52'>
                        <circle class='checkmark-circle' cx='26' cy='26' r='25'/>
                        <path class='checkmark-check' fill='none' d='M14 27l7 7 16-16'/>
                    </svg>
                </div>
                <h2>Thank You, $name!</h2>
                <p><strong>Age:</strong> $age</p>
                <p><strong>Mobile Number:</strong> $mobile</p>
                <p><strong>Hospital:</strong> $hospital</p>
                <p><strong>Date & Time:</strong> $datetime</p>
                <p>Your willingness to donate blood helps save lives. ❤️</p>
            </div>
        </body>
        </html>
        ";
    } else {
        echo "<h2>Error inserting data: " . $conn->error . "</h2>";
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect if the form is not submitted via POST method
    header("Location: wanttodonate.php");
    exit;
}
?>
