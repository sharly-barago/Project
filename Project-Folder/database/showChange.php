<?php
    include('connect.php');


    $stmt = $conn->prepare("SELECT c.dateModified, i.itemName, c.description, c.oldQuantity, c.adjustedQuantity, c.newQuantity  FROM item_changes c, item i WHERE i.itemID = c.itemID ORDER BY dateModified DESC");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetchAll();
?>