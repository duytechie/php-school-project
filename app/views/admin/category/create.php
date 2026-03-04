<?php $this->layout("layouts/default", ["title" => "Thêm Danh mục - CT275 Store"]) ?>
<?php $this->start("page") ?>
<?php $this->insert('partials/admin-navbar') ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2>Thêm Danh mục</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php foreach ($errors as $key => $error): ?>
                        <p><?= $this->e($error) ?></p>
                    <?php endforeach; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="/admin/categories/store">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="category_name" class="form-label">Tên danh mục</label>
                    <input type="text" name="category_name" id="category_name"
                        class="form-control <?= !empty($errors['category_name']) ? 'is-invalid' : '' ?>"
                        value="<?= $this->e($old['category_name'] ?? '') ?>" required>
                    <?php if (!empty($errors['category_name'])): ?>
                        <div class="invalid-feedback"><?= $this->e($errors['category_name']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="category_desc" class="form-label">Mô tả</label>
                    <textarea name="category_desc" id="category_desc" class="form-control"
                        rows="4"><?= $this->e($old['category_desc'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Tạo Danh mục</button>
                    <a href="/admin/categories" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->stop() ?>