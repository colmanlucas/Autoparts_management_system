<?php
require_once __DIR__ . '/config.php';
include __DIR__ . '/header.php';
?>

<?php
// Get all transactions with customer info
$transactions = $pdo->query("
    SELECT t.*, c.name as customer_name, c.phone as customer_phone,
           COUNT(ti.id) as item_count
    FROM transactions t 
    LEFT JOIN customers c ON t.customer_id = c.id 
    LEFT JOIN transaction_items ti ON t.id = ti.transaction_id
    GROUP BY t.id
    ORDER BY t.created_at DESC
")->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-receipt"></i> All Transactions</h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Discount</th>
                <th>Final Amount</th>
                <th>Payment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $t): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($t['invoice_number']); ?></strong></td>
                <td><?php echo date('M d, Y H:i', strtotime($t['created_at'])); ?></td>
                <td>
                    <?php if ($t['customer_name']): ?>
                        <?php echo htmlspecialchars($t['customer_name']); ?><br>
                        <small style="color:#777;"><?php echo htmlspecialchars($t['customer_phone']); ?></small>
                    <?php else: ?>
                        <span style="color:#999;">Walk-in</span>
                    <?php endif; ?>
                </td>
                <td><?php echo $t['item_count']; ?></td>
                <td>TZS <?php echo number_format($t['total_amount'], 2); ?></td>
                <td>TZS <?php echo number_format($t['discount'], 2); ?></td>
                <td><strong style="color:#1a237e;">TZS <?php echo number_format($t['final_amount'], 2); ?></strong></td>
                <td>
                    <span class="badge <?php echo $t['payment_method'] == 'cash' ? 'badge-success' : ($t['payment_method'] == 'card' ? 'badge-warning' : 'badge-primary'); ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $t['payment_method'])); ?>
                    </span>
                </td>
                <td>
                    <a href="invoice.php?id=<?php echo $t['id']; ?>" class="btn btn-primary" style="padding:0.4rem 0.8rem;font-size:0.85rem;">
                        <i class="fas fa-eye"></i> View
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>