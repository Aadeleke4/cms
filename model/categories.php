<?php
    session_start();

    $currentPage = 'categories.php';

    include "navigation.php";
    $conn=connect();

    // Fetch categories for display
    $categorySql = "SELECT * FROM categories";
    $categoryRes = $conn->query($categorySql);

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
        <title> Categories </title>
    </head>
    <body>
        
        <div class="row" style="padding: 40px;">
            <div class="leftcolumn">
                <?php include('product_cards.php')?>
                <div class="card">
                <div class="text-center">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProduct">
                            Add New Product
                        </button>
                    <div class="table_container">
                        <h1 style="text-align: center; color:white;">Categories Table</h1>
                        <div class="table-responsive">
                            <table class="table table-dark" id="table" data-toggle="table" data-search="true" data-filter-control="true" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                <thead class="thead-light">
                                    <tr>
                                        <th data-field="category_id" data-filter-control="select" data-sortable="true">Category ID</th>
                                        <th data-field="category_name" data-filter-control="select" data-sortable="true">Category Name</th>
                                        <th data-field="description" data-filter-control="select" data-sortable="true">Description</th>
                                        <th data-field="created_at" data-filter-control="select" data-sortable="true">Created At</th>
                                        <th data-field="updated_at" data-filter-control="select" data-sortable="true">Updated At</th>
                                        <th data-field="actions" data-sortable="false">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(mysqli_num_rows($categoryRes) > 0) {
                                            while ($categoryRow = mysqli_fetch_assoc($categoryRes)) {
                                                echo '<tr>';
                                                echo '<td>'. $categoryRow['category_id'].'</td>';
                                                echo '<td>'. $categoryRow['category_name'].'</td>';
                                                echo '<td>'. $categoryRow['description'].'</td>';
                                                echo '<td>'. $categoryRow['created_at'].'</td>';
                                                echo '<td>'. $categoryRow['updated_at'].'</td>';

                                                // Add actions (view, edit, delete) as needed
                                                echo "<td><a href='viewCategory.php?id=".$categoryRow['category_id']."' class='btn btn-success btn-sm'>".
                                                        "<span class='glyphicon glyphicon-eye-open'></span> </a>";

                                                // Fetch products under each category
                                                $categoryId = $categoryRow['category_id'];
                                                $productSql = "SELECT * FROM products WHERE category_id = $categoryId";
                                                $productRes = $conn->query($productSql);

                                                // Display product names under each category
                                                if(mysqli_num_rows($productRes) > 0) {
                                                    echo '<br><strong>Products:</strong>';
                                                    while ($productRow = mysqli_fetch_assoc($productRes)) {
                                                        echo '<br>'.$productRow['name'];
                                                    }
                                                }

                                                echo '</td>';
                                                echo '</tr>';
                                            }
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
