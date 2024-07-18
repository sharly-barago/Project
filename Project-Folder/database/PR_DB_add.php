<?php
session_start();

$table_name = 'purchase_requests';
$table_assoc = 'pr_item';
$date_needed = $_POST['dateNeeded'];
$status = $_POST['PRStatus'];
$reason = $_POST['reason'];

$user = $_SESSION['user'];
$requested_by = $user['userID'];

$PR_id = isset($_POST['PRID']) ? $_POST['PRID'] : null;
$estimated_cost = 0.00;
$itemid = $_POST['itemID'];
$reQuant = $_POST['requestQuantity'];
$estCost = $_POST['estimatedCost'];
$suppliers = $_POST['supplier'];

try {
    include('connect.php');

    if ($PR_id != null && $status == 'pending') {
        $command = "UPDATE $table_name SET PRDateRequested = current_timestamp(), dateNeeded = :dateNeeded, PRStatus = :PRStatus, estimatedCost = :estimatedCost, reason = :reason WHERE PRID = :PRID";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':PRID', $PR_id);

        $commandItem = "UPDATE $table_assoc SET itemID = :item, supplierID = :supplier, requestQuantity = :req, estimatedCost = :est WHERE PRID = :PRID AND itemID = :item";
        $gftf = $conn->prepare($commandItem);
    } else {
        $command = "INSERT INTO $table_name (requestedBy, PRDateRequested, dateNeeded, PRStatus, estimatedCost, reason) VALUES (:requestedBy, current_timestamp(), :dateNeeded, :PRStatus, :estimatedCost, :reason)";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':requestedBy', $requested_by);

        $commandItem = "INSERT INTO $table_assoc (PRID, itemID, supplierID, requestQuantity, estimatedCost) VALUES (:PRID, :item, :supplier, :req, :est)";
        $gftf = $conn->prepare($commandItem);
    }

    $stmt->bindParam(':dateNeeded', $date_needed);
    $stmt->bindParam(':PRStatus', $status);
    $stmt->bindParam(':estimatedCost', $estimated_cost);
    $stmt->bindParam(':reason', $reason);
    $stmt->execute();

    $NEW = ($PR_id) ? $PR_id : $conn->lastInsertId();
    
    foreach ($itemid as $index => $itemId) {
        $itemEstimatedCost = $estCost[$index];
        $estimated_cost += $itemEstimatedCost;
        $gftf->execute([
            ':PRID' => $NEW,
            ':item' => $itemId,
            ':supplier' => $suppliers[$index] ?: null,
            ':req' => $reQuant[$index],
            ':est' => $itemEstimatedCost
        ]); 
    }

    $command = "UPDATE $table_name SET estimatedCost = :estimatedCost WHERE PRID = :PRID";
    $stmt = $conn->prepare($command);
    $stmt->bindParam(':PRID', $NEW);  
    $stmt->bindParam(':estimatedCost', $estimated_cost);
    $stmt->execute();

    $message = "Purchase request successfully " . ($PR_id ? "updated" : "added") . ".";
    $_SESSION['success_message'] = $message;
    header('location: ../PR.php');
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Error processing purchase request: ' . $e->getMessage();
    header('location: ../PR.php');
}
?>