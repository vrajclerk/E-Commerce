<?php
include 'db.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

$query = "SELECT p.product_id, p.image, p.title, p.mrp, p.price, (p.mrp - p.price) AS discount 
          FROM products p 
          WHERE 1";

if ($search) {
    $query .= " AND (p.title LIKE '%$search%' OR p.description LIKE '%$search%')";
}

if ($category_id) {
    $query .= " AND p.category_id = $category_id";
}

if ($sort == 'price_high_to_low') {
    $query .= " ORDER BY p.price DESC";
} elseif ($sort == 'price_low_to_high') {
    $query .= " ORDER BY p.price ASC";
} else {
    $query .= " ORDER BY p.product_id DESC";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My E-commerce Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1><span class="highlight">My</span> E-commerce</h1>
            </div>
            <nav>
                <ul>
                    <li class="current"><a href="index.php">Home</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <!-- <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="logout.php">Logout</a></li> -->
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <form method="GET" action="index.php">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo $search; ?>">
            <select name="category_id">
                <option value="">All Categories</option>
                <?php
                $categories = $conn->query("SELECT id, name FROM category");
                while ($category = $categories->fetch_assoc()) {
                    $selected = $category_id == $category['id'] ? 'selected' : '';
                    echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
                }
                ?>
            </select>
            <select name="sort">
                <option value="">Sort by</option>
                <option value="price_low_to_high" <?php echo $sort == 'price_low_to_high' ? 'selected' : ''; ?>>Price Low to High</option>
                <option value="price_high_to_low" <?php echo $sort == 'price_high_to_low' ? 'selected' : ''; ?>>Price High to Low</option>
            </select>
            <button type="submit">Filter</button>
        </form>

        <div class="products">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="product">
                    <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
                    <h2><?php echo $row['title']; ?></h2>
                    <p>MRP: <?php echo $row['mrp']; ?></p>
                    <p>Price: <?php echo $row['price']; ?></p>
                    <p>Discount: <?php echo $row['discount']; ?></p>
                    <a href="product_detail.php?product_id=<?php echo $row['product_id']; ?>">View Details</a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
