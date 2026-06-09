<?php
require_once __DIR__ . '/config.php';
include __DIR__ . '/header.php';
?>

<?php
// Handle sale submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_sale'])) {
    $cartData = json_decode($_POST['cart_data'], true);
    
    if (empty($cartData)) {
        $_SESSION['error'] = 'Cart is empty!';
        header('Location: sell.php');
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        
        // Generate invoice number
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        
        // Calculate totals
        $totalAmount = 0;
        foreach ($cartData as $item) {
            $totalAmount += $item['subtotal'];
        }
        
        $discount = floatval($_POST['discount'] ?? 0);
        $finalAmount = $totalAmount - $discount;
        
        // Create customer if new
        $customerId = null;
        if (!empty($_POST['customer_name'])) {
            $stmt = $pdo->prepare("INSERT INTO customers (name, phone, email) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['customer_name'], $_POST['customer_phone'], $_POST['customer_email']]);
            $customerId = $pdo->lastInsertId();
        }
        
        // Create transaction
        $stmt = $pdo->prepare("INSERT INTO transactions (invoice_number, customer_id, total_amount, discount, final_amount, payment_method, notes) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $invoiceNumber,
            $customerId,
            $totalAmount,
            $discount,
            $finalAmount,
            $_POST['payment_method'],
            $_POST['notes']
        ]);
        
        $transactionId = $pdo->lastInsertId();
        
        // Add transaction items and update stock
        foreach ($cartData as $item) {
            $stmt = $pdo->prepare("INSERT INTO transaction_items (transaction_id, product_id, quantity, unit_price, subtotal) 
                                  VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $transactionId,
                $item['id'],
                $item['quantity'],
                $item['price'],
                $item['subtotal']
            ]);
            
            // Update stock
            $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
            $stmt->execute([$item['quantity'], $item['id']]);
        }
        
        $pdo->commit();
        $_SESSION['message'] = 'Sale completed successfully! Invoice: ' . $invoiceNumber;
        header('Location: invoice.php?id=' . $transactionId);
        exit;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = 'Error processing sale: ' . $e->getMessage();
        header('Location: sell.php');
        exit;
    }
}

// Get all products for selling
$products = $pdo->query("SELECT * FROM products WHERE stock_quantity > 0 ORDER BY name")->fetchAll();
?>

<?php if (isset($_SESSION['message'])): ?>
<div class="alert alert-success">
    <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-error">
    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
    <!-- Mobile/Tablet: Stack vertically; Desktop: 2fr 1fr -->
    <style>
        @media (min-width: 1024px) {
            .sell-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
        }
    </style>
    <div class="sell-layout">
            <div class="search-box">
                <input type="text" id="search-input" placeholder="Search parts by name, number, or category..." onkeyup="searchProducts()">
            </div>
            
            <div class="grid" id="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="part-card">
                <div class="part-header">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="part-number"><?php echo htmlspecialchars($product['part_number']); ?></div>
                </div>
                <div class="part-body">
                    <div class="part-info">
                        <span class="label">Category</span>
                        <span class="value"><?php echo htmlspecialchars($product['category']); ?></span>
                    </div>
                    <div class="part-info">
                        <span class="label">Stock</span>
                        <span class="value"><?php echo $product['stock_quantity']; ?> available</span>
                    </div>
                    <div class="part-info">
                        <span class="label">Price</span>
                        <span class="price">TZS <?php echo number_format($product['price'], 2); ?></span>
                    </div>
                    <?php if ($product['description']): ?>
                    <div style="font-size:0.85rem;color:#777;margin-top:0.5rem;">
                        <?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="part-footer">
                    <button class="btn btn-success" style="width:100%;" 
                            onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars(addslashes($product['name']), ENT_QUOTES); ?>', <?php echo $product['price']; ?>, <?php echo $product['stock_quantity']; ?>)">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Cart Section -->
        <div class="cart-section">
        <h2 style="margin-bottom:1.5rem;color:#1a237e;"><i class="fas fa-shopping-cart"></i> Current Sale</h2>
        
        <div id="cart-items">
            <p style="text-align:center;color:#999;padding:2rem 0;">Cart is empty</p>
        </div>
        
        <div class="cart-total">
            <span>Total:</span>
            <span id="cart-total">TZS 0.00</span>
        </div>
        
        <form method="POST" action="" style="margin-top:1.5rem;" onsubmit="return validateCart()">
            <input type="hidden" name="cart_data" id="cart-data" value="">
            
            <div class="form-group">
                <label>Customer Name (optional)</label>
                <input type="text" name="customer_name" class="form-control" placeholder="Walk-in customer">
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem;">
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="customer_phone" class="form-control">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="customer_email" class="form-control">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem;">
                <div class="form-group">
                    <label>Discount ($)</label>
                    <input type="number" step="0.01" name="discount" class="form-control" value="0" onchange="updateCartUI()">
                </div>
                <div class="form-group">
                    <label>Payment</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="2"></textarea>
            </div>
            
            <button type="submit" name="process_sale" id="checkout-btn" class="btn btn-success" style="width:100%;padding:1rem;" disabled>
                <i class="fas fa-check"></i> Complete Sale
            </button>
        </form>
    </div>
</div>

<script>
function validateCart() {
    const cartData = document.getElementById('cart-data').value;
    if (!cartData || cartData === '[]') {
        alert('Please add items to cart first!');
        return false;
    }
    return true;
}
</script>

<?php include 'footer.php'; ?>