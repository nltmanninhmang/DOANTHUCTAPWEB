<?php
session_start();
include '../../config.php';

// Fetch all orders
$stmt = $pdo->prepare("SELECT * FROM orders ORDER BY created_at DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../../include/admin/head.php'; ?>
<body>
    <?php include '../../include/admin/navbar.php'; ?>
    <div class="main-content">
        <div class="header">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-end">
                        <div class="col">
                            <h6 class="header-pretitle">SNSTECHNOLOGY</h6>
                            <h1 class="header-title">Quản Lý Đơn Hàng</h1>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-header-title">Danh Sách Đơn Hàng</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User ID</th>
                                            <th>Khách hàng</th>
                                            <th>Số điện thoại</th>
                                            <th>Địa chỉ</th>
                                            <th>Email</th>
                                            <th>Phương thức</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Sản phẩm</th>
                                            <th>Thời gian</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($orders)): ?>
                                            <tr>
                                                <td colspan="12" class="text-center">Không có đơn hàng</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($order['user_id'] ?? 'Khách'); ?></td>
                                                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                                                    <td><?php echo htmlspecialchars($order['address']); ?></td>
                                                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($order['payment_method'] === 'cash' ? 'Tiền mặt' : 'QR'); ?></td>
                                                    <td><?php echo number_format($order['total'], 0, ',', '.') . ' VNĐ'; ?></td>
                                                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                                                    <td>
                                                        <?php
                                                        $items = json_decode($order['items'], true);
                                                        if ($items && is_array($items)) {
                                                            echo '<ul>';
                                                            foreach ($items as $item) {
                                                                echo '<li>' . htmlspecialchars($item['name']) . ' (x' . $item['quantity'] . ') - ' . number_format($item['price'] * $item['quantity'], 0, ',', '.') . ' VNĐ</li>';
                                                            }
                                                            echo '</ul>';
                                                        } else {
                                                            echo 'Không có sản phẩm';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm <?php echo $order['status'] === 'Đang xử lý' ? 'btn-primary' : 'btn-outline-primary'; ?>" onclick="updateStatus(<?php echo $order['id']; ?>, 'Đang xử lý')">Đang xử lý</button>
                                                            <button class="btn btn-sm <?php echo $order['status'] === 'Đang giao' ? 'btn-warning' : 'btn-outline-warning'; ?>" onclick="updateStatus(<?php echo $order['id']; ?>, 'Đang giao')">Đang giao</button>
                                                            <button class="btn btn-sm <?php echo $order['status'] === 'Thành công' ? 'btn-success' : 'btn-outline-success'; ?>" onclick="updateStatus(<?php echo $order['id']; ?>, 'Thành công')">Thành công</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js"></script>
    <script src="../../assets/js/vendor.bundle.js"></script>
    <script src="../../assets/js/theme.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        function updateStatus(orderId, status) {
            fetch('/api/cartdata', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `order_id=${orderId}&status=${encodeURIComponent(status)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Cập nhật trạng thái thành công'
                    }).then(() => {
                        window.location.reload(); 
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Lỗi kết nối server'
                });
            });
        }
    </script>
</body>
</html>