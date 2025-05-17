<?php
session_start();
include '../config.php';
include '../include/head.php';
?>

<body cz-shortcut-listen="true">
    <?php include '../include/tophead.php'; ?>
    <?php include '../include/topbar.php'; ?>
    <section class="qr-payment pt-70 pb-40" style="padding-top: 100px;">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2>Quét mã QR để thanh toán</h2>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=NLTM" alt="QR Code" style="max-width: 300px; margin: 20px auto;">
                    <p>Thời gian còn lại: <span id="countdown">5</span> giây</p>
                </div>
            </div>
        </div>
    </section>
    <?php include '../include/footer.php'; ?>
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

        function clearCart() {
            document.cookie = 'cart=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        }

        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        const interval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(interval);
                clearCart();
                Toast.fire({
                    icon: 'success',
                    title: 'Thanh toán thành công!'
                }).then(() => {
                    window.location.href = '/cart';
                });
            }
        }, 1000);
    </script>
</body>
</html>