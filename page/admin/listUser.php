<?php
include '../../config.php'; 
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.css">
    <link rel="stylesheet" href="../assets/css/libs.bundle.css">
    <link rel="stylesheet" href="../assets/css/theme.bundle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>SNSTECH Quản Trị</title>
</head>
<body>
    <?php include '../../include/admin/navbar.php'; ?>
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="header">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-end">
                        <div class="col">
                            <h6 class="header-pretitle">SNSTECHNOLOGY</h6>
                            <h1 class="header-title">Danh Sách Người Dùng</h1>
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
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-sm table-nowrap card-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Cấp bậc</th>
                                        <th>Ngày tạo</th>
                                        <th colspan="2">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    <?php foreach ($products as $product): ?>
                                        <tr data-id="<?php echo htmlspecialchars($product['id']); ?>">
                                            <td class="orders-id"><?php echo htmlspecialchars($product['id']); ?></td>
                                            <td class="orders-name"><?php echo htmlspecialchars($product['name']); ?></td>
                                            <td class="orders-email"><?php echo htmlspecialchars($product['email']); ?></td>
                                            <td class="orders-level">
                                                <?php 
                                                if ($product['level'] == 1) {
                                                    echo 'Thành viên';
                                                } elseif ($product['level'] == 2) {
                                                    echo 'Quản trị viên';
                                                } else {
                                                    echo 'Không xác định'; 
                                                }
                                                ?>
                                            </td>
                                            <td class="orders-price"><?php echo htmlspecialchars($product['created_at']); ?></td>
                                            <td class="orders-action">
                                                <button class="btn btn-info" onclick="openEditModal(<?php echo htmlspecialchars($product['id']); ?>)">Sửa</button>
                                                <button class="btn btn-danger" onclick="deleteUser(<?php echo htmlspecialchars($product['id']); ?>)">Xoá</button>
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
    </div><!-- / .main-content -->

    <!-- Modal for editing user -->
    <div class="modal fade" id="editUser -Modal" tabindex="-1" aria-labelledby="editUser -ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUser -ModalLabel">Sửa người dùng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUser Form">
                        <input type="hidden" id="editUser -Id">
                        <div class="mb-3">
                            <label for="editUser -Name" class="form-label">Tên</label>
                            <input type="text" class="form-control" id="editUser -Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUser -Email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editUser -Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUser -Level" class="form-label">Cấp bậc</label>
                            <select class="form-select" id="editUser -Level" required>
                                <option value="1">Thành viên</option>
                                <option value="2">Quản trị viên</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary col-md-12">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src='../api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js'></script>
    <script src="../../assets/js/vendor.bundle.js"></script>
    <script src="../../assets/js/theme.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

    <script>
        function openEditModal(userId) {
            const row = document.querySelector(`tr[data-id='${userId}']`);
            const name = row.querySelector('.orders-name').innerText;
            const email = row.querySelector('.orders-email').innerText;
            const level = row.querySelector('.orders-level').innerText === 'Thành viên' ? 1 : 2;

            document.getElementById('editUser -Id').value = userId;
            document.getElementById('editUser -Name').value = name;
            document.getElementById('editUser -Email').value = email;
            document.getElementById('editUser -Level').value = level;

            const modal = new bootstrap.Modal(document.getElementById('editUser -Modal'));
            modal.show();
        }

        document.getElementById('editUser Form').addEventListener('submit', function(event) {
            event.preventDefault();
            const userId = document.getElementById('editUser -Id').value;
            const name = document.getElementById('editUser -Name').value;
            const email = document.getElementById('editUser -Email').value;
            const level = document.getElementById('editUser -Level').value;

            fetch('/api/edituser', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: userId, name, email, level }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: 'Người dùng đã được cập nhật!',
                    }).then(() => {
                        location.reload(); 
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Có lỗi xảy ra',
                        text: 'Không thể sửa người dùng.',
                    });
                }
            });
        });

        function deleteUser (userId) {
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa người dùng này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/api/deleteuser', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: userId }) // Gửi ID trong body
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Đã xóa!', 'Người dùng đã được xóa.', 'success').then(() => {
                                location.reload(); 
                            });
                        } else {
                            Swal.fire('Có lỗi xảy ra', data.message, 'error');
                        }
                    });
                }
            });
        }
    </script>

</body>
</html>