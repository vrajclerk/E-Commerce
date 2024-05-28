<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }

    echo json_encode(['status' => 'success']);
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}
?>
