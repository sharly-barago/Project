<?php
session_start();
include('connect.php');

$data = json_decode(file_get_contents('php://input'), true);
$user_id = isset($data['userID']) ? (int) $data['userID'] : 0;

$response = ['success' => false, 'message' => ''];

try {
    if ($user_id > 0) {
        // Check if user exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE userID = :userID");
        $stmt->bindParam(':userID', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            // User exists, proceed to delete
            $stmt = $conn->prepare("DELETE FROM users WHERE userID = :userID");
            $stmt->bindParam(':userID', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $response['success'] = true;
            $response['message'] = 'User successfully deleted from the system.';
        } else {
            // User does not exist
            throw new Exception('User not found.');
        }
    } else {
        throw new Exception('Invalid user ID.');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>