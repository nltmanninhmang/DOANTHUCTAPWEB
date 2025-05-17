<?php include '../include/head.php'; ?>

<body cz-shortcut-listen="true">
    <section class="profile-authentication-area">
        <div class="container">
            <div class="row justify-content-center align-items-center" style="height: 100vh;">
                <div class="col-lg-6 col-md-12">
                    <div class="login-form">
                        <h2>Đăng Nhập</h2>

                        <form id="loginForm">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" id="email" class="form-control" placeholder="Nhập email" required>
                            </div>

                            <div class="form-group">
                                <label>Mật khẩu</label>
                                <input type="password" id="password" class="form-control" placeholder="Nhập mật khẩu" required>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-lg-6 col-md-6 col-sm-6 remember-me-wrap">
                                    <p>
                                        <input type="checkbox" id="rememberMe">
                                        <label for="rememberMe">Ghi nhớ đăng nhập</label>
                                    </p>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 lost-your-password-wrap">
                                    <a href="#" class="lost-your-password">Quên mật khẩu?</a>
                                </div>
                            </div>

                            <button type="submit" id="loginBtn" class="btn btn-primary">Đăng Nhập</button>
                        </form>
                        <div class="text-center">Bạn chưa có tài khoản? <a href="/auth/register" class="btn btn-primary">Đăng ký ngay</a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            // Ngăn chặn hành động gửi form mặc định
            event.preventDefault();

            // Lấy dữ liệu từ các trường nhập liệu
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Kiểm tra xem các trường có rỗng không
            if (!email || !password) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Vui lòng điền đầy đủ thông tin!',
                });
                return;
            }

            // Gửi dữ liệu đến server bằng AJAX
            fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đăng nhập thành công',
                        text: 'Chào mừng bạn trở lại!',
                    }).then(() => {
                        window.location.href = '/'; // Chuyển hướng đến trang dashboard hoặc trang chính
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Đăng nhập thất bại',
                        text: data.message,
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra, vui lòng thử lại!',
                });
            });
        });
    </script>
</body>
</html>