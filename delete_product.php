<?php
include 'db.php';

$product_id = $_POST['product_id'];

// Delete from products (will automatically cascade to cart if FK is ON DELETE CASCADE)
$sql = "DELETE FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();

echo json_encode(['status' => 'success']);
?>
