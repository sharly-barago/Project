<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'item'; // Use the new table name
$user = $_SESSION['user'];
$changes = include('database/showChange.php');

$pageTitle = 'Quantity Changes';
include('partials/header.php');
?>

<div id="dashboardMainContainer">
    <?php include('partials/sideBar.php') ?>

    <div class="dashboard_content_container" id="dashboard_content_container">
        <?php include('partials/topNavBar.php') ?>

        <div class="dashboard_content d-flex justify-content-center">
            <div class="container m-0 p-0 mw-100">
                <div class="card h-100 m-2">
                    <div class="card-header p-3 bg-white d-flex justify-content-between">
                        <h2 class="card-title m-2"><i class="fa fa-list"></i> Quantity Changes</h2>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive flex-grow-1" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                            <table class="table table-hover table-striped border-top">
                                <thead class="bg-white">
                                    <tr class="userAdd sticky-top">
                                        <th>Date Modified</th>
                                        <th>Item</th> <!-- change to name soon -->
                                        <th>Reason</th>
                                        <th>Old Quantity</th>
                                        <th>Adjusted</th>
                                        <th>New Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $index = 0;
                                    foreach ($changes as $change) { ?>
                                        <tr>
                                            <td class="pt-3"><?= htmlspecialchars($change['dateModified']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($change['itemName']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($change['description']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($change['oldQuantity']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($change['adjustedQuantity']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($change['newQuantity']) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted mt-0 mx-3"><?= count($changes) ?> changes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php
        // if (isset($_SESSION['success_message'])) {
        //     echo "alert('" . addslashes($_SESSION['success_message']) . "');";
        //     unset($_SESSION['success_message']);
        // }
        // if (isset($_SESSION['error_message'])) {
        //     echo "alert('Error: " . addslashes($_SESSION['error_message']) . "');";
        //     unset($_SESSION['error_message']);
        // }
        ?>
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.deletechange')) {
                e.preventDefault();
                const deleteButton = e.target.closest('.deletechange');
                const changeId = deleteButton.dataset.changeId;
                const changeName = deleteButton.dataset.changeName;

                if (confirm(`Are you sure you want to delete ${changeName}?`)) {
                    fetch('database/deleteProd.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                itemID: changeId
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            if (data.success) {
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                }
            }
        });
    });
</script> -->

<?php include('partials/footer.php'); ?>