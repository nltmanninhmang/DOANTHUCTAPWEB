<?php
include '../../include/admin/head.php';
include '../../config.php';

// Fetch existing settings
try {
    $stmt = $pdo->query("SELECT website_name, phone, address, email, logo FROM settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $settings = null;
    // Optionally log the error
    error_log("Error fetching settings: " . $e->getMessage());
}
?>

<body>
    <?php include '../../include/admin/navbar.php'; ?>
    <div class="main-content">
        <div class="header">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-end">
                        <div class="col">
                            <h6 class="header-pretitle">SNSTECHNOLOGY</h6>
                            <h1 class="header-title">Cài đặt</h1>
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
                    <form id="settings-form" enctype="multipart/form-data" class="mb-4">
                        <div class="form-group">
                            <label class="form-label">Tên website</label>
                            <input type="text" class="form-control" name="website_name" placeholder="Tên website" value="<?php echo isset($settings['website_name']) ? htmlspecialchars($settings['website_name']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" name="phone" placeholder="Số điện thoại" pattern="[0-9]{10,11}" value="<?php echo isset($settings['phone']) ? htmlspecialchars($settings['phone']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" name="address" placeholder="Địa chỉ" value="<?php echo isset($settings['address']) ? htmlspecialchars($settings['address']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo isset($settings['email']) ? htmlspecialchars($settings['email']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Logo</label>
                            <input type="file" class="form-control" name="logo" accept="image/*">
                            <img id="logo-preview" src="<?php echo isset($settings['logo']) && $settings['logo'] ? htmlspecialchars($settings['logo']) : ''; ?>" alt="Logo Preview" style="max-width: 200px; margin-top: 10px; display: <?php echo isset($settings['logo']) && $settings['logo'] ? 'block' : 'none'; ?>;">
                        </div>
                        <button type="submit" class="btn w-100 btn-primary">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert Mixin for notifications
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

        // Logo preview
        document.querySelector('input[name="logo"]').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('logo-preview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                // Revert to existing logo or hide
                preview.src = '<?php echo isset($settings['logo']) && $settings['logo'] ? htmlspecialchars($settings['logo']) : ''; ?>';
                preview.style.display = '<?php echo isset($settings['logo']) && $settings['logo'] ? 'block' : 'none'; ?>';
            }
        });

        // Form submission
        document.getElementById('settings-form').addEventListener('submit', async function(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            // Client-side validation
            const websiteName = formData.get('website_name').trim();
            const phone = formData.get('phone').trim();
            const address = formData.get('address').trim();
            const email = formData.get('email').trim();
            const logo = formData.get('logo');

            if (!websiteName || !phone || !address || !email) {
                Toast.fire({
                    icon: 'error',
                    title: 'Vui lòng điền đầy đủ các trường bắt buộc'
                });
                return;
            }

            if (!/^[0-9]{10,11}$/.test(phone)) {
                Toast.fire({
                    icon: 'error',
                    title: 'Số điện thoại phải có 10-11 chữ số'
                });
                return;
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                Toast.fire({
                    icon: 'error',
                    title: 'Email không hợp lệ'
                });
                return;
            }

            try {
                const response = await fetch('/api/setting', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Lưu cài đặt thành công'
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: result.message || 'Lưu cài đặt thất bại'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Đã xảy ra lỗi khi lưu cài đặt'
                });
            }
        });
    </script>
</body>
</html>