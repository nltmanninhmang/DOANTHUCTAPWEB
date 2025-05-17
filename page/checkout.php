<?php 
session_start();
include '../config.php';
include './../include/head.php'; 

// Fetch user data if logged in
$user = null;
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT name, phone, address, email FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error fetching user: " . $e->getMessage());
    }
}
?>

<body cz-shortcut-listen="true">
    <?php include '../include/tophead.php'; ?>
    <?php include '../include/topbar.php'; ?>
    <section class="cart-area pt-70 pb-40" style="padding-top: 100px;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="cart-container bg-white" style="background-color: #ffffff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <!-- User Information Form -->
                        <form id="checkout-form">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Họ và tên</label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Nhập họ và tên" value="<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : ''; ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Số điện thoại</label>
                                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="Nhập số điện thoại" pattern="[0-9]{10,11}" value="<?php echo isset($user['phone']) ? htmlspecialchars($user['phone']) : ''; ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Địa chỉ</label>
                                    <input type="text" id="address" name="address" class="form-control" placeholder="Nhập địa chỉ" value="<?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email" value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" required>
                                </div>
                            </div>
                            <!-- Cart Items -->
                            <div id="cart-items" class="table-responsive mt-4">
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
                            <!-- Payment Options -->
                            <div class="mt-4">
                                <h4>Phương thức thanh toán</h4>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash" checked>
                                    <label class="form-check-label" for="cash">Thanh toán bằng tiền mặt</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="qr" value="qr">
                                    <label class="form-check-label" for="qr">Thanh toán bằng QR</label>
                                </div>
                            </div>
                            <!-- Total and Checkout Button -->
                            <div class="mt-4" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: nowrap; gap: 1rem;">
                                <h4 style="margin: 0; white-space: nowrap;">Tổng cộng: <span id="cart-total">0 VNĐ</span></h4>
                                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; white-space: nowrap;">Thanh toán</button>
                            </div>
                        </form>
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

        function clearCart() {
            document.cookie = 'cart=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
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

        document.getElementById('checkout-form').addEventListener('submit', async function(event) {
            event.preventDefault();

            const cart = getCart();
            if (cart.length === 0) {
                Toast.fire({
                    icon: 'error',
                    title: 'Giỏ hàng trống'
                });
                return;
            }

            const formData = new FormData(this);
            const paymentMethod = formData.get('payment_method');

            try {
                const response = await fetch('/api/checkout', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    if (paymentMethod === 'cash') {
                        clearCart();
                        Toast.fire({
                            icon: 'success',
                            title: 'Mua hàng thành công! Đơn hàng đang xử lý.'
                        }).then(() => {
                            window.location.href = '/cart';
                        });
                    } else if (paymentMethod === 'qr') {
                        window.location.href = '/payment';
                    }
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: result.message || 'Thanh toán thất bại'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Đã xảy ra lỗi khi thanh toán'
                });
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            updateCartCount();
            renderCart();
        });
    </script>
</body>
</html>