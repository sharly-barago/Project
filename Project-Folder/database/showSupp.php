<?php
    include('connect.php');


    $stmt = $conn->prepare("SELECT * FROM supplier ORDER BY supplierID ASC");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetchAll();
?>