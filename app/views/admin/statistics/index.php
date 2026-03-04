<?php $this->layout("layouts/default", ["title" => "Thống kê - CT275 Store"]) ?>
<?php $this->start("page") ?>
<?php $this->insert('partials/admin-navbar') ?>
<div class="container-fluid mt-3">
    <h2 class="mb-4">Dashboard Thống kê</h2>

    <!-- Row 1: User Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Tổng Người dùng</h5>
                    <h2><?= $stats['userCount'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Admin</h5>
                    <h2><?= $stats['adminCount'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Customer</h5>
                    <h2><?= $stats['customerCount'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Product Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Tổng Sản phẩm</h5>
                    <h2><?= $stats['productCount'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Còn hàng</h5>
                    <h2><?= $stats['inStockCount'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h5 class="card-title">Hết hàng</h5>
                    <h2><?= $stats['outOfStockCount'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Order and Revenue Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Tổng Danh mục</h5>
                    <h2><?= $stats['categoryCount'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Tổng Đơn hàng</h5>
                    <h2><?= $stats['orderCount'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Doanh thu</h5>
                    <h2><?= number_format($stats['revenue'], 0, ',', '.') ?> đ</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 4: Order Status -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Đơn hàng thành công</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-success"><?= $stats['successOrderCount'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Đơn hàng thất bại</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-danger"><?= $stats['failedOrderCount'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 5: Top Products -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Top 5 Sản phẩm bán chạy</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($topProducts)): ?>
                                <?php $count = 1; ?>
                                <?php foreach ($topProducts as $product): ?>
                                    <tr>
                                        <td><?= $count++ ?></td>
                                        <td><?= $this->e($product->product_name) ?></td>
                                        <td><?= $product->total_quantity ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Products by Category -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Sản phẩm theo danh mục</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Danh mục</th>
                                <th>Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($productsByCategory)): ?>
                                <?php foreach ($productsByCategory as $category): ?>
                                    <tr>
                                        <td><?= $this->e($category->category_name) ?></td>
                                        <td><?= $category->product_count ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 6: Orders by Month -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Đơn hàng theo tháng (12 tháng gần nhất)</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tháng</th>
                                <th>Số đơn hàng</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ordersByMonth)): ?>
                                <?php foreach ($ordersByMonth as $month): ?>
                                    <tr>
                                        <td><?= date('m/Y', strtotime($month->month)) ?></td>
                                        <td><?= $month->order_count ?></td>
                                        <td><?= number_format($month->month_revenue, 0, ',', '.') ?> đ</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 40px;"></div>
</div>
<?php $this->stop() ?>