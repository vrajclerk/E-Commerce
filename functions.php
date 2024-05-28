<?php
function fetch_products($conn, $search, $category, $sort, $limit = 5, $offset = 0) {
    $query = "SELECT * FROM products WHERE title LIKE '%$search%'";
    if ($category != '') {
        $query .= " AND category_id = $category";
    }
    switch ($sort) {
        case 'price_asc':
            $query .= " ORDER BY price ASC";
            break;
        case 'price_desc':
            $query .= " ORDER BY price DESC";
            break;
        case 'latest':
            $query .= " ORDER BY product_id DESC";
            break;
        default:
            $query .= " ORDER BY product_id DESC";
            break;
    }
    $query .= " LIMIT $limit OFFSET $offset";
    return $conn->query($query);
}
?>
