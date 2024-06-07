<?php
// Assume you have a database connection in $conn
if (isset($userid)) {
    $stmt = $conn->prepare("SELECT * FROM users_info WHERE id = ?");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $thisUser = $result->fetch_assoc();
    $stmt->close();
} else {
    // Handle the case where $userid is not set
    die("User ID not set.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Information</title>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/product.css">
</head>
<body>
    <div class="rightcolumn">
        <div class="card text-center">
            <h2>About User</h2>
            <?php if ($thisUser): ?>
                <div style="height:100px;">
                    <img src="<?php echo htmlspecialchars($thisUser['avatar']); ?>" height="100px" width="100px" class="img-circle" alt="User Avatar">
                </div>
                <p>
                    <h4><?php echo htmlspecialchars($thisUser['name']); ?></h4>
                    is working here since 
                    <h4><?php echo date('F j, Y', strtotime($thisUser['created_at'])); ?></h4>
                </p>
            <?php else: ?>
                <p>User not found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
