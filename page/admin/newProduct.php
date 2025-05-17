<?php
include '../../include/admin/head.php';
?>

<body>
    <?php include '../../include/admin/navbar.php'; ?>
    <div class="main-content">
        <div class="header">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-end">
                        <div class="col">
                            <h6 class="header-pretitle">SNSTECHNOLOGY</h6>
                            <h1 class="header-title">Thêm Sản Phẩm</h1>
                        </div>
                        <div class="col-auto">
                            <a href="/" class="btn btn-primary lift">Quay về trang chủ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-lg-12 col-xl">
                    <div class="d-flex mb-3">
                        <a href="/admin/product" class="col-6 btn btn-light" style="border-bottom-right-radius: 0px; border-top-right-radius: 0px;">Danh sách sản phẩm</a>
                        <a href="#" class="col-6 btn btn-primary" style="border-bottom-left-radius: 0px; border-top-left-radius: 0px;">Thêm sản phẩm mới</a>
                    </div>

                    <form id="add-product-form" enctype="multipart/form-data" class="mb-4">
                        <div class="form-group">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" name="name" placeholder="Tên sản phẩm" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Danh mục</label>
                            <select id="category" name="category" class="form-control" required>
                                <option value="boxphone">Box phone</option>
                                <option value="linhkien">Linh kiện</option>
                                <option value="mayanh">Máy ảnh</option>
                                <option value="flycam">Flycam</option>
                                <option value="phanmem">Phần mềm</option>
                                <option value="phukienmayanh">Phụ kiện máy ảnh</option>
                                <option value="phukienflycam">Phụ kiện flycam</option>
                                <option value="phukienquaychup">Phụ kiện quay chụp</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Số lượng sản phẩm</label>
                            <input type="number" class="form-control" name="amount" placeholder="Số lượng sản phẩm" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ảnh sản phẩm</label>
                            <input type="file" class="form-control" name="image" accept="image/*" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Giá tiền (VNĐ)</label>
                            <input type="text" class="form-control" name="price" placeholder="Giá tiền, ví dụ: 10.000.000" required>
                            <small class="form-text text-muted">Nhập giá bằng số, ví dụ: 10.000.000</small>
                        </div>

                        <button type="submit" class="btn w-100 btn-primary">Thêm sản phẩm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="../../assets/js/vendor.bundle.js"></script>
    <script src="../../assets/js/theme.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Hàm định dạng giá theo kiểu VNĐ
        function formatVND(price) {
            return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' VNĐ';
        }

        // Xử lý định dạng giá khi nhập
        document.querySelector('input[name="price"]').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Chỉ giữ số
            if (value) {
                e.target.value = parseInt(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
        });

        document.getElementById('add-product-form').addEventListener('submit', async function(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            
            // Chuyển đổi giá từ định dạng VNĐ sang số
            const priceInput = form.price.value.replace(/\./g, '');
            formData.set('price', priceInput);

            // Validate form inputs
            const name = form.name.value;
            const category = form.category.value;
            const amount = form.amount.value;
            const image = form.image.files[0];
            const price = priceInput;

            if (!name || !category || !amount || !image || !price) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Vui lòng điền đầy đủ thông tin và chọn ảnh sản phẩm!',
                });
                return;
            }

            try {
                const response = await fetch('/api/product/add', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: `Sản phẩm đã được thêm!`,
                    }).then(() => {
                        window.location.href = '/admin/product?success=1';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Thất bại',
                        text: data.message || 'Có lỗi xảy ra khi thêm sản phẩm',
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi kết nối: ' + error.message,
                });
            }
        });
    </script>
</body>
</html>