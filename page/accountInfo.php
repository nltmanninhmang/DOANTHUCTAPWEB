<?php 
session_start();
include './../config.php'; 
include '../include/head.php'; 

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    try {
        // Lấy thông tin người dùng
        $stmt = $pdo->prepare("SELECT name, email, phone, address FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            throw new Exception("Không tìm thấy người dùng.");
        }

        // Lấy danh sách đơn hàng với phân trang
        $orders_per_page = 5;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $orders_per_page;

        // Đếm tổng số đơn hàng
        $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = :user_id");
        $count_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $count_stmt->execute();
        $total_orders = $count_stmt->fetchColumn();
        $total_pages = ceil($total_orders / $orders_per_page);

        // Lấy đơn hàng cho trang hiện tại
        $orders_stmt = $pdo->prepare("SELECT id, created_at, total, status FROM orders WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $orders_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $orders_stmt->bindParam(':limit', $orders_per_page, PDO::PARAM_INT);
        $orders_stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $orders_stmt->execute();
        $orders = $orders_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Lỗi truy vấn cơ sở dữ liệu: " . $e->getMessage());
    }
} else {
    header("Location: /auth/login");
    exit();
}
?>

<body cz-shortcut-listen="true">
    <?php include '../include/tophead.php'; ?>
    <?php include '../include/topbar.php'; ?>
    
    <section class="page-title-area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12">
                    <!-- Thông tin tài khoản -->
                    <div class="user-profile card p-4 shadow-sm mb-4">
                        <h3 class="mb-4 text-center">Thông tin tài khoản</h3>
                        <form id="updateProfileForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <input class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary col-12">Cập nhật</button>
                            </div>
                        </form>
                    </div>

                    <!-- Lịch sử đơn hàng -->
                    <div class="order-history card p-4 shadow-sm">
                        <h3 class="mb-4 text-center">Lịch sử đơn hàng</h3>
                        <?php if (empty($orders)): ?>
                            <p class="text-center">Bạn chưa có đơn hàng nào.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Mã đơn hàng</th>
                                            <th>Ngày đặt</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Chi tiết</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td>#SNS<?php echo htmlspecialchars($order['id']); ?></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                                <td><?php echo number_format($order['total'], 0, ',', '.'); ?> VNĐ</td>
                                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary view-order-details" data-order-id="<?php echo $order['id']; ?>" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">Xem chi tiết</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Phân trang -->
                            <?php if ($total_pages > 1): ?>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center mt-3">
                                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $page - 1; ?>">Trước</a>
                                        </li>
                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $page + 1; ?>">Sau</a>
                                        </li>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal chi tiết đơn hàng -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Chi tiết đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="order-details-content">
                        <p>Đang tải dữ liệu...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../include/footer.php'; ?>
    
    <!-- Thư viện -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/meanmenu/2.0.8/jquery.meanmenu.min.js"></script>

    <!-- JavaScript xử lý cập nhật thông tin và modal -->
    <script>
        $(document).ready(function() {
            // Xử lý form cập nhật thông tin
            $('#updateProfileForm').on('submit', function(e) {
                e.preventDefault();
                
                const formData = {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val(),
                    address: $('#address').val()
                };

                $.ajax({
                    url: '/api/account',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cập nhật thành công!',
                                text: 'Thông tin của bạn đã được cập nhật.',
                                timer: 2000,
                                timerProgressBar: true
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: response.message || 'Không thể cập nhật thông tin.'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Đã xảy ra lỗi khi gửi yêu cầu.'
                        });
                    }
                });
            });

            // Xử lý nút Xem chi tiết đơn hàng
            $('.view-order-details').on('click', function() {
                const orderId = $(this).data('order-id');
                $.ajax({
                    url: '/api/order',
                    method: 'GET',
                    data: { id: orderId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            const order = response.order;
                            const items = response.items;
                            let html = `
                                <h6>Mã đơn hàng: #SNS${order.id}</h6>
                                <p><strong>Ngày đặt:</strong> ${new Date(order.created_at).toLocaleString('vi-VN')}</p>
                                <p><strong>Tổng tiền:</strong> ${Number(order.total).toLocaleString('vi-VN')} VNĐ</p>
                                <p><strong>Trạng thái:</strong> ${order.status}</p>
                                <h6 class="mt-3">Sản phẩm</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tên sản phẩm</th>
                                                <th>Số lượng</th>
                                                <th>Giá</th>
                                                <th>Tổng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                            `;
                            items.forEach(item => {
                                html += `
                                    <tr>
                                        <td>${item.name}</td>
                                        <td>${item.quantity}</td>
                                        <td>${Number(item.price).toLocaleString('vi-VN')} VNĐ</td>
                                        <td>${Number(item.quantity * item.price).toLocaleString('vi-VN')} VNĐ</td>
                                    </tr>
                                `;
                            });
                            html += `
                                        </tbody>
                                    </table>
                                </div>
                            `;
                            $('#order-details-content').html(html);
                        } else {
                            $('#order-details-content').html(`<p class="text-danger">${response.message || 'Không thể tải chi tiết đơn hàng.'}</p>`);
                        }
                    },
                    error: function() {
                        $('#order-details-content').html('<p class="text-danger">Đã xảy ra lỗi khi tải chi tiết đơn hàng.</p>');
                    }
                });
            });

            // Logic Mean Menu
            $('.mean-menu').meanmenu({
                meanScreenWidth: "767",
                meanMenuContainer: '.drodo-responsive-menu',
                meanMenuClose: '<i class="bx bx-x"></i>',
                meanMenuOpen: '<i class="bx bx-menu"></i>',
                meanExpand: '<i class="bx bx-plus"></i>',
                meanContract: '<i class="bx bx-minus"></i>',
                meanRemoveAttrs: true
            });

            // Dropdown user trên mobile
            $('.user-dropdown').on('click', function(e) {
                e.preventDefault();
                $(this).next('.dropdown-menu').toggleClass('show');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('show');
                }
            });
        });
    </script>
</body>
</html>