<?php
session_start();

$currentPage = 'product.php';

include "navigation.php";
$conn = connect();

$id = $_SESSION['userid'];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['Submit'])) {
    $id = $_POST['id'];

    // Fetch product details to check ownership
    $checkOwnershipQuery = "SELECT created_by FROM products WHERE id = $id LIMIT 1";
    $ownershipResult = $conn->query($checkOwnershipQuery);

    if ($ownershipResult) {
        $productOwnership = mysqli_fetch_assoc($ownershipResult)['created_by'];

        // Allows deletion only if the user is the owner (normal user) or is an admin
        if ($_SESSION['is_admin'] == 1 || $productOwnership == $_SESSION['userid']) {
            $deleteQuery = "DELETE FROM products WHERE id = $id LIMIT 1";
            $conn->query($deleteQuery);
        }
    }

    header("Location: product.php");
    exit;
}

$sql = "SELECT * FROM products WHERE id = $id LIMIT 1";
$res = mysqli_fetch_assoc($conn->query($sql));

$img = $res['image'];

$is_admin = $_SESSION['is_admin'];

// Check if the user is an admin
if ($is_admin != 1) {
    // Check if the product belongs to the user
    if ($res['created_by'] != $_SESSION['userid']) {
        header("Location: product.php");
        exit;
    }
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=10" >

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/product.css">
        <link rel="stylesheet" type="text/css" href="../css/navigation.css">
        <title> Products </title>
    </head>

    <body>
        <div class="row" style="padding: 50px;">
            <div class="leftcolumn">
                <?php include('product_cards.php')?>
                <div class="pt-20 pl-20">
                    <div class="col-sm-12" style="background-color: white; border: solid rgb(0, 162, 255);">
                        <div class="text-center">
                            <h2 style="color:red;"> The product will be deleted!!!</h2>
                        </div>
                        <div class="row p-20" >
                            <div class="row col-sm-6">
                                <div class="col-sm-6 p-20 pull-left" >
                                    <img src="<?php echo $img; ?>" height="250" width="250">
                                </div>
                            </div>
                            <div class="row col-sm-6">
                                <h4 class="pull-left col-sm-6">Name:</h4>
                                <div class="col-sm-6">
                                    <h4  class="pull-left" style="color: black;"><?php echo ucwords($res['name']) ?></h4>
                                </div>
                            </div>
                            <div class="row col-sm-6">
                                <h4 class="pull-left col-sm-6">Buy Quantity:</h4>
                                <div class="col-sm-6">
                                    <h4  class="pull-left" style="color: black;"><?php echo $res['bought'] ?></h4>
                                </div>
                            </div>
                            <div class="row col-sm-6">
                                <h4 class="pull-left col-sm-6">Sell Quantity:</h4>
                                <div class="col-sm-6">
                                    <h4  class="pull-left" style="color: black;"><?php echo $res['sold'] ?></h4>
                                </div>
                            </div>
                            <div class="row  col-sm-6">
                                <h4 class="pull-left col-sm-6">Created at:</h4>
                                <div class="col-sm-6">
                                    <h4  class="pull-left" style="color: black;"><?php echo date("F j, Y",strtotime(str_replace('-','/', $res['created_at'])))?></h4>
                                </div>
                            </div>
                            <div class="row col-sm-6 text-center" style="padding: 20px">
                                <form method="POST" action="deleteProduct.php">
                                    <input type="hidden" value="<?php echo $res['id']; ?>" name="id">
                                        <div class="row">
                                            <div class="text-center">
                                                <input class="btn btn-danger" type="submit" name="Submit" value="Delete">
                                            </div>
                                        </div>
                                </form>                          
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('side_info.php')?>
        </div>
        <?php include('footer.php')?>
    </body>
</html>