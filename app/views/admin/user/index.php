<?php $this->layout("layouts/default", ["title" => "Quản lý Người dùng - CT275 Store"]) ?>
<?php $this->start("page") ?>
<?php $this->insert('partials/admin-navbar') ?>
<div class="container mt-3">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2>Quản lý Người dùng</h2>
        </div>
        <div class="col-md-4 text-right">
            <a href="/admin/users/create" class="btn btn-primary">+ Thêm người dùng</a>
        </div>
    </div>

    <?php if (!empty($messages)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php foreach ($messages as $message): ?>
                <p><?= $this->e($message) ?></p>
            <?php endforeach; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php foreach ($errors as $error): ?>
                <p><?= $this->e($error) ?></p>
            <?php endforeach; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Email</th>
                    <th>Họ tên</th>
                    <th>Vai trò</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Không có người dùng nào</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $this->e($user->user_id) ?></td>
                            <td><?= $this->e($user->username) ?></td>
                            <td><?= $this->e($user->email) ?></td>
                            <td><?= $this->e($user->fullname) ?></td>
                            <td>
                                <span class="badge bg-<?= $user->roles === 'Admin' ? 'danger' : 'info' ?>">
                                    <?= $this->e($user->roles) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/admin/users/<?= $user->user_id ?>/edit" class="btn btn-sm btn-warning">Sửa</a>
                                <form method="POST" action="/admin/users/<?= $user->user_id ?>/delete" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link">&laquo;</span>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i === $currentPage): ?>
                        <li class="page-item active" aria-current="page">
                            <span class="page-link"><?= $i ?></span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link">&raquo;</span>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="text-center text-muted mt-2" style="margin-bottom: 40px;">
            <small>Trang <?= $currentPage ?> / <?= $totalPages ?> (Tổng cộng: <?= $totalItems ?> người dùng)</small>
        </div>
    <?php endif; ?>
</div>
<?php $this->stop() ?>