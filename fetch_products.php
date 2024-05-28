<?php
include 'db.php';
include 'functions.php'; // Include the functions file

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

$result = fetch_products($conn, $search, $category, $sort, $limit, $offset,);

while ($row = $result->fetch_assoc()):
?>
    <div class="product">
        <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
        <p>MRP: <?php echo htmlspecialchars($row['mrp']); ?></p>
        <p>Price: <?php echo htmlspecialchars($row['price']); ?></p>
        <p>Discount: <?php echo round((($row['mrp'] - $row['price']) / $row['mrp']) * 100); ?>%</p>
        <!-- <p>Quantity: <span class="quantity-display">' . $quantity_in_cart . '</span> -->
        <form class="add-to-cart-form">
            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
            <button type="submit">Add to Cart</button>
        </form>
        <!-- <button class="remove-from-cart" data-product-id="<?php echo $row['product_id']; ?>">Remove from Cart</button> -->
        <p>
        <button class="increase-quantity" data-product-id="<?php echo $row['product_id']; ?>">+</button>
        <!-- <input type="number" class="quantity" data-product-id="<?php echo $row['product_id']; ?>" value="<?php echo $row['quantity']; ?>" min="1"> -->
        <!-- echo '<p>Quantity: <span class="quantity-display">' . $quantity_in_cart . '</span></p>'; -->
        <button class="decrease-quantity" data-product-id="<?php echo $row['product_id']; ?>">-</button>
</p>
    </div>
<?php endwhile; ?>
