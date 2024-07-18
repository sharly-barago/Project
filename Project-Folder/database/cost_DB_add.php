<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'You must be logged in to add an item cost.'
    ];
    header('location: ../login.php');
    exit();
}

$user = $_SESSION['user'];
$table_name = 'item_costs';

// Validate and sanitize input
$supplier_id = filter_input(INPUT_POST, 'supplier', FILTER_VALIDATE_INT);
$item_cost = filter_input(INPUT_POST, 'itemCost', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$item_id = filter_input(INPUT_POST, 'itemID', FILTER_VALIDATE_INT);

if (!$item_id || !$supplier_id || !$item_cost) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Invalid input. Please check your entries and try again.'
    ];
    header('location: ../itemCostAddForm.php');
    exit();
}

try {
    include('connect.php');

    // Check if a cost entry already exists for this item and supplier
    $check_command = "SELECT * FROM $table_name WHERE itemID = :itemID AND supplierID = :supplierID";
    $check_stmt = $conn->prepare($check_command);
    $check_stmt->bindParam(':itemID', $item_id, PDO::PARAM_INT);
    $check_stmt->bindParam(':supplierID', $supplier_id, PDO::PARAM_INT);
    $check_stmt->execute();

    if ($check_stmt->rowCount() > 0) {
        // Update existing entry
        $command = "UPDATE $table_name SET cost = :cost WHERE itemID = :itemID AND supplierID = :supplierID";
    } else {
        // Insert new entry
        $command = "INSERT INTO $table_name (itemID, supplierID, cost) VALUES (:itemID, :supplierID, :cost)";
    }

    $stmt = $conn->prepare($command);
    $stmt->bindParam(':itemID', $item_id, PDO::PARAM_INT);
    $stmt->bindParam(':supplierID', $supplier_id, PDO::PARAM_INT);
    $stmt->bindParam(':cost', $item_cost);
    $stmt->execute();

    $response = [
        'success' => true,
        'message' => 'Item cost successfully added/updated.'
    ];
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

$_SESSION['response'] = $response;
header('location: ../productAdd.php');
exit();
