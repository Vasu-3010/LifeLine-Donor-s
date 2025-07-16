<?php
/*header("Content-Type: application/json");

$host = "localhost";     
$user = "root";          
$password = "";          
$dbname = "lookblood";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed."]));
}

$district = isset($_GET['district']) ? $conn->real_escape_string($_GET['district']) : '';
$city = isset($_GET['city']) ? $conn->real_escape_string($_GET['city']) : '';

$sql = "SELECT hospital_name, a_pos, a_neg, b_pos, b_neg, ab_pos, ab_neg, o_pos, o_neg 
        FROM hospital 
        WHERE district = '$district' AND city = '$city'";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    exit;
}

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "Hospital Name" => $row["hospital_name"],
            "A+" => $row["a_pos"],
            "A-" => $row["a_neg"],
            "B+" => $row["b_pos"],
            "B-" => $row["b_neg"],
            "AB+" => $row["ab_pos"],
            "AB-" => $row["ab_neg"],
            "O+" => $row["o_pos"],
            "O-" => $row["o_neg"]
        ];
    }
}

echo json_encode($data);
$conn->close();*/
?>

<?php
header("Content-Type: application/json");

$district = isset($_GET['district']) ? $_GET['district'] : '';
$city = isset($_GET['city']) ? $_GET['city'] : '';

$data = [];
$file = fopen("hospitals.csv", "r");
$header = fgetcsv($file); // read header

while (($row = fgetcsv($file)) !== false) {
    if (strtolower($row[0]) == strtolower($district) && strtolower($row[1]) == strtolower($city)) {
        $data[] = [
            "Hospital Name" => $row[2],
            "A+" => $row[3],
            "A-" => $row[4],
            "B+" => $row[5],
            "B-" => $row[6],
            "AB+" => $row[7],
            "AB-" => $row[8],
            "O+" => $row[9],
            "O-" => $row[10],
        ];
    }
}

fclose($file);

if (empty($data)) {
    $data[] = ["message" => "No data found"];
}

echo json_encode($data);
?>