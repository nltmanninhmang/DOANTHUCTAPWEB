<?php
include '../../config.php'; 
$stmt = $pdo->prepare("SELECT * FROM sanpham");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../../include/admin/head.php'; ?>
    <?php include '../../include/admin/navbar.php'; ?>
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="header">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-end">
                        <div class="col">
                            <h6 class="header-pretitle">SNSTECHNOLOGY</h6>
                            <h1 class="header-title">Danh Sách Sản Phẩm</h1>
                        </div>
                        <div class="col-auto">
                            <a href="/" class="btn btn-primary lift">Quay về trang chủ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-lg-12 col-xl">
                    <div class="d-flex mb-3">
                        <a href="#" class="col-6 btn btn-primary" style="border-bottom-right-radius: 0px; border-top-right-radius: 0px;">Danh sách sản phẩm</a>
                        <a href="/admin/product/add" class="col-6 btn btn-light" style="border-bottom-left-radius: 0px; border-top-left-radius: 0px;">Thêm sản phẩm mới</a>
                    </div>
                    
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-sm table-nowrap card-table text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Danh mục</th>
                                        <th>Số lượng còn lại</th>
                                        <th>Giá</th>
                                        <th>Ảnh Sản Phẩm</th>
                                        <th colspan="2">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    <?php foreach ($products as $product): ?>
                                        <tr data-id="<?php echo htmlspecialchars($product['id']); ?>">
                                            <td class="orders-id"><?php echo htmlspecialchars($product['id']); ?></td>
                                            <td class="orders-name"><?php echo htmlspecialchars($product['name']); ?></td>
                                            <td class="orders-category"><?php echo htmlspecialchars($product['danhmuc']); ?></td>
                                            <td class="orders-total"><?php echo htmlspecialchars($product['amount']); ?></td>
                                            <td class="orders-price"><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</td>
                                            <td class="orders-image">
                                                <?php if (!empty($product['name_image'])): ?>
                                                    <img src="<?php echo htmlspecialchars($product['name_image']); ?>" alt="<?php echo htmlspecialchars($product['name'] ?? 'Product Image'); ?>" style="width: 70px;">
                                                <?php else: ?>
                                                    <span>No image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="orders-action">
                                                <a href="/admin/product/edit?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-info">Sửa</a>
                                                <button class="btn btn-danger" onclick="deleteProduct(<?php echo htmlspecialchars($product['id']); ?>)">Xoá</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include '../../include/admin/foot.php'; ?>

<script>
    function deleteProduct(productId) {
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa sản phẩm này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/api/product/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: productId }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Đã xóa',
                            text: 'Sản phẩm đã được xóa!',
                        }).then(() => {
                            location.reload(); 
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Có lỗi xảy ra',
                            text: data.message || 'Không thể xóa sản phẩm.',
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi kết nối',
                        text: 'Không thể kết nối tới server.',
                    });
                });
            }
        });
    }
</script>
</body>
</html>