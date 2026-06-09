<?php
require_once __DIR__ . '/config.php';
include __DIR__ . '/header.php';
?>

<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $stmt = $pdo->prepare("INSERT INTO products (part_number, name, category, description, price, stock_quantity, supplier) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['part_number'],
            $_POST['name'],
            $_POST['category'],
            $_POST['description'],
            $_POST['price'],
            $_POST['stock_quantity'],
            $_POST['supplier']
        ]);
        $_SESSION['message'] = 'Product added successfully!';
        header('Location: products.php');
        exit;
    }
    
    if (isset($_POST['update_product'])) {
        $stmt = $pdo->prepare("UPDATE products SET part_number=?, name=?, category=?, description=?, price=?, stock_quantity=?, supplier=? WHERE id=?");
        $stmt->execute([
            $_POST['part_number'],
            $_POST['name'],
            $_POST['category'],
            $_POST['description'],
            $_POST['price'],
            $_POST['stock_quantity'],
            $_POST['supplier'],
            $_POST['id']
        ]);
        $_SESSION['message'] = 'Product updated successfully!';
        header('Location: products.php');
        exit;
    }
    
    if (isset($_POST['delete_product'])) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $_SESSION['message'] = 'Product deleted successfully!';
        header('Location: products.php');
        exit;
    }
}

// Get all products
$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();

// Get product for edit
$editProduct = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editProduct = $stmt->fetch();
}
?>

<?php if (isset($_SESSION['message'])): ?>
<div class="alert alert-success">
    <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-box"></i> <?php echo $editProduct ? 'Edit Product' : 'Add New Product'; ?></h2>
    </div>
    <form method="POST" action="">
        <?php if ($editProduct): ?>
            <input type="hidden" name="id" value="<?php echo $editProduct['id']; ?>">
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
            <div class="form-group">
                <label>Part Number</label>
                <input type="text" name="part_number" class="form-control" required 
                       value="<?php echo $editProduct['part_number'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" class="form-control" required 
                       value="<?php echo $editProduct['name'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <option value="Brakes" <?php echo ($editProduct['category'] ?? '') == 'Brakes' ? 'selected' : ''; ?>>Brakes</option>
                    <option value="Engine" <?php echo ($editProduct['category'] ?? '') == 'Engine' ? 'selected' : ''; ?>>Engine</option>
                    <option value="Electrical" <?php echo ($editProduct['category'] ?? '') == 'Electrical' ? 'selected' : ''; ?>>Electrical</option>
                    <option value="Filters" <?php echo ($editProduct['category'] ?? '') == 'Filters' ? 'selected' : ''; ?>>Filters</option>
                    <option value="Fluids" <?php echo ($editProduct['category'] ?? '') == 'Fluids' ? 'selected' : ''; ?>>Fluids</option>
                    <option value="Ignition" <?php echo ($editProduct['category'] ?? '') == 'Ignition' ? 'selected' : ''; ?>>Ignition</option>
                    <option value="Suspension" <?php echo ($editProduct['category'] ?? '') == 'Suspension' ? 'selected' : ''; ?>>Suspension</option>
                    <option value="Other" <?php echo ($editProduct['category'] ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Price ($)</label>
                <input type="number" step="0.01" name="price" class="form-control" required 
                       value="<?php echo $editProduct['price'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" name="stock_quantity" class="form-control" required 
                       value="<?php echo $editProduct['stock_quantity'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label>Supplier</label>
                <input type="text" name="supplier" class="form-control" 
                       value="<?php echo $editProduct['supplier'] ?? ''; ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"><?php echo $editProduct['description'] ?? ''; ?></textarea>
        </div>
        
        <?php if ($editProduct): ?>
            <button type="submit" name="update_product" class="btn btn-success"><i class="fas fa-save"></i> Update Product</button>
            <a href="products.php" class="btn btn-primary">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add_product" class="btn btn-success"><i class="fas fa-plus"></i> Add Product</button>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-list"></i> Product Inventory</h2>
        <div class="search-box" style="margin:0;">
            <input type="text" id="search-input" placeholder="Search products..." onkeyup="searchProducts()">
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Part #</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Supplier</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($product['part_number']); ?></strong></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['category']); ?></td>
                <td>TZS <?php echo number_format($product['price'], 2); ?></td>
                <td>
                    <?php if ($product['stock_quantity'] < 10): ?>
                        <span class="badge badge-danger"><?php echo $product['stock_quantity']; ?></span>
                    <?php elseif ($product['stock_quantity'] < 25): ?>
                        <span class="badge badge-warning"><?php echo $product['stock_quantity']; ?></span>
                    <?php else: ?>
                        <span class="badge badge-success"><?php echo $product['stock_quantity']; ?></span>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($product['supplier'] ?? '-'); ?></td>
                <td>
                    <a href="?edit=<?php echo $product['id']; ?>" class="btn btn-warning" style="padding:0.4rem 0.8rem;font-size:0.85rem;"><i class="fas fa-edit"></i></a>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="delete_product" class="btn btn-danger" style="padding:0.4rem 0.8rem;font-size:0.85rem;"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>