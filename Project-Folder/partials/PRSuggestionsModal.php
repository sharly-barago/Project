<div class="modal fade" id="PRSuggestions" tabindex="-1" role="dialog" aria-labelledby="PRSuggestionsModals" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header mx-2">
                <h5 class="modal-title" id="PRSuggestionsLabel">PR Suggestions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                include('database/connect.php');

                $stmt = $conn->prepare("SELECT * FROM item WHERE quantity < minStockLevel ORDER BY quantity, minStockLevel ASC;");
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover table-striped border">
                        <thead class="bg-white">
                            <tr class="userAdd sticky-top">
                                <th>Item Name</th>
                                <th>Unit of Measure</th>
                                <th>Item Type</th>
                                <th>Quantity</th>
                                <th>Min Stock Level</th>
                                <th>Item Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $index = 0;
                            foreach ($products as $product) { ?>
                                <tr>
                                    <td class="pt-3"><?= htmlspecialchars($product['itemName']) ?></td>
                                    <td class="pt-3"><?= htmlspecialchars($product['unitOfMeasure']) ?></td>
                                    <td class="pt-3"><?= htmlspecialchars($product['itemType']) ?></td>
                                    <td class="pt-3"><?= htmlspecialchars($product['quantity']) ?></td>
                                    <td class="pt-3"><?= htmlspecialchars($product['minStockLevel']) ?></td>
                                    <td class="pt-3"><?= htmlspecialchars($product['itemStatus']) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted mt-0 mb-0"><?= count($products) ?> Products</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>