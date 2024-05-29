<?php
include 'db.php';
session_start();

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($product_id > 0) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        if ($action === 'increase') {
            $_SESSION['cart'][$product_id] += 1;
        } elseif ($action === 'decrease') {
            if ($_SESSION['cart'][$product_id] > 1) {
                $_SESSION['cart'][$product_id] -= 1;
            }
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    echo json_encode(['status' => 'success', 'message' => 'Quantity updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product ID']);
}
?>
