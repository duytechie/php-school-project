<?php $this->layout("layouts/default", ["title" => "Shopping Cart - CT275 Store"]) ?>

<?php $this->start("page_specific_css") ?>
<style>
    /* Fix header positioning for cart page */
    .container-menu-desktop {
        height: 124px;
        display: block !important;
    }

    .wrap-menu-desktop {
        position: fixed;
        z-index: 1100;
        background-color: #fff !important;
        width: 100%;
        height: 84px;
        top: 40px;
        left: 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Fix logo styling on cart page */
    .logo {
        display: flex !important;
        align-items: center !important;
        height: 65% !important;
        margin-right: 55px !important;
        flex-shrink: 0;
        min-width: 150px;
    }

    .logo img {
        max-width: 100% !important;
        max-height: 100% !important;
        height: auto !important;
        width: auto !important;
        object-fit: contain;
    }

    /* Ensure navbar container has proper spacing */
    .limiter-menu-desktop {
        height: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between;
    }

    /* Ensure proper layout for menu items */
    .menu-desktop {
        display: flex !important;
        align-items: center !important;
        height: 100% !important;
        flex-grow: 1;
    }

    /* Ensure main menu is visible */
    .main-menu {
        display: flex !important;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    /* Add spacing for breadcrumb to avoid overlap with fixed header */
    .bread-crumb {
        margin-top: 124px;
    }

    /* Responsive: Show desktop menu on all screen sizes for cart page */
    @media (max-width: 991px) {
        .container-menu-desktop {
            display: block !important;
        }

        .wrap-header-mobile {
            display: none !important;
        }

        /* Adjust menu items for smaller screens */
        .main-menu>li {
            margin: 0 5px;
        }

        .main-menu>li>a {
            font-size: 13px;
        }

        .logo {
            margin-right: 20px;
            min-width: 120px;
        }
    }

    @media (max-width: 767px) {

        /* For very small screens, reduce sizes further */
        .logo {
            min-width: 100px;
        }

        .main-menu>li>a {
            font-size: 12px;
            padding: 5px 5px;
        }

        .icon-header-item {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
    }
</style>
<?php $this->stop() ?>

<?php $this->start("page") ?>
<?php include_once __DIR__ . '/../partials/navbar.php'; ?>

<!-- breadcrumb -->
<div class="container">
    <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
        <a href="/" class="stext-109 cl8 hov-cl1 trans-04">
            Home
            <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
        </a>

        <span class="stext-109 cl4">
            Shopping Cart
        </span>
    </div>
</div>

<!-- Shopping Cart -->
<div class="bg0 p-t-75 p-b-85">
    <div class="container">
        <?php if (!empty($cartItems)): ?>
            <div class="row">
                <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                    <div class="m-l-25 m-r--38 m-lr-0-xl">
                        <div class="wrap-table-shopping-cart">
                            <table class="table-shopping-cart">
                                <tr class="table_head">
                                    <th class="column-1">Product</th>
                                    <th class="column-2"></th>
                                    <th class="column-3">Price</th>
                                    <th class="column-4">Quantity</th>
                                    <th class="column-5">Total</th>
                                    <th class="column-6"></th>
                                </tr>

                                <?php foreach ($cartItems as $item): ?>
                                    <tr class="table_row" data-product-id="<?= $this->e($item['product_id']) ?>">
                                        <td class="column-1">
                                            <div class="how-itemcart1">
                                                <?php if ($item['image_url']): ?>
                                                    <img src="/images/products/<?= $this->e($item['image_url']) ?>"
                                                        alt="<?= $this->e($item['product_name']) ?>">
                                                <?php else: ?>
                                                    <img src="/images/product-01.jpg" alt="<?= $this->e($item['product_name']) ?>">
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="column-2"><?= $this->e($item['product_name']) ?></td>
                                        <td class="column-3 item-price" data-price="<?= $item['product_price'] ?>">
                                            <?= number_format($item['product_price'], 0, ',', '.') ?> đ
                                        </td>
                                        <td class="column-4">
                                            <div class="wrap-num-product flex-w m-l-auto m-r-0">
                                                <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                                    <i class="fs-16 zmdi zmdi-minus"></i>
                                                </div>

                                                <input class="mtext-104 cl3 txt-center num-product" type="number"
                                                    name="quantity" value="<?= $item['quantity'] ?>" min="1">

                                                <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                                    <i class="fs-16 zmdi zmdi-plus"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="column-5 item-total">
                                            <?= number_format($item['product_price'] * $item['quantity'], 0, ',', '.') ?> đ
                                        </td>
                                        <td class="column-6">
                                            <button class="btn-remove-item"
                                                style="border: none; background: none; cursor: pointer;">
                                                <i class="zmdi zmdi-close fs-20 cl2 hov-cl1 trans-04"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>

                        <div class="flex-w flex-sb-m bor15 p-t-18 p-b-15 p-lr-40 p-lr-15-sm">
                            <a href="/"
                                class="flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10">
                                Continue Shopping
                            </a>

                            <button id="btn-clear-cart"
                                class="flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10">
                                Clear Cart
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                    <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                        <h4 class="mtext-109 cl2 p-b-30">
                            Cart Totals
                        </h4>

                        <div class="flex-w flex-t bor12 p-b-13">
                            <div class="size-208">
                                <span class="stext-110 cl2">
                                    Subtotal:
                                </span>
                            </div>

                            <div class="size-209">
                                <span class="mtext-110 cl2" id="cart-subtotal">
                                    <?= number_format($total, 0, ',', '.') ?> đ
                                </span>
                            </div>
                        </div>

                        <div class="flex-w flex-t p-t-27 p-b-33">
                            <div class="size-208">
                                <span class="mtext-101 cl2">
                                    Total:
                                </span>
                            </div>

                            <div class="size-209 p-t-1">
                                <span class="mtext-110 cl2" id="cart-total">
                                    <?= number_format($total, 0, ',', '.') ?> đ
                                </span>
                            </div>
                        </div>

                        <a href="/cart/checkout"
                            class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12 text-center p-t-100 p-b-100">
                    <i class="zmdi zmdi-shopping-cart" style="font-size: 80px; color: #ccc;"></i>
                    <h3 class="mtext-109 cl2 p-t-20 p-b-20">Your cart is empty</h3>
                    <p class="stext-107 cl6 p-b-30">Add some products to your cart to see them here</p>
                    <a href="/" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer"
                        style="max-width: 300px; margin: 0 auto;">
                        Continue Shopping
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Back to top -->
<div class="btn-back-to-top" id="myBtn">
    <span class="symbol-btn-back-to-top">
        <i class="zmdi zmdi-chevron-up"></i>
    </span>
</div>

<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="vendor/animsition/js/animsition.min.js"></script>
<script src="vendor/bootstrap/js/popper.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/sweetalert/sweetalert.min.js"></script>
<script src="js/main.js"></script>

<script>
    $(document).ready(function () {
        // Setup CSRF token for AJAX requests
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajaxSetup({
            beforeSend: function (xhr, settings) {
                if (settings.type === 'POST' || settings.type === 'PUT' || settings.type === 'DELETE') {
                    if (csrfToken && settings.data) {
                        // Add CSRF token to POST data
                        if (typeof settings.data === 'string') {
                            settings.data += (settings.data ? '&' : '') + 'csrf_token=' + encodeURIComponent(csrfToken);
                        } else if (typeof settings.data === 'object' && !(settings.data instanceof FormData)) {
                            settings.data.csrf_token = csrfToken;
                        } else if (settings.data instanceof FormData) {
                            settings.data.append('csrf_token', csrfToken);
                        }
                    }
                }
            }
        });

        // Update quantity
        $('.btn-num-product-down, .btn-num-product-up').on('click', function () {
            var $row = $(this).closest('.table_row');
            var $input = $row.find('.num-product');
            var quantity = parseInt($input.val());
            var productId = $row.data('product-id');

            if ($(this).hasClass('btn-num-product-down')) {
                quantity = Math.max(1, quantity - 1);
            } else {
                quantity = quantity + 1;
            }

            $input.val(quantity);
            updateCart(productId, quantity, $row);
        });

        // Update on input change
        $('.num-product').on('change', function () {
            var $row = $(this).closest('.table_row');
            var quantity = Math.max(1, parseInt($(this).val()) || 1);
            var productId = $row.data('product-id');
            $(this).val(quantity);
            updateCart(productId, quantity, $row);
        });

        // Remove item
        $('.btn-remove-item').on('click', function () {
            var $row = $(this).closest('.table_row');
            var productId = $row.data('product-id');

            swal({
                title: "Are you sure?",
                text: "Do you want to remove this item from cart?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    removeFromCart(productId, $row);
                }
            });
        });

        // Clear cart
        $('#btn-clear-cart').on('click', function () {
            swal({
                title: "Are you sure?",
                text: "Do you want to clear all items from cart?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willClear) => {
                if (willClear) {
                    window.location.href = '/cart/clear';
                }
            });
        });

        function updateCart(productId, quantity, $row) {
            $.ajax({
                url: '/cart/update',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function (response) {
                    if (response.success) {
                        // Update row total
                        var price = parseFloat($row.find('.item-price').data('price'));
                        var total = price * quantity;
                        $row.find('.item-total').text(formatNumber(total) + ' đ');

                        // Update cart totals
                        $('#cart-subtotal').text(formatNumber(response.total) + ' đ');
                        $('#cart-total').text(formatNumber(response.total) + ' đ');

                        // Update cart count in header
                        updateCartCount();
                    } else {
                        swal("Error", response.message, "error");
                    }
                },
                error: function () {
                    swal("Error", "Failed to update cart", "error");
                }
            });
        }

        function removeFromCart(productId, $row) {
            $.ajax({
                url: '/cart/remove',
                method: 'POST',
                data: {
                    product_id: productId
                },
                success: function (response) {
                    if (response.success) {
                        $row.fadeOut(300, function () {
                            $(this).remove();

                            // Check if cart is empty
                            if ($('.table_row').length === 0) {
                                location.reload();
                            } else {
                                // Update cart totals
                                $('#cart-subtotal').text(formatNumber(response.total) + ' đ');
                                $('#cart-total').text(formatNumber(response.total) + ' đ');

                                // Update cart count in header
                                updateCartCount();
                            }
                        });

                        swal("Removed!", response.message, "success");
                    } else {
                        swal("Error", response.message, "error");
                    }
                },
                error: function () {
                    swal("Error", "Failed to remove item from cart", "error");
                }
            });
        }

        function updateCartCount() {
            $.ajax({
                url: '/cart/count',
                method: 'GET',
                success: function (response) {
                    $('.js-show-cart').attr('data-notify', response.count);
                }
            });
        }

        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    });
</script>

<?php $this->stop() ?>