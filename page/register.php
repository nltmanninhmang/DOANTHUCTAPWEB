<?php include '../include/head.php'; ?>

<body cz-shortcut-listen="true">
    <section class="profile-authentication-area">
        <div class="container">
            <div class="row justify-content-center align-items-center" style="height: 100vh;">
                <div class="col-lg-6 col-md-12">
                    <div class="login-form">
                        <h2>Đăng Ký</h2>
                        <form id="registrationForm">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" id="email" class="form-control" placeholder="Nhập email" required>
                            </div>

                            <div class="form-group">
                                <label>Họ và tên</label>
                                <input type="text" id="name" class="form-control" placeholder="Nhập họ và tên" required>
                            </div>

                            <div class="form-group">
                                <label>Mật khẩu</label>
                                <input type="password" id="password" class="form-control" placeholder="Nhập mật khẩu" required>
                            </div>

                            <button id="registerBtn" class="btn btn-primary">Đăng Ký</button>
                        </form>
                        <div class="text-center">Bạn đã có tài khoản? <a href="/auth/login" class="btn btn-primary">Đăng nhập ngay</a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const email = document.getElementById('email').value;
        const name = document.getElementById('name').value;
        const password = document.getElementById('password').value;

        if (!email || !name || !password) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Vui lòng điền đầy đủ thông tin!',
            });
            return;
        }

        fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, name, password }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thông báo',
                    text: 'Đăng ký tài khoản thành công!',
                }).then(() => {
                    window.location.href = '/auth/login'; 
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Đăng ký thất bại',
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