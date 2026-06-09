<?php 
require_once __DIR__ . '/config.php';
include __DIR__ . '/header.php'; 
?>

<?php
// Get statistics
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalTransactions = $pdo->query("SELECT COUNT(*) FROM transactions")->fetchColumn();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(final_amount), 0) FROM transactions")->fetchColumn();
$lowStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock_quantity < 10")->fetchColumn();

// Recent transactions
$recentTransactions = $pdo->query("
    SELECT t.*, c.name as customer_name 
    FROM transactions t 
    LEFT JOIN customers c ON t.customer_id = c.id 
    ORDER BY t.created_at DESC 
    LIMIT 5
")->fetchAll();

// Low stock products
$lowStockProducts = $pdo->query("
    SELECT * FROM products 
    WHERE stock_quantity < 10 
    ORDER BY stock_quantity ASC 
    LIMIT 5
")->fetchAll();
?>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-box"></i></div>
        <div class="stat-info">
            <h3><?php echo $totalProducts; ?></h3>
            <p>Total Products</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-receipt"></i></div>
        <div class="stat-info">
            <h3><?php echo $totalTransactions; ?></h3>
            <p>Transactions</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-money-bill"></i></div>
        <div class="stat-info">
            <h3>TZS <?php echo number_format($totalRevenue, 2); ?></h3>
            <p>Total Revenue</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-info">
            <h3><?php echo $lowStock; ?></h3>
            <p>Low Stock Items</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-clock"></i> Recent Transactions</h2>
            <a href="transactions.php" class="btn btn-primary">View All</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentTransactions as $t): ?>
                <tr>
                    <td><?php echo htmlspecialchars($t['invoice_number']); ?></td>
                    <td><?php echo htmlspecialchars($t['customer_name'] ?? 'Walk-in'); ?></td>
                    <td>TZS <?php echo number_format($t['final_amount'], 2); ?></td>
                    <td><?php echo date('M d, Y', strtotime($t['created_at'])); ?></td>
                    <td><a href="invoice.php?id=<?php echo $t['id']; ?>" class="btn btn-primary" style="padding:0.4rem 0.8rem;font-size:0.85rem;"><i class="fas fa-eye"></i> View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-bell"></i> Low Stock Alert</h2>
        </div>
        <?php if (empty($lowStockProducts)): ?>
            <p style="text-align:center;color:#2e7d32;padding:2rem;"><i class="fas fa-check-circle"></i> All stock levels are healthy!</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Part</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lowStockProducts as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                        <td><span class="badge badge-danger"><?php echo $p['stock_quantity']; ?> left</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>