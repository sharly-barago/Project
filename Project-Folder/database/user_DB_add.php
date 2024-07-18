<?php
session_start();
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['userID'] ?? null;
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $department = $_POST['department'];
    $permissions = $_POST['permissions'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $workStatus = $_POST['workStatus'];

    try {
        // Check if the email already exists
        $emailCheckCommand = "SELECT COUNT(*) FROM users WHERE email = :email";
        if ($userID) {
            $emailCheckCommand .= " AND userID != :userID";
        }
        $stmt = $conn->prepare($emailCheckCommand);
        $stmt->bindParam(':email', $email);
        if ($userID) {
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        }
        $stmt->execute();
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            $response = [
                'success' => false,
                'message' => 'This email is already in use.'
            ];
        } else {
            if ($userID) {
                // Update existing user
                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $command = "UPDATE users SET fname = :fname, lname = :lname, department = :department, permissions = :permissions, email = :email, password = :password, workStatus = :workStatus WHERE userID = :userID";
                    $stmt = $conn->prepare($command);
                    $stmt->bindParam(':password', $hashed_password);
                } else {
                    $command = "UPDATE users SET fname = :fname, lname = :lname, department = :department, permissions = :permissions, email = :email, workStatus = :workStatus WHERE userID = :userID";
                    $stmt = $conn->prepare($command);
                }
                $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                $response = [
                    'success' => true,
                    'message' => 'User successfully updated.'
                ];
            } else {
                // Insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $command = "INSERT INTO users (fname, lname, department, permissions, email, password, workStatus) VALUES (:fname, :lname, :department, :permissions, :email, :password, :workStatus)";
                $stmt = $conn->prepare($command);
                $stmt->bindParam(':password', $hashed_password);
                $response = [
                    'success' => true,
                    'message' => 'User successfully added.'
                ];
            }

            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':department', $department);
            $stmt->bindParam(':permissions', $permissions);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':workStatus', $workStatus);

            $stmt->execute();
        }
    } catch (PDOException $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }

    $_SESSION['response'] = $response;
    header('location: ../userAdd.php');
    exit();
}
?>
