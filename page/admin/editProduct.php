<?php
include '../../config.php';

if (!isset($_GET['id'])) {
    header('Location: /admin/product');
    exit;
}

$productId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM sanpham WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: /admin/product');
    exit;
}
?>

<?php include '../../include/admin/head.php'; ?>
<?php include '../../include/admin/navbar.php'; ?>

<div class="main-content">
    <div class="header">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-end">
                    <div class="col">
                        <h6 class="header-pretitle">SNSTECHNOLOGY</h6>
                        <h1 class="header-title">Sửa Sản Phẩm</h1>
                    </div>
                    <div class="col-auto">
                        <a href="/admin/product" class="btn btn-primary lift">Quay về danh sách</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="error-message" class="alert alert-danger" style="display: none;"></div>
                        <form id="product-form" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên sản phẩm</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Danh mục</label>
                                <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($product['danhmuc']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Số lượng</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="<?php echo htmlspecialchars($product['amount']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Giá (VNĐ)</label>
                                <input type="text" class="form-control" id="price" name="price" value="<?php echo number_format($product['price'], 0, ',', '.'); ?>" required>
                                <small class="form-text text-muted">Nhập giá bằng số, ví dụ: 10.000.000</small>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Ảnh sản phẩm</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <img src="<?php echo htmlspecialchars($product['name_image']); ?>" alt="Current Image" style="width: 100px; margin-top: 10px;">
                            </div>
                            <input type="hidden" name="id" value="<?php echo $productId; ?>">
                            <button type="submit" class="btn btn-primary col-md-12">Lưu thay đổi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../include/admin/foot.php'; ?>

<script>
// Hàm định dạng giá theo kiểu VNĐ
function formatVND(price) {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' VNĐ';
}

// Xử lý định dạng giá khi nhập
document.getElementById('price').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, ''); // Chỉ giữ số
    if (value) {
        e.target.value = parseInt(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
});

document.getElementById('product-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const form = e.target;
    const errorMessage = document.getElementById('error-message');
    const formData = new FormData(form);
    
    // Chuyển đổi giá từ định dạng VNĐ sang số
    const priceInput = form.price.value.replace(/\./g, '');
    formData.set('price', priceInput);

    try {
        const response = await fetch('/api/product/edit', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (response.ok && data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: `Sản phẩm đã được cập nhật!`,
            }).then(() => {
                window.location.href = '/admin/product?success=1';
            });
        } else {
            errorMessage.style.display = 'block';
            errorMessage.textContent = data.message || 'Có lỗi xảy ra khi cập nhật sản phẩm';
        }
    } catch (error) {
        errorMessage.style.display = 'block';
        errorMessage.textContent = 'Lỗi kết nối: ' + error.message;
    }
});
</script>

</body>
</html>