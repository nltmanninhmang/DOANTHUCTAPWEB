<?php 
session_start();
include 'config.php';
include './include/head.php'; 
include './include/settings.php';

$stmt = $pdo->prepare("SELECT * FROM sanpham"); 
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body cz-shortcut-listen="true">
    <?php include './include/tophead.php'; ?>
    <?php include './include/topbar.php'; ?>
    <!-- Start Main Banner Area -->
    <section class="main-banner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 col-md-12">
                    <div class="main-banner-content">
                        <span class="sub-title">Giải pháp marketing đa kênh</span>
                        <h1><?php echo isset($settings['website_name']) && $settings['website_name'] ? htmlspecialchars($settings['website_name']) : 'Chưa cài đặt'; ?></h1>
                        <p>Chúng tôi chuyên cung cấp các linh kiện, thiết bị, giải pháp phần mềm để tối ưu hoá doanh thu và giảm chi phí nhất.</p>
                        <a href="#sanpham" class="default-btn"><i class="flaticon-trolley"></i> Mua hàng ngay</a>
                    </div>
                </div>
                <div class="col-lg-7 col-md-12">
                    <div class="main-banner-image">
                        <img src="assets/img/snslanding.png" alt="image" style="width: 600px;">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Main Banner Area -->

    <!-- Start Facility Area -->
    <section class="facility-area bg-f7f8fa pt-70 pb-40">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-4 col-sm-12 col-md-4 col-12">
                    <div class="single-facility-box">
                        <div class="icon">
                            <i class="flaticon-free-shipping"></i>
                        </div>
                        <h3>Giao hàng nhanh</h3>
                        <p>Tốc độ xử lý đơn hàng nhanh chóng tránh tình trạng delay</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12 col-md-4 col-12">
                    <div class="single-facility-box">
                        <div class="icon">
                            <i class="flaticon-headset"></i>
                        </div>
                        <h3>Hỗ trợ 24/7</h3>
                        <p>Đội ngũ luôn sẵn sàng hỗ trợ và lắng nghe ý kiến khách hàng</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12 col-md-4 col-12">
                    <div class="single-facility-box" style="border-right: none;">
                        <div class="icon">
                            <i class="flaticon-secure-payment"></i>
                        </div>
                        <h3>Thanh toán</h3>
                        <p>Phương thức thanh toán tiên tiến nhất đảm bảo an toàn</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Facility Area -->

    <!-- Start Products Area -->
    <section class="products-area pt-70 pb-40" id="sanpham">
        <div class="container">
            <div class="section-title">
                <h2>Các sản phẩm</h2>
            </div>
            <div class="owl-carousel owl-theme owl-loaded owl-drag">
                <div class="owl-stage-outer">
                    <div class="owl-stage">
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
                    </div>
                </div>
                <div class="owl-dots disabled"></div>
                <div class="owl-thumbs"></div>
            </div>
        </div>
    </section>
    <!-- End Products Area -->

    <!-- Start Quick View Modal -->
    <div class="modal fade" id="productsQuickView" tabindex="-1" aria-labelledby="productsQuickViewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6">
                            <div class="products-image">
                                <img id="quickViewImage" src="" alt="image" style="border-radius: 10px;">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="products-content">
                                <h3 id="quickViewName"></h3>
                                <div class="price">
                                    <span id="quickViewPrice" class="new-price mt-2 h4"></span>
                                </div>
                                <div class="mt-3 mb-3 d-flex align-items-center col-md-12">
                                    <label for="quickViewQuantity" class="form-label col-md-6 mb-0">Số lượng</label>
                                    <input type="number" id="quickViewQuantity" class="form-control" min="1" value="1">
                                </div>
                                <div class="products-add-to-cart">
                                    <button type="button" class="default-btn col-md-12" onclick="addToCartFromQuickView()">
                                        <i class="bx bx-cart"></i> Thêm vào giỏ
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Quick View Modal -->

    <!-- Start Brands Area -->
    <section class="brands-area bg-f7f8fa pt-70 pb-40">
        <div class="container">
            <div class="section-title">
                <h2>Đối tác kinh doanh</h2>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-2 col-sm-4 col-md-2 col-6">
                    <div class="single-brands-item" style="border-right: none;">
                        <a href="#" class="d-block"><img src="assets/img/Shopee.svg.webp" alt="image"></a>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4 col-md-2 col-6">
                    <div class="single-brands-item" style="border-right: none;">
                        <a href="#" class="d-block"><img src="assets/img/Logo_Tiki.png" alt="image"></a>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4 col-md-2 col-6">
                    <div class="single-brands-item" style="border-right: none;">
                        <a href="#" class="d-block"><img src="assets/img/Lazada.svg" alt="image"></a>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4 col-md-2 col-6">
                    <div class="single-brands-item" style="border-right: none;">
                        <a href="#" class="d-block"><img src="assets/img/Amazon_logo.svg" alt="image"></a>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4 col-md-2 col-6">
                    <div class="single-brands-item" style="border-right: none;">
                        <a href="#" class="d-block"><img src="assets/img/snsteam.png" alt="image"></a>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4 col-md-2 col-6">
                    <div class="single-brands-item" style="border-right: none;">
                        <a href="#" class="d-block"><img src="assets/img/Ebay_logo.svg.png" alt="image"></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include './include/footer.php'; ?>

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