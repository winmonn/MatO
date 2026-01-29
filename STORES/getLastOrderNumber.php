<?php
function getLastOrderNumber($conn, $storeID) {
    $query = "SELECT COUNT(*) AS order_count FROM orders o
              JOIN orderitems oi ON o.OrderID = oi.order_id
              JOIN products p ON oi.product_id = p.ProductID
              WHERE p.StoreID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $storeID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['order_count'];
}
?>
