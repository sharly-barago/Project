<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'item_costs';

if (isset($_GET['itemID'])) {
    $_SESSION['itemID'] = $_GET['itemID'];
}

$pageTitle = 'Add Supplier';
include('partials/header.php');
include('database/fetchSupplier.php'); // Include the file to fetch suppliers
$suppliers = getSuppliers();

$itemID = $_GET['itemID'];
$itemName = $_GET['itemName'];
?>

<div id="dashboardMainContainer">
    <?php include('partials/sideBar.php') ?>

    <div class="dashboard_content_container" id="dashboard_content_container">
        <?php include('partials/topNavBar.php') ?>

        <div class="dashboard_content d-flex justify-content-center">
            <div class="container mt-3">
                <div class="card m-5">
                    <div class="card-header p-3 bg-white">
                        <h2 class="card-title m-2">Add Supplier to <span id="itemName"><?php echo htmlspecialchars($itemName); ?></span></h2>
                    </div>
                    <div class="card-body p-5" style="max-height: calc(100vh - 300px); overflow-y: auto;">
                        <form action="database/cost_DB_add.php" method="POST" class="AddForm">
                            <input type="hidden" name="itemID" value="<?php echo $_SESSION['itemID']; ?>">
                            <div class="addFormContainer mb-3">
                                <label for="supplier" class="form-label">Supplier</label>
                                <select id="supplier" name="supplier" class="form-control" required>
                                    <?php if (empty($suppliers)) : ?>
                                        <option value="">No suppliers available</option>
                                    <?php else : ?>
                                        <?php foreach ($suppliers as $supplier) : ?>
                                            <option value="<?= $supplier['supplierID'] ?>"><?= $supplier['companyName'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="itemCost" class="form-label">Item Cost</label>
                                <input type="number" step="0.01" min="0" max="99999.99" class="form-control" name="itemCost" id="itemCost" required>
                            </div>
                            <div class="d-flex flex-row-reverse flex-wrap">
                                <button type="submit" class="btn btn-primary mx-1 mt-4">Submit</button>
                                <a href="productAdd.php" class="btn btn-secondary mx-1 mt-4">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>