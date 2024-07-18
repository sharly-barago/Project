<?php
include('connect.php'); // Include your database connection file

function getSuppliers($itemID = null)
{
    global $conn;

    if ($itemID) {
        $query = $conn->prepare("SELECT s.supplierID, s.companyName, ic.cost FROM supplier s 
                                 JOIN item_costs ic ON s.supplierID = ic.supplierID 
                                 WHERE ic.itemID = :itemID");
        $query->bindParam(':itemID', $itemID);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $query = $conn->query("SELECT supplierID, companyName FROM supplier");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Only output JSON if this file is called directly
if (basename($_SERVER['PHP_SELF']) == 'fetchSupplier.php') {
    $itemID = isset($_GET['itemID']) ? $_GET['itemID'] : null;
    $suppliers = getSuppliers($itemID);
    echo json_encode($suppliers);
}
