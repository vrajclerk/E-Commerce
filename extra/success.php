<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <header>
        <div class="banner">
            <form method="GET" action="index.php">
                <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
                <select name="category">
                    <option value="">All Categories</option>
                    <?php
                    // Fetch categories from the database
                    include 'db.php';
                    $cat_query = "SELECT * FROM category";
                    $cat_result = $conn->query($cat_query);
                    while ($row = $cat_result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '"' . (isset($_GET['category']) && $_GET['category'] == $row['id'] ? ' selected' : '') . '>' . $row['name'] . '</option>';
                    }
                    ?>
                </select>
                <select name="sort">
                    <option value="">Sort By</option>
                    <option value="price_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_asc' ? 'selected' : ''; ?>>Price Low to High</option>
                    <option value="price_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_desc' ? 'selected' : ''; ?>>Price High to Low</option>
                    <option value="latest" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'latest' ? 'selected' : ''; ?>>Latest Products</option>
                </select>
                <button type="submit">Apply</button>
            </form>
        </div>
    </header>
    <main>
        <div class="success">
            <h1>Thank You!</h1>
            <p>Your order has been placed successfully.</p>
            <p><a href="index.php">Continue Shopping</a></p>
        </div>
    </main>
</body>
</html>
