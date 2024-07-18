<?php 

function fetchSupp(){
    include('connect.php');


    $stmt = $conn->prepare("SELECT supplierID, companyName FROM supplier");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetchAll();
}

function fetchItem(){
    include('connect.php');

    $stmt = $conn->prepare("SELECT itemID, itemName FROM item");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetchAll();

}



?>