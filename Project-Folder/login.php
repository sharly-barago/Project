<?php
session_start();

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('database/connect.php');
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = 'SELECT * FROM users WHERE email=:email';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();

        if (password_verify($password, $user['password']) && $user['workStatus'] == 1) {
            $_SESSION['user'] = $user;
            header('Location: productAdd.php');
            exit();
        } else if (password_verify($password, $user['password']) && $user['workStatus'] == 0) {
            $error_message = "Account is inactive.";
        } else {
            $error_message = "Please make sure that your credentials are correct.";
        }
    } else {
        $error_message = "Please make sure that your credentials are correct.";
    }
}

$pageTitle = 'Login';
$bodyClass = 'login-page';
include('partials/header.php');
?>

<div class="overlay"></div>
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card login-card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="images/Palm_Grass_logo.png" alt="Palm Grass Hotel" class="img-fluid logo">
                        <hr class="divider"/>
                    </div>

                    <?php if (!empty($error_message)) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong> <?= $error_message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>

                    <form action="login.php" method="POST">
                        <div class="mb-4">
                            <label for="email" class="form-label login">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label login">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg mt-5 mb-4">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>