<?php
//session start
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$user = $_SESSION['user'];

$pageTitle = 'Dashboard';
include('partials/header.php');
?>

<div id="dashboardMainContainer">
    <!-- include Sidebar file -->
    <?php include('partials/sideBar.php') ?>

    <div class="dashboard_content_container" id="dashboard_content_container">
        <!-- include topNavigation file -->
        <?php include('partials/topNavBar.php') ?>

        <div class="dashboard_content">
            <div class="dashboard_content_main">

            </div>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>