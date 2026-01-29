<?php
function searchProducts($search_query, $store_name, $price_range, $sort_by, $category, $conn) {
    $sql = "SELECT p.*, s.StoreName FROM products p INNER JOIN stores s ON p.StoreID = s.StoreID";
    $conditions = [];

    if (!empty($search_query)) {
        $conditions[] = "(p.ProductName LIKE '%" . $conn->real_escape_string($search_query) . "%' OR s.StoreName LIKE '%" . $conn->real_escape_string($search_query) . "%')";
    }

    if (!empty($store_name)) {
        $conditions[] = "s.StoreName LIKE '%" . $conn->real_escape_string($store_name) . "%'";
    }

    if (!empty($price_range)) {
        list($price_min, $price_max) = explode('-', $price_range);
        $conditions[] = "p.Price BETWEEN " . floatval($price_min) . " AND " . floatval($price_max);
    }

    if (!empty($category)) {
        $conditions[] = "p.CategoryID = " . intval($category);
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(' AND ', $conditions);
    }

    // Apply sorting
    switch ($sort_by) {
        case 'price_asc':
            $sql .= " ORDER BY p.Price ASC";
            break;
        case 'price_desc':
            $sql .= " ORDER BY p.Price DESC";
            break;
        default:
            // No sorting applied
            break;
    }

    $result = $conn->query($sql);

    $products = [];
    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        $result->free();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    return $products;
}
