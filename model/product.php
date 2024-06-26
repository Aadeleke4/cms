<?php
    session_start();

    $currentPage = 'product.php';
    
    include "navigation.php";
    $m='';
    $conn=connect();

    // Added prepared statements to prevent SQL injection.
    if(isset($_POST['submit'])){
        $pName= $_POST['pname'];
        $buy= $_POST['buy'];
        $img= $_FILES['pimage'];
        $iName= $img['name'];
        $tempName= $img['tmp_name'];
        $format= explode('.', $iName);
        $actualName= strtolower($format[0]);
        $actualFormat= strtolower($format[1]);
        $allowedFormats= ['jpg', 'png', 'jpeg', 'gif'];

        if (in_array($actualFormat, $allowedFormats)) {
            $location = 'Uploads/' . $actualName . '.' . $actualFormat;
            $category_id = $_POST['category']; // Get the selected category from the form
        
            $sql = "INSERT INTO products(name, bought, image, created_at, category_id) VALUES ('$pName', '$buy', '$location', current_timestamp(), '$category_id')";
        
            if ($conn->query($sql) === true) {
                move_uploaded_file($tempName, $location);
                $m = "Product Inserted!";
            }
           
        }

    }
    $sql = "SELECT products.*, categories.category_name 
        FROM products 
        LEFT JOIN categories ON products.category_id = categories.category_id";
$res = $conn->query($sql);
// code handles searching for products based on a search term provided via a GET request
if (isset($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);

    $sql = "SELECT products.*, categories.category_name 
            FROM products 
            LEFT JOIN categories ON products.category_id = categories.category_id
            WHERE products.name LIKE '%$searchTerm%'";
    $res = $conn->query($sql);
} else {
    // If no search query, use the existing query to fetch all products
    $sql = "SELECT products.*, categories.category_name 
            FROM products 
            LEFT JOIN categories ON products.category_id = categories.category_id";
    $res = $conn->query($sql);
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
                <div class="card">
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProduct">
                            Add New Product
                        </button>
                        <h4 style="color: green"><?php echo $m; ?></h4>
                        <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button style="background-color: white;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h2 class="modal-title" id="exampleModalScrollableTitle" style="color: white;">Add New Product</h2>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="product.php" enctype="multipart/form-data">
                                            <div class="form-group pt-20">
                                                <div class="col-sm-4">
                                                    <label for="name" class="pr-10"> Product Name</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input name="pname" type="text" class="login-input" placeholder="Product Name" id="name" required>
                                                </div>
                                            </div>
                                            <div class="form-group pt-20">
                                                <div class="col-sm-4">
                                                    <label for="buy" class="pr-10"> Buying Amount</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input name="buy" type="text" class="login-input" placeholder="Buying Amount" id="buy" required>
                                                </div>
                                            </div>
                                            <div class="form-group pt-20">
                                                <div class="col-sm-4">
                                                    <label for="pimage" class="pr-10"> Product Image</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input name="pimage" class="pl-20" type="file" id="pimage" required>
                                                </div>
                                            </div>
                                            <div class="form-group pt-20">
                                                <div class="col-sm-4">
                                                    <label for="category" class="pr-10"> Category</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select name="category" class="login-input" id="category" required>
                                                        <?php
                                                            $categorySql = "SELECT * FROM categories";
                                                            $categoryRes = $conn->query($categorySql);

                                                            while ($categoryRow = mysqli_fetch_assoc($categoryRes)) {
                                                                echo "<option value='" . $categoryRow['category_id'] . "'>" . $categoryRow['category_name'] . "</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            <div class="form-group" style="text-align: center;">
                                                <button type="submit" value="submit" name="submit" class="btn btn-success">Add</button>
                                            </div>
                                            
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="search-form">
    <form method="GET" action="product.php">
        <div class="form-group">
            <label for="search">Search:</label>
            <input type="text" class="form-control" name="search" id="search" placeholder="Enter product name">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>
                    <div class="table_container">
                        <h1 style="text-align: center; color:white;">Products Table</h1>
                        <div class="table-responsive">
                            <table class="table table-dark" id="table" data-toggle="table" data-search="true" data-filter-control="true" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                <thead class="thead-light">
                                <tr>
                                    <th data-field="name" data-filter-control="select" data-sortable="true">Product Name</th>
                                    <th data-field="bought" data-filter-control="select" data-sortable="true"> Bought</th>
                                    <th data-field="sold" data-sortable="true">Sold</th>
                                    <th data-field="stock" data-sortable="true">Available in Stock</th>
                                    <th data-field="category" data-filter-control="select" data-sortable="true">Category</th>
                                    <th data-field="actions" data-sortable="true"> Actions</th>
                                    
                                </tr>
                                </thead>
                                <tbody>
                                    <?php

                                        if(mysqli_num_rows($res)>0){
                                            while($row= mysqli_fetch_assoc($res)){
                                                $stock= $row['bought']-$row['sold'];
                                                echo "<tr>";
                                                echo "<td>".$row['name']."</td>";

                                                echo "<td>".$row['bought']."</td>";

                                                echo "<td>".$row['sold']."</td>";

                                                echo "<td>".$stock."</td>";

                                                echo "<td>" . $row['category_name'] . "</td>";
                                                

                                                echo "<td><a href='viewProduct.php?id=".$row['id']."' class='btn btn-success btn-sm'>".
                                                    "<span class='glyphicon glyphicon-eye-open'></span> </a>";
                                                echo "<a href='editProduct.php?id=".$row['id']."' class='btn btn-warning btn-sm'>".
                                                    "<span class='glyphicon glyphicon-pencil'></span> </a>";
                                                if($thisUser['is_admin']==1) {
                                                echo "<a href='deleteProduct.php?id=".$row['id']."' class='btn btn-danger btn-sm'>".
                                                    "<span class='glyphicon glyphicon-trash'></span> </a></td>";
                                                }
                                                echo "<tr>";  
                                            }
                                        } else{
                                            echo "No results found!";
                                        }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
           <?php include('side_info.php')?>
        </div>
        <?php include('footer.php')?>
    </body>
</html>