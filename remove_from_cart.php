<?php
include 'db.php';
session_start();

$product_id = $_POST['product_id'];
$session_id = session_id();

// Delete from cart table
$sql = "DELETE FROM cart WHERE session_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $session_id, $product_id);
$stmt->execute();

echo json_encode(['status' => 'success']);
?>
