<footer class="footer-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-6 col-md-6">
                    <div class="single-footer-widget">
                        <a href="/" class="logo d-inline-block"><img src="/assets/img/snstech.svg" alt="image" style="width: 200px;">
                        </a>
                        <ul class="footer-contact-info">
                            <li><span>Phone:</span> (+84) <?php echo isset($settings['phone']) && $settings['phone'] ? htmlspecialchars($settings['phone']) : 'Chưa cài đặt'; ?>
                            </li>
                            <li><span>Email:</span> <?php echo isset($settings['email']) && $settings['email'] ? htmlspecialchars($settings['email']) : 'Chưa cài đặt'; ?>
                            </li>
                            <li><span>Địa chỉ:</span> <?php echo isset($settings['address']) && $settings['address'] ? htmlspecialchars($settings['address']) : 'Chưa cài đặt'; ?>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6 col-md-6">
                    <div class="single-footer-widget">
                        <h3>Thông tin</h3>

                        <ul class="link-list">
                            <li><a href="#">Về chúng tôi</a>
                            </li>
                            <li><a href="#">Liên hệ</a>
                            </li>
                            <li><a href="#">Pháp lý</a>
                            </li>
                            <li><a href="#">Điều khoản dịch vụ</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6 col-md-6">
                    <div class="single-footer-widget">
                        <h3>Liên hệ</h3>
                        <p>Bạn muốn nhận thông tin về các chính sách và ưu đãi mới.</p>
                        <form class="newsletter-form" data-bs-toggle="validator" novalidate="true">
                            <input type="text" class="input-newsletter" placeholder="Nhập email của bạn" required="" autocomplete="off">
                            <button type="submit" class="default-btn disabled" style="pointer-events: all; cursor: pointer;">Gửi</button>
                            <div id="validator-newsletter" class="form-result"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <p>©2025 <?php echo isset($settings['website_name']) && $settings['website_name'] ? htmlspecialchars($settings['website_name']) : 'Chưa cài đặt'; ?> - Designed By <a href="#"><?php echo isset($settings['website_name']) && $settings['website_name'] ? htmlspecialchars($settings['website_name']) : 'Chưa cài đặt'; ?></a>
                        </p>
                    </div>

                    
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer Area -->

    <div class="go-top" style="border-radius: 10px;"><i class="bx bx-up-arrow-alt"></i>
    </div>
    <script>
        function openQuickView(productId) {
            // Tìm sản phẩm trong mảng sản phẩm
            const product = <?php echo json_encode($products); ?>.find(p => p.id == productId);

            // Cập nhật thông tin trong modal
            document.getElementById('quickViewImage').src = product.url_image;
            document.getElementById('quickViewName').innerText = product.name;
            document.getElementById('quickViewPrice').innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(product.price);

            // Hiển thị modal
            const modal = new bootstrap.Modal(document.getElementById('productsQuickView'));
            modal.show();
        }
    </script>
    <!-- Links of JS files -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/magnific-popup.min.js"></script>
    <script src="assets/js/fancybox.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/owl.carousel2.thumbs.min.js"></script>
    <script src="assets/js/meanmenu.min.js"></script>
    <script src="assets/js/nice-select.min.js"></script>
    <script src="assets/js/rangeSlider.min.js"></script>
    <script src="assets/js/sticky-sidebar.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/form-validator.min.js"></script>
    <script src="assets/js/contact-form-script.js"></script>
    <script src="assets/js/ajaxchimp.min.js"></script>
    <script src="assets/js/main.js"></script>