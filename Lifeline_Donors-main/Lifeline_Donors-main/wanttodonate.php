<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Looking Blood</title>
    <link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
    <style>
        .logo {
            font-size: 50px;
            font-weight: bold;
            margin-left: 20px;
            font-family: 'Satisfy', cursive;
            background: linear-gradient(to right, rgb(245, 2, 2), rgb(245, 2, 2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        nav {
            background-color: black;
            padding: 20px;
            margin: -20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        body {
            font-family: Arial, sans-serif;
            background: url('background.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .main1, .main2{
            background-color: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            width: 90%;
            max-width: 900px;
            margin: 50px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .main3{
            background-color: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            width: 100%;
            max-width: 900px;
            margin: 50px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .q{
            width: 50px;
        }
        .form-control, select {
            font-family: Calibri;
            width: 30%;
            padding: 10px;
            margin: 5px auto;
            border: none;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.402);
        }
        .bt {
            background: linear-gradient(45deg, #800080, #ff4444);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            margin-top: 1rem;
        }
        .bt:hover {
            background: linear-gradient(45deg, #ff4444, #800080);
            transform: scale(1.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Lifeline Donor's</div>
        </nav>
    </header>

    <section class="main1">
        <h2>Want to Donate blood</h2>
        <table>
            <thead>
                <tr>
                    <th>Sr NO.</th>
                    <th>Location</th>
                    <th>Date & Time</th>
                    <th>Donate Blood</th>
                </tr>
            </thead>
            <tbody id="bloodBankTable">
                <tr>
                    <td>1</td>
                    <td>Ahemdabad Civil Hospital</td>
                    <td>23 July,2025 <br>Time : 1:00 pm</td>
                    <td><button class="bt" onclick="showDonationInfo('Ahemdabad Civil Hospital', '23 July, 2025 - 1:00 pm')">Donate</button></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Junagadh Civil Hospital</td>
                    <td>25 August,2025 <br>Time : 9:00 am</td>
                    <td><button class="bt" onclick="showDonationInfo('Junagadh Civil Hospital', '25 August, 2025 - 9:00 am')">Donate</button></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Vapi Civil Hospital</td>
                    <td>19 October,2025 <br>Time : 10:00 am</td>
                    <td><button class="bt" onclick="showDonationInfo('Vapi Civil Hospital', '19 October, 2025 - 10:00 am')">Donate</button></td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="main3">
        <h2>Enter your Details</h2>

        <!-- AJAX populated info -->
        <div id="donationDetailsBox" style="margin-bottom: 20px;"></div>

        <form action="Showinfo.php" method="post">
            <input type="hidden" name="hospital" id="hospitalInput">
            <input type="hidden" name="datetime" id="datetimeInput">

            <label>Name</label><br>
            <input type="text" placeholder="Enter your Name" name="uname" required
                   style="font-family: Calibri; width: 30%; padding: 10px;margin-top: 5px;border: none;border-radius:50px; background: rgb(255,255,255,0.402);"><br><br>
            <label>Age</label><br>
            <input type="number" placeholder="Enter your Age" name="age" min="18" max="60" required
                   style="font-family: Calibri; width: 30%; padding: 10px;margin-top: 5px;border: none;border-radius:50px; background: rgb(255,255,255,0.402);"><br><br>
            <label>Mobile Number</label><br>
            <input type="number" placeholder="Enter your Mobile Number" name="mno" required
                   style="font-family: Calibri; width: 30%; padding: 10px;margin-top: 5px;border: none;border-radius:50px; background: rgb(255,255,255,0.402);"><br><br>
            <button class="bt" type="submit">Submit</button>
        </form>
    </section>

    <script>
        function showDonationInfo(hospitalName, dateTime) {
            // Populate visible details
            const detailsBox = document.getElementById("donationDetailsBox");
            detailsBox.innerHTML = `
                <p><strong>Hospital:</strong> ${hospitalName}</p>
                <p><strong>Date & Time:</strong> ${dateTime}</p>
            `;

            // Populate hidden inputs
            document.getElementById("hospitalInput").value = hospitalName;
            document.getElementById("datetimeInput").value = dateTime;

            // Scroll to form section
            detailsBox.scrollIntoView({ behavior: "smooth" });
        }
    </script>
</body>
</html>
