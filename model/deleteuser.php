<?php
// the connect function
function connect() {
    $host = 'localhost';
    $dbname = 'inventory_project';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // Sets the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

session_start();

// Call the connect function
$conn = connect();

$Id = isset($_GET['id']) ? $_GET['id'] : null;

if ($Id) {
    $stmt = $conn->prepare("DELETE FROM users_info WHERE id = :id");
    $stmt->bindParam(':id', $Id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['admin_deleted'] = "Admin successfully deleted.";
    } else {
        $_SESSION['admin_deleted'] = "Failed to delete admin.";
    }
}

header('Location: user.php');
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Deletion</title>
</head>
<body>

<?php
// Display the deletion message
if (isset($_SESSION['admin_deleted'])) {
    echo "<p>{$_SESSION['admin_deleted']}</p>";
    unset($_SESSION['admin_deleted']); // Clear the message to avoid displaying it on subsequent page loads
}
?>

</body>
</html>
