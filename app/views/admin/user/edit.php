<?php $this->layout("layouts/default", ["title" => "Chỉnh sửa Người dùng - CT275 Store"]) ?>
<?php $this->start("page") ?>
<?php $this->insert('partials/admin-navbar') ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2>Chỉnh sửa Người dùng</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php foreach ($errors as $key => $error): ?>
                        <p><?= $this->e($error) ?></p>
                    <?php endforeach; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="/admin/users/<?= $user->user_id ?>/update">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="user_id" class="form-label">ID Người dùng</label>
                    <input type="text" name="user_id" id="user_id" class="form-control"
                        value="<?= $this->e($user->user_id) ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <input type="text" name="username" id="username"
                        class="form-control <?= !empty($errors['username']) ? 'is-invalid' : '' ?>"
                        value="<?= $this->e($user->username) ?>" required>
                    <?php if (!empty($errors['username'])): ?>
                        <div class="invalid-feedback"><?= $this->e($errors['username']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email"
                        class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
                        value="<?= $this->e($user->email) ?>" required>
                    <?php if (!empty($errors['email'])): ?>
                        <div class="invalid-feedback"><?= $this->e($errors['email']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="fullname" class="form-label">Họ tên</label>
                    <input type="text" name="fullname" id="fullname" class="form-control"
                        value="<?= $this->e($user->fullname) ?>">
                </div>

                <div class="mb-3">
                    <label for="roles" class="form-label">Vai trò</label>
                    <select name="roles" id="roles" class="form-select">
                        <option value="Customer" <?= $user->roles === 'Customer' ? 'selected' : '' ?>>Customer</option>
                        <option value="Admin" <?= $user->roles === 'Admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="/admin/users" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->stop() ?>