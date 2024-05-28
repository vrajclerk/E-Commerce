<?php
include 'db.php';
include 'functions.php';
session_start();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

// function fetch_products($conn, $search, $category, $sort, $limit = 5, $offset = 0) {
//     $query = "SELECT * FROM products WHERE title LIKE '%$search%'";
//     if ($category != '') {
//         $query .= " AND category_id = $category";
//     }
//     switch ($sort) {
//         case 'price_asc':
//             $query .= " ORDER BY price ASC";
//             break;
//         case 'price_desc':
//             $query .= " ORDER BY price DESC";
//             break;
//         case 'latest':
//             $query .= " ORDER BY product_id DESC";
//             break;
//         default:
//             $query .= " ORDER BY product_id DESC";
//             break;
//     }
//     $query .= " LIMIT $limit OFFSET $offset";
//     return $conn->query($query);
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        var offset = 0;
        var limit = 4;

        function loadProducts() {
            $.ajax({
                url: 'fetch_products.php',
                type: 'GET',
                data: {
                    search: '<?php echo $search; ?>',
                    category: '<?php echo $category; ?>',
                    sort: '<?php echo $sort; ?>',
                    limit: limit,
                    offset: offset
                },
                success: function(response) {
                    $('#product-list').append(response);
                    offset += limit;
                }
            });
        }

        loadProducts();

        $('#load-more').click(function() {
            loadProducts();
        });

        $(document).on('submit', '.add-to-cart-form', function(e) {
            e.preventDefault();
            var form = $(this);
            var productId = form.find('input[name="product_id"]').val();

            $.ajax({
                url: 'add_to_cart.php',
                type: 'POST',
                data: { product_id: productId },
                success: function(response) {
                    form.find('button').text('Added to Cart').attr('disabled', true);
                },
                error: function() {
                    alert('Error adding product to cart.');
                }
            });
        });

        $(document).on('click', '.remove-from-cart', function(e) {
            e.preventDefault();
            var productId = $(this).data('product-id');

            $.ajax({
                url: 'remove_from_cart.php',
                type: 'POST',
                data: { product_id: productId },
                success: function(response) {
                    loadProducts();
                },
                error: function() {
                    alert('Error removing product from cart.');
                }
            });
        });

        $(document).on('click', '.increase-quantity, .decrease-quantity', function(e) {
            e.preventDefault();
            var productId = $(this).data('product-id');
            var action = $(this).hasClass('increase-quantity') ? 'increase' : 'decrease';

            $.ajax({
                url: 'update_quantity.php',
                type: 'POST',
                data: { product_id: productId, action: action },
                success: function(response) {
                    loadProducts();
                },
                error: function() {
                    alert('Error updating quantity.');
                }
            });
        });
    });
    </script>
</head>
<body>
    <header>
        <div class="banner">
            <form method="GET" action="index.php">
                <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                <select name="category">
                    <option value="">All Categories</option>
                    <?php
                    $cat_query = "SELECT * FROM category";
                    $cat_result = $conn->query($cat_query);
                    while ($row = $cat_result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '"' . ($category == $row['id'] ? ' selected' : '') . '>' . $row['name'] . '</option>';
                    }
                    ?>
                </select>
                <select name="sort">
                    <option value="">Sort By</option>
                    <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Price Low to High</option>
                    <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Price High to Low</option>
                    <option value="latest" <?php echo $sort == 'latest' ? 'selected' : ''; ?>>Latest Products</option>
                </select>
                <button type="submit">Apply</button>
            </form>
            <a href="index.php" class="home-button">Home</a>
            <a href="cart.php" class="home-button">Cart</a>
        </div>
    </header>
    <main>
        <div id="product-list" class="products"></div>
        <button id="load-more" style="background-color: #007BFF; color: white; padding: 10px 20px; border-radius: 5px;">More</button>

    </main>
</body>
</html>
