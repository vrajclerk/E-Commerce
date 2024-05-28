<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$product_id])) {
        if ($action == 'increase') {
            $_SESSION['cart'][$product_id]++;
        } elseif ($action == 'decrease' && $_SESSION['cart'][$product_id] > 1) {
            $_SESSION['cart'][$product_id]--;
        }
    }

    echo json_encode(['status' => 'success']);
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}
?>
