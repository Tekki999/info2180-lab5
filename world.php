<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

try {
    $host = 'localhost';
    $username = 'lab5_user';
    $password = 'password123';
    $dbname = 'world';

    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo "Database connection failed: " . $e->getMessage();
    exit;
}

// Check for query parameters
$country = isset($_GET['country']) ? $_GET['country'] : null;
$lookup = isset($_GET['lookup']) ? $_GET['lookup'] : null;

// Validate country parameter
if (empty($country)) {
    http_response_code(400);
    echo "Please enter a country name.";
    exit;
}

if ($lookup === 'cities') {
    // Fetch cities in the given country
    $stmt = $conn->prepare("
        SELECT cities.name AS city_name, cities.district, cities.population
        FROM cities
        JOIN countries ON cities.country_code = countries.code
        WHERE countries.name LIKE :country
    ");
    $stmt->bindValue(':country', '%' . $country . '%', PDO::PARAM_STR);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output cities in a table
    echo "<table border='1'>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>District</th>
                    <th>Population</th>
                </tr>
            </thead>
            <tbody>";
    foreach ($results as $row) {
        echo "<tr>
                <td>" . htmlspecialchars($row['city_name']) . "</td>
                <td>" . htmlspecialchars($row['district']) . "</td>
                <td>" . htmlspecialchars($row['population']) . "</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    // Fetch country information
    $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
    $stmt->bindValue(':country', '%' . $country . '%', PDO::PARAM_STR);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output country information in a table
    echo "<table border='1'>
            <thead>
                <tr>
                    <th>Country Name</th>
                    <th>Continent</th>
                    <th>Independence Year</th>
                    <th>Head of State</th>
                </tr>
            </thead>
            <tbody>";
    foreach ($results as $row) {
        echo "<tr>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['continent']) . "</td>
                <td>" . htmlspecialchars($row['independence_year'] ?? 'N/A') . "</td>
                <td>" . htmlspecialchars($row['head_of_state']) . "</td>
              </tr>";
    }
    echo "</tbody></table>";
}
?>
