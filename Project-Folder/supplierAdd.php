<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'supplier'; // Use the new table name
$user = $_SESSION['user'];
$products = include('database/showSupp.php');

$pageTitle = 'Supplier Management';
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
                        <h2 class="card-title m-2"><i class="fa fa-list"></i> List of Suppliers</h2>
                        <div class="d-flex align-items-center m-2">
                            <input type="text" id="searchInput" class="search-bar mx-2 p-3" placeholder="Search for suppliers...">
                            <a href="supplierAddForm.php" class="btn btn-primary mx-2">
                                Add New Supplier
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive flex-grow-1" style="max-height: calc(100vh - 230px); overflow-y: auto;">
                            <table class="table table-hover table-striped border-top">
                                <thead class="bg-white">
                                    <tr class="userAdd sticky-top">
                                        <th>Company Name</th>
                                        <th>Address</th>
                                        <th>Contact Number</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $index = 0;
                                    foreach ($products as $product) { ?>
                                        <tr>
                                            <td class="pt-3"><?= htmlspecialchars($product['companyName']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['address']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['contactNum']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['supplierEmail']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['status']) ?></td>
                                            <td class="text-center">
                                                <a href="supplierUpdateForm.php?supplierID=<?= $product['supplierID'] ?>" class="btn btn-sm btn-outline-primary m-1">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted mt-0 mx-3"><?= count($products) ?> Suppliers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php
        if (isset($_SESSION['response'])) {
            echo "alert('" . addslashes($_SESSION['response']['message']) . "');";
            unset($_SESSION['response']);
        }
        ?>

        const searchInput = document.getElementById('searchInput');
        const table = document.querySelector('table');
        const rows = table.querySelectorAll('tbody tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.deleteSupplier')) {
                e.preventDefault();
                const deleteButton = e.target.closest('.deleteSupplier');
                const supplierId = deleteButton.dataset.supplierId;
                const supplierName = deleteButton.dataset.supplierName;

                if (confirm(`Are you sure you want to delete ${supplierName}?`)) {
                    fetch('database/deleteSupplier.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                supplierID: supplierId
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
</script>

<?php include('partials/footer.php'); ?>
