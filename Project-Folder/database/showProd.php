<?php
    include('connect.php');


    $stmt = $conn->prepare("SELECT * FROM item ORDER BY itemID ASC");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetchAll();
?>