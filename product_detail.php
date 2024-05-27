<?php
include 'db.php';

$product_id = $_GET['product_id'];
$result = $conn->query("SELECT p.title, p.mrp, p.price, p.description, p.image, c.name AS category 
                        FROM tbl_products p 
                        JOIN tbl_category c ON p.category_id = c.id 
                        WHERE p.product_id = $product_id");
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <!-- <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="logout.php">Logout</a></li> -->
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="product-detail">
            <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>">
            <h1><?php echo $product['title']; ?></h1>
            <p>Category: <?php echo $product['category']; ?></p>
            <p>MRP: <?php echo $product['mrp']; ?></p>
            <p>Price: <?php echo $product['price']; ?></p>
            <p><?php echo $product['description']; ?></p>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="number" name="quantity" value="1" min="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    </div>
</body>
</html>
