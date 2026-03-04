<?php $this->layout("layouts/default", ["title" => "Danh sách sản phẩm - CT275 Store"]) ?>
<?php $this->start("page") ?>
<?php $this->insert('partials/admin-navbar') ?>
<div class="container mt-3">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2>Quản lý Sản phẩm</h2>
        </div>
        <div class="col-md-4 text-right">
            <a href="/admin/products/create" class="btn btn-primary">+ Thêm sản phẩm</a>
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
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Trạng thái</th>
                    <th>Hình ảnh</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Không có sản phẩm nào</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $this->e($product->product_id) ?></td>
                            <td><?= $this->e($product->product_name) ?></td>
                            <td><?= number_format($product->product_price, 0, ',', '.') ?> đ</td>
                            <td><?= $product->stock_quantity ?></td>
                            <td>
                                <span class="badge bg-<?= $product->product_status === 'in_stock' ? 'success' : 'danger' ?>">
                                    <?= $product->product_status === 'in_stock' ? 'Còn hàng' : 'Hết hàng' ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($product->image_urls): ?>
                                    <img src="/images/products/<?= $this->e($product->image_urls) ?>" alt=""
                                        style="max-width: 50px; height: auto;">
                                <?php else: ?>
                                    <span class="text-muted">Không có</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/admin/products/<?= $product->product_id ?>/edit"
                                    class="btn btn-sm btn-warning">Sửa</a>
                                <form method="POST" action="/admin/products/<?= $product->product_id ?>/delete"
                                    style="display: inline;">
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
                <!-- Previous button -->
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

                <!-- Page numbers -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i === $currentPage): ?>
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">
                                <?= $i ?>
                                <span class="visually-hidden"></span>
                            </span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Next button -->
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
            <small>Trang <?= $currentPage ?> / <?= $totalPages ?> (Tổng cộng: <?= $totalItems ?> sản phẩm)</small>
        </div>
    <?php endif; ?>
</div>
<?php $this->stop() ?>