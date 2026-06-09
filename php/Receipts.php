<?php 
require_once __DIR__ . '/config.php';
include __DIR__ . '/header.php'; 
?>

<?php
if (!isset($_GET['id'])) {
    header('Location: transactions.php');
    exit;
}

$transactionId = $_GET['id'];

// Get transaction details
$stmt = $pdo->prepare("
    SELECT t.*, c.name as customer_name, c.phone as customer_phone, c.email as customer_email, c.address 
    FROM transactions t 
    LEFT JOIN customers c ON t.customer_id = c.id 
    WHERE t.id = ?
");
$stmt->execute([$transactionId]);
$transaction = $stmt->fetch();

if (!$transaction) {
    header('Location: transactions.php');
    exit;
}

// Get transaction items
$stmt = $pdo->prepare("
    SELECT ti.*, p.part_number, p.name as product_name 
    FROM transaction_items ti 
    JOIN products p ON ti.product_id = p.id 
    WHERE ti.transaction_id = ?
");
$stmt->execute([$transactionId]);
$items = $stmt->fetchAll();
?>

<style>
    @media print {
        .navbar, .no-print { display: none !important; }
        .container { margin: 0; padding: 0; }
        .invoice-box { box-shadow: none !important; }
    }
    
    .invoice-box {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        max-width: 800px;
        margin: 0 auto;
    }
    
    .invoice-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 3px solid #1a237e;
    }
    
    .company-info h2 {
        color: #1a237e;
        margin-bottom: 0.5rem;
    }
    
    .invoice-details {
        text-align: right;
    }
    
    .invoice-details h3 {
        color: #1a237e;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .customer-info {
        margin-bottom: 2rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .items-table {
        width: 100%;
        margin-bottom: 2rem;
    }
    
    .items-table th {
        background: #1a237e;
        color: white;
        padding: 0.8rem;
    }
    
    .items-table td {
        padding: 0.8rem;
        border-bottom: 1px solid #eee;
    }
    
    .totals {
        margin-left: auto;
        width: 300px;
    }
    
    .totals-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
    }
    
    .totals-row.final {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1a237e;
        border-top: 2px solid #1a237e;
        border-bottom: none;
        margin-top: 0.5rem;
        padding-top: 0.5rem;
    }
</style>

<div class="invoice-box">
    <div class="invoice-header">
        <div class="company-info">
            <h2><i class="fas fa-car"></i> AutoParts Pro</h2>
            <p>123 Auto Street, Motor City<br>
            Phone: (555) 123-4567<br>
            Email: sales@autopartspro.com</p>
        </div>
        <div class="invoice-details">
            <h3>INVOICE</h3>
            <p><strong><?php echo htmlspecialchars($transaction['invoice_number']); ?></strong><br>
            Date: <?php echo date('F d, Y', strtotime($transaction['created_at'])); ?><br>
            Time: <?php echo date('h:i A', strtotime($transaction['created_at'])); ?></p>
        </div>
    </div>
    
    <div class="customer-info">
        <h4 style="margin-bottom:0.5rem;color:#555;">Bill To:</h4>
        <?php if ($transaction['customer_name']): ?>
            <strong><?php echo htmlspecialchars($transaction['customer_name']); ?></strong><br>
            <?php if ($transaction['customer_phone']): ?>Phone: <?php echo htmlspecialchars($transaction['customer_phone']); ?><br><?php endif; ?>
            <?php if ($transaction['customer_email']): ?>Email: <?php echo htmlspecialchars($transaction['customer_email']); ?><br><?php endif; ?>
            <?php if ($transaction['address']): ?>Address: <?php echo htmlspecialchars($transaction['address']); ?><?php endif; ?>
        <?php else: ?>
            <span style="color:#999;">Walk-in Customer</span>
        <?php endif; ?>
    </div>
    
    <table class="items-table">
        <thead>
            <tr>
                <th style="text-align:left;">Item</th>
                <th>Part #</th>
                <th style="text-align:center;">Qty</th>
                <th style="text-align:right;">Unit Price</th>
                <th style="text-align:right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td><?php echo htmlspecialchars($item['part_number']); ?></td>
                <td style="text-align:center;"><?php echo $item['quantity']; ?></td>
                <td style="text-align:right;">TZS <?php echo number_format($item['unit_price'], 2); ?></td>
                <td style="text-align:right;">TZS <?php echo number_format($item['subtotal'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="totals">
        <div class="totals-row">
            <span>Subtotal:</span>
            <span>TZS <?php echo number_format($transaction['total_amount'], 2); ?></span>
        </div>
        <div class="totals-row">
            <span>Discount:</span>
            <span>-TZS <?php echo number_format($transaction['discount'], 2); ?></span>
        </div>
        <div class="totals-row final">
            <span>Total Amount:</span>
            <span>TZS <?php echo number_format($transaction['final_amount'], 2); ?></span>
        </div>
        <div class="totals-row" style="margin-top:0.5rem;color:#777;font-size:0.9rem;">
            <span>Payment Method:</span>
            <span><?php echo ucfirst(str_replace('_', ' ', $transaction['payment_method'])); ?></span>
        </div>
    </div>
    
    <?php if ($transaction['notes']): ?>
    <div style="margin-top:2rem;padding-top:1rem;border-top:1px solid #eee;">
        <strong>Notes:</strong><br>
        <?php echo nl2br(htmlspecialchars($transaction['notes'])); ?>
    </div>
    <?php endif; ?>
    
    <div style="margin-top:3rem;text-align:center;color:#777;font-size:0.9rem;">
        <p>Thank you for your business!</p>
    </div>
</div>

<div class="no-print" style="text-align:center;margin-top:2rem;">
    <button onclick="printInvoice()" class="btn btn-primary" style="margin-right:0.5rem;">
        <i class="fas fa-print"></i> Print Invoice
    </button>
    <a href="transactions.php" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back to Transactions
    </a>
</div>

<?php include __DIR__ . '/footer.php'; ?>