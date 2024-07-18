<div class="modal fade" id="PRItemCosts" tabindex="-1" role="dialog" aria-labelledby="PRItemCostsModals" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header mx-2">
                <h5 class="modal-title" id="PRItemCostsLabel">Item Costs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                include('database/connect.php');

                $stmt = $conn->prepare("SELECT item.itemName, supplier.companyName, item_costs.cost FROM item, supplier, item_costs WHERE item_costs.itemID = item.itemID AND item_costs.supplierID = supplier.supplierID AND supplier.status = 'active' ORDER BY item.itemName, item_costs.cost ASC;");
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Group the products by itemName
                $groupedProducts = [];
                foreach ($products as $product) {
                    $itemName = $product['itemName'];
                    if (!isset($groupedProducts[$itemName])) {
                        $groupedProducts[$itemName] = [];
                    }
                    $groupedProducts[$itemName][] = $product;
                }
                ?>

                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover table-striped border">
                        <thead class="bg-white">
                            <tr class="userAdd sticky-top">
                                <th>Item Name</th>
                                <th>Supplier</th>
                                <th>Costs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groupedProducts as $itemName => $products) { ?>
                                <tr>
                                    <td class="pt-3" rowspan="<?= count($products) ?>"><?= htmlspecialchars($itemName) ?></td>
                                    <td class="pt-3"><?= htmlspecialchars($products[0]['companyName']) ?></td>
                                    <td class="pt-3">₱<?= htmlspecialchars($products[0]['cost']) ?></td>
                                </tr>
                                <?php for ($i = 1; $i < count($products); $i++) { ?>
                                    <tr>
                                        <td class="pt-3"><?= htmlspecialchars($products[$i]['companyName']) ?></td>
                                        <td class="pt-3">₱<?= htmlspecialchars($products[$i]['cost']) ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted mt-0 mb-0"><?= count($groupedProducts) ?> Products</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>