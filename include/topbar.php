<div class="navbar-area">
    <div class="drodo-responsive-nav">
        <div class="container">
            <div class="drodo-responsive-menu">
                <a href="/">
                    <?php echo isset($settings['logo']) && $settings['logo'] ? '<img src="' . htmlspecialchars($settings['logo']) . '" alt="Logo" style="max-width: 150px;">' : 'Chưa cài đặt'; ?>
                </a>
            </div>
        </div>
    </div>

    <div class="drodo-nav">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light">
                <a href="/">
                    <?php echo isset($settings['logo']) && $settings['logo'] ? '<img src="' . htmlspecialchars($settings['logo']) . '" alt="Logo" style="max-width: 150px;">' : 'Chưa cài đặt'; ?>
                </a>
                <div class="collapse navbar-collapse mean-menu" style="display: block;">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="/" class="nav-link">Trang chủ</a></li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Sản Phẩm <i class="bx bx-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">Phone Farm <i class="bx bx-chevron-right"></i></a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item"><a href="/products?danhmuc=boxphone" class="nav-link">Box Phone</a></li>
                                        <li class="nav-item"><a href="/products?danhmuc=linhkien" class="nav-link">Linh Kiện</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item"><a href="/products?danhmuc=mayanh" class="nav-link">Máy Ảnh</a></li>
                                <li class="nav-item"><a href="/products?danhmuc=flycam" class="nav-link">Flycam</a></li>
                                <li class="nav-item"><a href="/products?danhmuc=phanmem" class="nav-link">Phần Mềm</a></li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">Phụ Kiện <i class="bx bx-chevron-right"></i></a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item"><a href="/products?danhmuc=phukienmayanh" class="nav-link">Máy Ảnh</a></li>
                                        <li class="nav-item"><a href="/products?danhmuc=phukienflycam" class="nav-link">Flycam</a></li>
                                        <li class="nav-item"><a href="/products?danhmuc=phukienquaychup" class="nav-link">Quay Chụp</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item"><a href="mailto:<?php echo isset($settings['email']) && $settings['email'] ? htmlspecialchars($settings['email']) : 'Chưa cài đặt'; ?>" class="nav-link">Liên hệ</a></li>
                    </ul>

                    <div class="others-option">
                        <div class="option-item">
                            <div class="cart-btn">
                                <a href="/cart" class="cart-icon" title="Giỏ hàng">
                                    <i class='bx bx-cart'></i>
                                    <span id="cart-count" class="cart-count">0</span>
                                </a>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <a href="/account" title="Tài khoản">
                                        <i class='bx bx-user'></i>
                                    </a>
                                    <?php if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == 2): ?>
                                        <a href="/admin" title="Cài đặt">
                                            <i class='bx bx-cog'></i>
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="/auth/login">
                                        <button class="btn" style="border: 2px solid grey; border-radius: 50px;">Đăng Nhập</button>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
<!-- End Navbar Area -->