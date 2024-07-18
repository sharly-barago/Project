<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'item'; // Use the new table name
$user = $_SESSION['user'];
$products = include('database/showProd.php');

$pageTitle = 'Product Management';
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
                        <h2 class="card-title m-2"><i class="fa fa-list"></i> List of Products</h2>
                        <div class="d-flex align-items-center m-2">
                            <input type="text" id="searchInput" class="search-bar mx-2 p-3" placeholder="Search for products...">
                            <a href="productAddForm.php" class="btn btn-primary mx-2">Add New Product</a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive flex-grow-1" style="max-height: calc(100vh - 230px); overflow-y: auto;">
                            <table class="table table-hover table-striped border-top">
                                <thead class="bg-white">
                                    <tr class="userAdd sticky-top">
                                        <th>Item Name</th>
                                        <th>Unit of Measure</th>
                                        <th>Item Type</th>
                                        <th>Quantity</th>
                                        <th>Min Stock Level</th>
                                        <th>Item Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product) : ?>
                                        <tr>
                                            <td class="pt-3"><?= htmlspecialchars($product['itemName']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['unitOfMeasure']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['itemType']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['quantity']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['minStockLevel']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['itemStatus']) ?></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-success m-1" data-bs-toggle="modal" data-bs-target="#ItemSuppliers" data-item-id="<?= $product['itemID'] ?>">
                                                    <i class="fa fa-eye"></i> Suppliers
                                                </button>
                                                <a href="productUpdateForm.php?itemID=<?= $product['itemID'] ?>" class="btn btn-sm btn-outline-primary m-1">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted mt-0 mx-3"><?= count($products) ?> Products</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include the itemSuppliersModal.php here -->
<?php include('partials/ItemSuppliersModal.php'); ?>

<script>
    // Function to attach modal event listeners
    function attachModalListeners() {
        const modalButtons = document.querySelectorAll('button[data-bs-toggle="modal"]');
        modalButtons.forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                const itemName = this.closest('tr').querySelector('td:nth-child(1)').textContent;

                document.getElementById('itemName').textContent = itemName;

                // Fetch supplier data for the specific item
                fetch('database/product_DB_add.php?action=getSuppliers&itemID=' + itemId)
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('supplierTableBody');
                        tableBody.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach(supplier => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td class="pt-3">${supplier.companyName}</td>
                                    <td class="pt-3">${supplier.cost}</td>
                                    <td class="pt-3">${supplier.status}</td>
                                    <td class="text-center">
                                        <a href="supplierUpdateForm.php?supplierID=${supplier.supplierID}" class="btn btn-sm btn-outline-primary m-1">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger deleteSupplier m-1" data-supplier-id="${supplier.supplierID}" data-company-name="${supplier.companyName}">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </td>
                                `;
                                tableBody.appendChild(row);
                            });
                        } else {
                            const row = document.createElement('tr');
                            row.innerHTML = `<td colspan="3" class="pt-3 text-center">No suppliers available</td>`;
                            tableBody.appendChild(row);
                        }

                        document.getElementById('supplierCount').textContent = data.length + ' Suppliers';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while fetching supplier data.');
                    });
            });
        });
    }

    // Search bar functionality
    document.addEventListener('DOMContentLoaded', function() {
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
            // Re-attach modal event listeners after filtering
            attachModalListeners();
        });

        <?php if (isset($_SESSION['response'])) : ?>
            alert('<?= addslashes($_SESSION['response']['message']) ?>');
            <?php unset($_SESSION['response']); ?>
        <?php endif; ?>

        document.addEventListener('click', function(e) {
            if (e.target.closest('.deleteProduct')) {
                e.preventDefault();
                const deleteButton = e.target.closest('.deleteProduct');
                const productId = deleteButton.dataset.productId;
                const productName = deleteButton.dataset.productName;

                if (confirm(`Are you sure you want to delete ${productName}?`)) {
                    fetch('database/deleteProd.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ itemID: productId }),
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

        // Initial attachment of modal event listeners
        attachModalListeners();
    });
</script>

<?php include('partials/footer.php'); ?>
