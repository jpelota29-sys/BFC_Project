<?php
include 'db.php';      // your database connection
session_start();       // start PHP session to track user

$product_id = $_POST['product_id']; // product ID sent via AJAX
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
$session_id = session_id();         // unique session ID for this user

// Check if product already in cart
$sql_check = "SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("si", $session_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Product exists: update quantity
    $row = $result->fetch_assoc();
    $new_qty = $row['quantity'] + $quantity;
    $sql_update = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ii", $new_qty, $row['id']);
    $stmt_update->execute();
} else {
    // Product not in cart: insert new row
    $sql_insert = "INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("sii", $session_id, $product_id, $quantity);
    $stmt_insert->execute();
}

// Respond with JSON
echo json_encode(['status' => 'success']);
?>
