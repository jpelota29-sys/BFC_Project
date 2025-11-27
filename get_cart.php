<?php
include 'db.php';
session_start();

$session_id = session_id();

$sql = "
SELECT c.product_id, p.name, p.price, c.quantity
FROM cart c
JOIN products p ON c.product_id = p.id
WHERE c.session_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $session_id);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

echo json_encode($cartItems);
?>
