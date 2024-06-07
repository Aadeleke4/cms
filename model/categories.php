<?php
session_start();

$currentPage = 'categories.php';

include "navigation.php";
$conn = connect();

// Fetch categories for display
$categorySql = "SELECT * FROM categories";
$categoryRes = $conn->query($categorySql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Categories</title>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/product.css">
    <link rel="stylesheet" type="text/css" href="../css/navigation.css">
</head>
<body>
    <div class="row" style="padding: 40px;">
    <?php include('side_info.php')?>
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
                                    if ($categoryRes && mysqli_num_rows($categoryRes) > 0) {
                                        while ($categoryRow = mysqli_fetch_assoc($categoryRes)) {
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($categoryRow['category_id']) . '</td>';
                                            echo '<td>' . htmlspecialchars($categoryRow['category_name']) . '</td>';
                                            echo '<td>' . htmlspecialchars($categoryRow['description']) . '</td>';
                                            echo '<td>' . htmlspecialchars($categoryRow['created_at']) . '</td>';
                                            echo '<td>' . htmlspecialchars($categoryRow['updated_at']) . '</td>';

                                            // Add actions (view, edit, delete) as needed
                                            echo "<td><a href='viewCategory.php?id=" . htmlspecialchars($categoryRow['category_id']) . "' class='btn btn-success btn-sm'>".
                                                 "<span class='glyphicon glyphicon-eye-open'></span></a>";

                                            // Fetch products under each category
                                            $categoryId = $categoryRow['category_id'];
                                            $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
                                            $stmt->bind_param("i", $categoryId);
                                            $stmt->execute();
                                            $productRes = $stmt->get_result();

                                            // Display product names under each category
                                            if ($productRes && mysqli_num_rows($productRes) > 0) {
                                                echo '<br><strong>Products:</strong>';
                                                while ($productRow = mysqli_fetch_assoc($productRes)) {
                                                    echo '<br>' . htmlspecialchars($productRow['name']);
                                                }
                                            }

                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="6">No categories found.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <?php include('footer.php')?>

    <!-- Modal for adding a new product -->
    <div id="addProduct" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Product</h4>
                </div>
                <div class="modal-body">
                    <form action="add_product.php" method="POST" enctype="multipart/form-data">
                        <!-- Form fields for adding product -->
                        <div class="form-group">
                            <label for="pname">Product Name:</label>
                            <input type="text" class="form-control" id="pname" name="pname" required>
                        </div>
                        <div class="form-group">
                            <label for="buy">Buy Price:</label>
                            <input type="text" class="form-control" id="buy" name="buy" required>
                        </div>
                        <div class="form-group">
                            <label for="pimage">Product Image:</label>
                            <input type="file" class="form-control" id="pimage" name="pimage" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Category:</label>
                            <select class="form-control" id="category" name="category" required>
                                <?php
                                // Fetch categories for the dropdown
                                $categoryRes = $conn->query($categorySql);
                                if ($categoryRes && mysqli_num_rows($categoryRes) > 0) {
                                    while ($categoryRow = mysqli_fetch_assoc($categoryRes)) {
                                        echo '<option value="' . htmlspecialchars($categoryRow['category_id']) . '">' . htmlspecialchars($categoryRow['category_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
