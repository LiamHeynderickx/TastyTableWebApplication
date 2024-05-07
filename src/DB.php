<?php
global $db;

$dsn = "mysql:host=mysql.studev.groept.be;port=3306;dbname=a23www406";
$username = "a23www406";
$password = "TPnGIUzt";

try {
    // Create a new PDO database connection
    $db = new PDO($dsn, $username, $password);
    // Set PDO to throw exceptions on error
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Function to handle database queries
function executeQuery($query, $params = []) {
    global $db;
    try {
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        // Handle query errors
        echo "Query execution failed: " . $e->getMessage();
        exit();
    }
}

?>