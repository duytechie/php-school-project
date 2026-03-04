<?php $this->layout("layouts/default", ["title" => "Chỉnh sửa sản phẩm - CT275 Store"]) ?>
<?php $this->start("page") ?>
<?php $this->insert('partials/admin-navbar') ?>

<div class="container mt-3" style="margin-bottom: 40px; justify-content: center;">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="mb-4">Chỉnh sửa sản phẩm</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php foreach ($errors as $field => $error): ?>
                        <p><strong><?= ucfirst($field) ?>:</strong> <?= $this->e($error) ?></p>
                    <?php endforeach; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="/admin/products/<?= $product->product_id ?>/update" enctype="multipart/form-data" class="needs-validation">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="product_name" class="form-label">Tên sản phẩm *</label>
                    <input type="text" class="form-control <?= !empty($errors['product_name']) ? 'is-invalid' : '' ?>"
                        id="product_name" name="product_name"
                        value="<?= $this->e($old['product_name'] ?? $product->product_name) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="product_desc" class="form-label">Mô tả sản phẩm</label>
                    <textarea class="form-control" id="product_desc" name="product_desc" rows="3"><?= $this->e($old['product_desc'] ?? $product->product_desc) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="product_price" class="form-label">Giá *</label>
                        <input type="number" class="form-control <?= !empty($errors['product_price']) ? 'is-invalid' : '' ?>"
                            id="product_price" name="product_price"
                            value="<?= $this->e($old['product_price'] ?? $product->product_price) ?>" min="0" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="stock_quantity" class="form-label">Số lượng *</label>
                        <input type="number" class="form-control <?= !empty($errors['stock_quantity']) ? 'is-invalid' : '' ?>"
                            id="stock_quantity" name="stock_quantity"
                            value="<?= $this->e($old['stock_quantity'] ?? $product->stock_quantity) ?>" min="0" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Danh mục *</label>
                        <select class="form-control <?= !empty($errors['category_id']) ? 'is-invalid' : '' ?>"
                            id="category_id" name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $this->e($cat->category_id) ?>"
                                    <?= ($old['category_id'] ?? $product->category_id) === $cat->category_id ? 'selected' : '' ?>>
                                    <?= $this->e($cat->category_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="product_status" class="form-label">Trạng thái</label>
                        <select class="form-control" id="product_status" name="product_status">
                            <option value="in_stock" <?= ($old['product_status'] ?? $product->product_status) === 'in_stock' ? 'selected' : '' ?>>Còn hàng</option>
                            <option value="out_of_stock" <?= ($old['product_status'] ?? $product->product_status) === 'out_of_stock' ? 'selected' : '' ?>>Hết hàng</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Hình ảnh</label>
                    <?php if ($product->image_urls): ?>
                        <div class="mb-2">
                            <p><strong>Hình ảnh hiện tại:</strong></p>
                            <img src="/images/products/<?= $this->e($product->image_urls) ?>" alt="" style="max-width: 150px; height: auto;">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control <?= !empty($errors['image']) ? 'is-invalid' : '' ?>"
                        id="image" name="image" accept="image/*">
                    <small class="text-muted">Nếu muốn thay đổi hình ảnh, chọn file mới. Định dạng: JPG, PNG, GIF. Kích thước tối đa: 5MB</small>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/admin/products" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->stop() ?>