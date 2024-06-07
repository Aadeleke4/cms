<?php
session_start();

$currentPage = 'product.php';

include "navigation.php";
$conn = connect();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * from products WHERE id=$id limit 1";
    $res = mysqli_fetch_assoc($conn->query($sql));

    $img = $res['image'];

    if (isset($_POST['deleteImage'])) {
        // Deletes the image file
        if ($img !== null && file_exists($img)) {
            unlink($img);
        }

        // Update the database record to set the image column to NULL
        $updateSql = "UPDATE products SET image = NULL WHERE id = $id";
        $conn->query($updateSql);

        // Refresh the page to reflect the changes
        header("Location: viewProduct.php?id=$id");
        exit();
    }

    if (isset($_FILES['newImage']) && $_FILES['newImage']['error'] === UPLOAD_ERR_OK) {
        $newImg = $_FILES['newImage'];
        $newImgName = $newImg['name'];
        $newImgTempName = $newImg['tmp_name'];

        // Specifies the directory for storing uploaded images
        $uploadDir = 'Uploads/';
        $newLocation = $uploadDir . $newImgName;

        // Move the uploaded image to the specified directory
        move_uploaded_file($newImgTempName, $newLocation);

        // Update the database record with the new image path
        $updateSql = "UPDATE products SET image = '$newLocation' WHERE id = $id";
        $conn->query($updateSql);

        // Refresh the page to reflect the changes
        header("Location: viewProduct.php?id=$id");
        exit();
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
                            <h1 style="color:#130553;"> Product Details</h1>
                        </div>
                        <div class="row p-20" >
                            <div class="row col-sm-6">
                            <div class="col-sm-6 p-20 pull-left">
                                <?php if ($img !== null): ?>
                                    <img src="<?php echo $img; ?>" height="250" width="250">
                                    <form method="POST" action="viewProduct.php?id=<?php echo $id; ?>" style="margin-top:10px;">
                                        <button type="submit" name="deleteImage" class="btn btn-danger">Delete Image</button>
                                    </form>
                                <?php else: ?>
                                    <img src="path/to/placeholder-image.jpg" height="250" width="250" alt="No image available">
        <p>No image available</p>
                                <?php endif; ?>

                                <form method="POST" action="viewProduct.php?id=<?php echo $id; ?>" enctype="multipart/form-data" style="margin-top:10px;">
                                    <input type="file" name="newImage" accept="image/*">
                                    <button type="submit" name="addImage" class="btn btn-success">Add Image</button>
                                </form>
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
                            <div class="col-sm-6">
                                    <a href="editProduct.php?id=<?php echo $res['id']; ?>"><button class="btn btn-warning">Edit</button></a>
                                </div>
                                <div class="col-sm-6">
                                    <a href="deleteProduct.php?id=<?php echo $res['id']; ?>"><button class="btn btn-danger">Delete</button></a>
                                </div>
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