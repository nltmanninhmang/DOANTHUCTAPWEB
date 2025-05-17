<?php 
session_start();
include '../config.php';
include './../include/head.php'; 
?>

<body cz-shortcut-listen="true">
    <?php include '../include/tophead.php'; ?>
    <?php include '../include/topbar.php'; ?>
    <section class="cart-area pt-70 pb-40" style="padding-top: 100px;"> 
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="cart-container bg-white" style="background-color: #ffffff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <div id="cart-items" class="table-responsive" style="min-height: 200px;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Hình ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-table-body"></tbody>
                            </table>
                        </div>
                        <div class="mt-4" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: nowrap; gap: 1rem;">
                            <h4 style="margin: 0; white-space: nowrap;">Tổng cộng: <span id="cart-total">0 VNĐ</span></h4>
                            <button class="btn btn-primary" style="padding: 10px 20px; white-space: nowrap;" onclick="proceedToCheckout()">Thanh toán</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include './../include/footer.php'; ?>
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

        function getCart() {
            const cart = document.cookie.split('; ').find(row => row.startsWith('cart='));
            return cart ? JSON.parse(decodeURIComponent(cart.split('=')[1])) : [];
        }

        function saveCart(cart) {
            const expires = new Date();
            expires.setDate(expires.getDate() + 30); 
            document.cookie = `cart=${encodeURIComponent(JSON.stringify(cart))}; expires=${expires.toUTCString()}; path=/`;
            updateCartCount();
            renderCart();
        }

        function updateCartCount() {
            const cart = getCart();
            const count = cart.reduce((total, item) => total + item.quantity, 0);
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
            }
        }

        function renderCart() {
            const cart = getCart();
            const tbody = document.getElementById('cart-table-body');
            tbody.innerHTML = '';
            let total = 0;

            if (cart.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">Giỏ hàng trống</td></tr>';
            } else {
                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;
                    const row = `
                        <tr>
                            <td><img src="${item.name_image}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: cover;"></td>
                            <td>${item.name}</td>
                            <td>${Number(item.price).toLocaleString('vi-VN')} VNĐ</td>
                            <td>
                                <input type="number" class="form-control" value="${item.quantity}" min="1" style="width: 60px;" onchange="updateQuantity(${item.id}, this.value)">
                            </td>
                            <td>${Number(itemTotal).toLocaleString('vi-VN')} VNĐ</td>
                            <td><button class="btn btn-danger" onclick="removeFromCart(${item.id})">Xóa</button></td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            }

            document.getElementById('cart-total').textContent = `${Number(total).toLocaleString('vi-VN')} VNĐ`;
        }

        function updateQuantity(productId, quantity) {
            const cart = getCart();
            const item = cart.find(item => item.id === productId);
            if (item && quantity >= 1) {
                item.quantity = parseInt(quantity);
                saveCart(cart);
            } else if (quantity < 1) {
                removeFromCart(productId);
            }
        }

        function removeFromCart(productId) {
            const cart = getCart();
            const updatedCart = cart.filter(item => item.id !== productId);
            saveCart(updatedCart);
            Toast.fire({
                icon: 'success',
                title: 'Đã xóa sản phẩm khỏi giỏ hàng'
            });
        }

        function proceedToCheckout() {
            const cart = getCart();
            if (cart.length === 0) {
                Toast.fire({
                    icon: 'error',
                    title: 'Giỏ hàng trống',
                    text: 'Vui lòng thêm sản phẩm trước khi thanh toán'
                });
                return;
            }

            Toast.fire({
                icon: 'success',
                title: 'Đang chuyển hướng đến trang thanh toán'
            }).then(() => {
                window.location.href = '/checkout';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateCartCount();
            renderCart();
        });
    </script>
</body>
</html>