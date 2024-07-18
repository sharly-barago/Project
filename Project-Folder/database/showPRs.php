<?php
include('connect.php');

$stmt = $conn->prepare("SELECT p.PRID, CONCAT(u.fname, ' ', u.lname) AS requestedBy, p.PRDateRequested, p.dateNeeded, p.PRStatus, p.estimatedCost 
                        FROM purchase_requests p, users u 
                        WHERE p.requestedBy = u.userID
                        ORDER BY PRID DESC");
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);

return $stmt->fetchAll();
