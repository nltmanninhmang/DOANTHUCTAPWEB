<?php 
session_start();
include '../config.php';

$category = isset($_GET['danhmuc']) ? trim($_GET['danhmuc']) : '';

if ($category) {
    $stmt = $pdo->prepare("SELECT * FROM sanpham WHERE LOWER(danhmuc) = LOWER(?) ORDER BY id DESC");
    $stmt->execute([$category]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM sanpham ORDER BY id DESC");
    $stmt->execute();
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../include/head.php'; 
?>

<body cz-shortcut-listen="true">
    <?php include '../include/tophead.php'; ?>
    <?php include '../include/topbar.php'; ?>
    <section class="page-title-area">
        <div class="container">
            <div class="page-title-content">
                <h1><?php echo $category ? htmlspecialchars($category) : 'Danh mục sản phẩm'; ?></h1>
                <ul>
                    <li><a href="/">Trang chủ</a></li>
                    <li><?php echo $category ? htmlspecialchars($category) : 'Danh mục'; ?></li>
                </ul>
            </div>
        </div>
    </section>
    <section class="products-area pt-70 pb-40" id="sanpham">
        <div class="container">
            <div class="section-title">
                <h2><?php echo $category ? htmlspecialchars($category) : 'Các sản phẩm'; ?></h2>
            </div>
            <div class="owl-carousel owl-theme owl-loaded owl-drag">
                <div class="owl-stage-outer">
                    <div class="owl-stage">
                        <?php if (empty($products)): ?>
                            <div class="owl-item" style="width: 211.5px; margin-right: 30px;">
                                <div class="single-products-box text-center">
                                    <p>Không có sản phẩm trong danh mục này</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <div class="owl-item" style="width: 211.5px; margin-right: 30px;">
                                    <div class="single-products-box">
                                        <div class="image">
                                            <img src="<?php echo htmlspecialchars($product['name_image']); ?>" alt="<?php echo htmlspecialchars($product['name'] ?? 'Product Image'); ?>">
                                        </div>
                                        <div class="content text-center">
                                            <h3><a href="#" onclick="openQuickView(<?php echo htmlspecialchars($product['id']); ?>)"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                                            <div class="price">
                                                <span class="new-price"><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center mt-2">
                                            <button class="btn btn-primary col-md-12" onclick="addToCartFromList(<?php echo htmlspecialchars(json_encode($product)); ?>)">
                                                <i class="bx bx-cart"></i> Thêm vào giỏ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="owl-dots disabled"></div>
                <div class="owl-thumbs"></div>
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

        function getCart() {
            const cart = document.cookie
                .split('; ')
                .find(row => row.startsWith('cart='));
            return cart ? JSON.parse(decodeURIComponent(cart.split('=')[1])) : [];
        }

        function saveCart(cart) {
            const expires = new Date();
            expires.setDate(expires.getDate() + 7); 
            document.cookie = `cart=${encodeURIComponent(JSON.stringify(cart))}; expires=${expires.toUTCString()}; path=/`;
            updateCartCount();
        }

        function updateCartCount() {
            const cart = getCart();
            const count = cart.reduce((total, item) => total + item.quantity, 0);
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
            }
        }

        function addToCartFromList(product) {
            const cart = getCart();
            const existingItem = cart.find(item => item.id === product.id);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    name_image: product.name_image,
                    quantity: 1
                });
            }
            saveCart(cart);
            Toast.fire({
                icon: 'success',
                title: `${product.name} đã được thêm vào giỏ hàng`
            });
        }

        async function openQuickView(productId) {
            try {
                const response = await fetch(`/api/product/${productId}`);
                const product = await response.json();
                document.getElementById('quickViewImage').src = product.name_image;
                document.getElementById('quickViewName').textContent = product.name;
                document.getElementById('quickViewPrice').textContent = `${Number(product.price).toLocaleString('vi-VN')} VNĐ`;
                document.getElementById('quickViewQuantity').value = 1;
                document.getElementById('productsQuickView').dataset.product = JSON.stringify(product);
                new bootstrap.Modal(document.getElementById('productsQuickView')).show();
            } catch (error) {
                console.error('Error:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Không thể tải thông tin sản phẩm'
                });
            }
        }

        function addToCartFromQuickView() {
            const modal = document.getElementById('productsQuickView');
            const product = JSON.parse(modal.dataset.product);
            const quantity = parseInt(document.getElementById('quickViewQuantity').value);
            if (quantity < 1) {
                Toast.fire({
                    icon: 'error',
                    title: 'Số lượng không hợp lệ'
                });
                return;
            }
            const cart = getCart();
            const existingItem = cart.find(item => item.id === product.id);
            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    name_image: product.name_image,
                    quantity: quantity
                });
            }
            saveCart(cart);
            Toast.fire({
                icon: 'success',
                title: `${product.name} đã được thêm vào giỏ hàng`
            });
        }

        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
</body>
</html>