<div class="top-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-7">
                    <ul class="top-header-contact-info">
                        <li><i class="bx bx-phone-call"></i><b>(+84) <?php echo isset($settings['phone']) && $settings['phone'] ? htmlspecialchars($settings['phone']) : 'Chưa cài đặt'; ?></b>
                        </li>
                        <li><i class="bx bx-map"></i><b><?php echo isset($settings['address']) && $settings['address'] ? htmlspecialchars($settings['address']) : 'Chưa cài đặt'; ?></b>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-6 col-md-5">
                    <ul class="top-header-menu">
                        <li><a href="#"><i class="fa-brands fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa-brands fa-tiktok"></i></a>
                        </li>
                        <li><a href="#"><i class="fa-solid fa-envelope"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>