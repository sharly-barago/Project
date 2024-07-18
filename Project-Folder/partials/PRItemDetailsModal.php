<div class="modal fade" id="PRItemDetails" tabindex="-1" aria-labelledby="PRItemDetailsView" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header mx-2 d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <h5 class="modal-title" id="PRItemDetailsViewLabel">Purchase Request Details</h5>
                    <div class="vr mx-2"></div>
                    <p class="mb-0 text-muted"><small>
                            <span>Total Cost:</span>
                            <span id="totalCost">0</span>
                        </small></p>
                    <div class="vr mx-2"></div>
                    <p class="mb-0 text-muted"><small>
                            <span>Reason: </span>
                            <span id="prReason"></span>
                        </small></p>
                </div>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <div class="modal-body">
                <input type="hidden" id="PRIDInput" value="">

                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover table-striped border" id="PRDetailsTable">
                        <thead class="bg-white">
                            <tr class="sticky-top">
                                <th>Item Name</th>
                                <th>Supplier</th>
                                <th>Quantity</th>
                                <th>Estimated Cost</th>
                            </tr>
                        </thead>
                        <tbody id="PRDetailsTableBody" class="text-start">
                            <tr>
                                <td colspan="4" class="pt-3 text-center">No items found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted mt-0 mb-0 text-start" style="font-size: 16px;" id="productCount">0 Products</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    var PRItemDetailsModal = document.getElementById('PRItemDetails');
    PRItemDetailsModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var PRID = button.getAttribute('data-pr-id');
        document.getElementById('PRIDInput').value = PRID;

        // Use AJAX to fetch the details
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'database/fetchPRDetails.php?PRID=' + PRID, true);
        xhr.onload = function() {
            if (this.status === 200) {
                var response = JSON.parse(this.responseText);
                var products = response.products;
                var reason = response.reason;
                var tableBody = document.getElementById('PRDetailsTableBody');
                var productCount = document.getElementById('productCount');
                var totalCostElement = document.getElementById('totalCost');
                var reasonElement = document.getElementById('prReason');

                // Clear existing table rows
                tableBody.innerHTML = '';

                var totalCost = 0;

                if (products.length > 0) {
                    products.forEach(function(product) {
                        totalCost += parseFloat(product.estimatedCost);
                        var row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="pt-3">${product.itemName}</td>
                            <td class="pt-3">${product.supplierName}</td>
                            <td class="pt-3">${product.requestQuantity}</td>
                            <td class="pt-3">${product.estimatedCost}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    var row = document.createElement('tr');
                    row.innerHTML = `<td colspan="4" class="pt-3 text-center">No items found.</td>`;
                    tableBody.appendChild(row);
                }

                productCount.textContent = `${products.length} Products`;
                totalCostElement.textContent = totalCost.toFixed(2); // Set total cost
                reasonElement.textContent = reason; // Set reason
            }
        };
        xhr.send();
    });
</script>