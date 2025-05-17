<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.css">
    <link rel="stylesheet" href="../assets/css/libs.bundle.css">
    <link rel="stylesheet" href="../assets/css/theme.bundle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>SNSTECH Quản Trị</title>
</head>
<body>
    <?php include '../../include/admin/navbar.php'; ?>
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- HEADER -->
        <div class="header">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-end">
                        <div class="col">
                            <h6 class="header-pretitle">SNSTECHNOLOGY</h6>
                            <h1 class="header-title">Trang Quản Trị</h1>
                        </div>
                        <div class="col-auto">
                            <a href="/" class="btn btn-primary lift">Quay về trang chủ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARDS -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-lg-6 col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center gx-0">
                                <div class="col">
                                    <h6 class="text-uppercase text-body-secondary mb-2">Doanh thu tháng này</h6>
                                    <span class="h2 mb-0" id="monthly-revenue">0 VNĐ</span>
                                </div>
                                <div class="col-auto">
                                    <span class="h2 fe fe-dollar-sign text-body-secondary mb-0"></span>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center gx-0">
                                <div class="col">
                                    <h6 class="text-uppercase text-body-secondary mb-2">Tổng số người dùng</h6>
                                    <span class="h2 mb-0" id="total-users">0 Người dùng</span>
                                </div>
                                <div class="col-auto">
                                    <span class="h2 fe fe-user text-body-secondary mb-0"></span>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center gx-0">
                                <div class="col">
                                    <h6 class="text-uppercase text-body-secondary mb-2">Sản phẩm hiện có</h6>
                                    <span class="h2 mb-0" id="total-products">0 Sản phẩm</span>
                                </div>
                                <div class="col-auto">
                                    <span class="h2 fe fe-shopping-bag text-body-secondary mb-0"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center gx-0">
                                <div class="col">
                                    <h6 class="text-uppercase text-body-secondary mb-2">Đơn hàng hôm nay</h6>
                                    <span class="h2 mb-0" id="total-orders-today">0 Đơn hàng</span>
                                </div>
                                <div class="col-auto">
                                    <span class="h2 fe fe-shopping-cart text-body-secondary mb-0"></span>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-header-title">Danh sách đơn hàng hôm nay</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Khách hàng</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Thời gian</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orders-table-body">
                                        <tr>
                                            <td colspan="5" class="text-center">Đang tải...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="../api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js"></script>
    <script src="assets/js/vendor.bundle.js"></script>
    <script src="assets/js/theme.bundle.js"></script>
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
                toast.addEventListener('mouseleave', Swal.resume189Timer);
            }
        });

        function formatCurrency(amount) {
            return Number(amount).toLocaleString('vi-VN') + ' VNĐ';
        }

        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function renderStats(data) {
            document.getElementById('monthly-revenue').textContent = formatCurrency(data.monthlyRevenue);
            document.getElementById('total-users').textContent = `${data.totalUsers} Người dùng`;
            document.getElementById('total-products').textContent = `${data.totalProducts} Sản phẩm`;
            document.getElementById('total-orders-today').textContent = `${data.totalOrdersToday} Đơn hàng`;

            const tbody = document.getElementById('orders-table-body');
            tbody.innerHTML = '';
            if (data.ordersToday.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">Không có đơn hàng hôm nay</td></tr>';
            } else {
                data.ordersToday.forEach(order => {
                    const row = `
                        <tr>
                            <td>${order.id}</td>
                            <td>${order.name}</td>
                            <td>${formatCurrency(order.total)}</td>
                            <td>${order.status}</td>
                            <td>${formatDateTime(order.created_at)}</td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetch('/api/data')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderStats(data.data);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Lỗi tải dữ liệu',
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
        });
    </script>
</body>
</html>