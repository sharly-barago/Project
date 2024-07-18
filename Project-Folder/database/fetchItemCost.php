<?php
include('connect.php'); // Include your database connection file

$itemID = $_GET['itemID'];
$supplierID = $_GET['supplierID'];

try {
    $query = $conn->prepare("SELECT cost FROM item_costs WHERE itemID = :itemID AND supplierID = :supplierID");
    $query->bindParam(':itemID', $itemID);
    $query->bindParam(':supplierID', $supplierID);
    $query->execute();

    if ($query->rowCount() > 0) {
        $row = $query->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['cost' => $row['cost']]);
    } else {
        echo json_encode(['cost' => 0]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
