<?php
session_start();
if (!isset($_SESSION['user'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access!'
    ]);
    exit;
}

include('connect.php');

$data = json_decode(file_get_contents('php://input'), true);
$item_id = isset($data['itemID']) ? (int) $data['itemID'] : 0;
$supplier_id = isset($data['supplierID']) ? (int) $data['supplierID'] : 0;

try {
    if ($item_id > 0 && $supplier_id > 0) {
        $stmt = $conn->prepare("DELETE FROM item_costs WHERE itemID = :itemID AND supplierID = :supplierID");
        $stmt->bindParam(':itemID', $item_id, PDO::PARAM_INT);
        $stmt->bindParam(':supplierID', $supplier_id, PDO::PARAM_INT);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => 'Supplier cost successfully deleted from the system.'
        ];
    } else {
        throw new Exception('Invalid item ID or supplier ID.');
    }
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
