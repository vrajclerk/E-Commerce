<?php
include 'db.php';
include 'functions.php'; 
// Start the session to track user session data
session_start();

// Retrieve search, category, and sort parameters from the URL query string
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

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
        // Initialize offset and limit for product loading
        var offset = 0;
        var limit = 4;

        // Function to load products with optional reset flag
        function loadProducts(reset = false) {
            if (reset) {
                offset = 0;
                $('#product-list').empty(); // Clear the product list if reset is true
            }
            // AJAX request to fetch products from the server
            $.ajax({
                url: 'fetch_products.php',
                type: 'GET',
                data: {
                    search: $('#search').val(),
                    category: $('#category').val(),
                    sort: $('#sort').val(),
                    limit: limit,
                    offset: offset
                },
                success: function(response) {
                    $('#product-list').append(response); // Append the response to the product list
                    offset += limit; // Increment the offset for the next set of products
                }
            });
        }

        // Initial load of products 
        loadProducts();

        // Load more products when the 'Load More' button is clicked
        $('#load-more').click(function() {
            loadProducts();
        });

        // Reload products when search, category, or sort inputs change
        $('#search, #category, #sort').on('change keyup', function() {
            loadProducts(true);
        });

        // Add to cart handling
        $(document).on('submit', '.add-to-cart-form', function(e) {
            e.preventDefault(); 
            var form = $(this);
            var productId = form.find('input[name="product_id"]').val(); // Get productID 

            // AJAX request to add product to the cart
            $.ajax({
                url: 'add_to_cart.php',
                type: 'POST',
                data: { product_id: productId },
                success: function(response) {
                    form.find('button').text('Added to Cart').attr('disabled', true); // Update button text and disable it
                    alert('product added to cart');
                },
                error: function() {
                    alert('Error adding product to cart.');
                }
            });
        });

        // Remove product
        // $(document).on('click', '.remove-from-cart', function(e) {
        //     e.preventDefault(); 
        //     var productId = $(this).data('product-id'); // Get the product ID from the data attribute

        //     // AJAX request to remove product from the cart
        //     $.ajax({
        //         url: 'remove_from_cart.php',
        //         type: 'POST',
        //         data: { product_id: productId },
        //         success: function(response) {
        //             loadProducts(true); // Reload the products list
        //             alert('product removed from cart');
        //         },
        //         error: function() {
        //             alert('Error removing product from cart.');
        //         }
        //     });
        // });

        // Increase or decrease product quantity handling
        $(document).on('click', '.increase-quantity, .decrease-quantity', function(e) {
        e.preventDefault();
        var button = $(this);
        var productId = button.data('product-id');
        var action = button.hasClass('increase-quantity') ? 'increase' : 'decrease';
        var quantitySpan = button.siblings('.quantity');
        var currentQuantity = parseInt(quantitySpan.text());

        if (action === 'increase') {
            currentQuantity += 1;
        } else if (action === 'decrease' && currentQuantity > 1) {
            currentQuantity -= 1;
        }

        $.ajax({
            url: 'add_to_cart.php',
            type: 'POST',
            data: { product_id: productId, action: action },
            success: function(response) {
                quantitySpan.text(currentQuantity);
                alert('Quantity updated');
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
                <a href="index.php" class="home-button">Home</a>
              
                <input type="text" id="search" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
               
                <select id="category" name="category">
                    <option value="">All Categories</option>
                    <?php
                    $cat_query = "SELECT * FROM category";
                    $cat_result = $conn->query($cat_query);
                    // Loop through the categories and create options for the dropdown
                    while ($row = $cat_result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '"' . ($category == $row['id'] ? ' selected' : '') . '>' . $row['name'] . '</option>';
                    }
                    ?>
                </select>
              
                <select id="sort" name="sort">
                    <option value="">Sort By</option>
                    <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Price Low to High</option>
                    <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Price High to Low</option>
                    <option value="latest" <?php echo $sort == 'latest' ? 'selected' : ''; ?>>Latest Products</option>
                </select>
                <!-- <button type="submit">Apply</button> -->
            </form>
            <a href="cart.php" class="home-button">Cart</a>
        </div>
    </header>
    <main>
        
        <div id="product-list" class="products"></div>
        <!-- <div class="product-item" data-product-id="1">
        <h3>Product Name</h3>
        <form class="add-to-cart-form">
            <input type="hidden" name="product_id" value="1">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit">Add to Cart</button>
        </form>
        <div class="quantity-controls">
            <button class="decrease-quantity" data-product-id="1">-</button>
            <span class="quantity">1</span>
            <button class="increase-quantity" data-product-id="1">+</button> -->
        </div>
    </div>
</div>

        <button id="load-more">More</button>
    </main>
</body>
</html>
