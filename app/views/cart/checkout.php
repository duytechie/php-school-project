<?php $this->layout("layouts/default", ["title" => "Checkout - CT275 Store"]) ?>

<?php $this->start("page_specific_css") ?>
<style>
    /* Fix header positioning for checkout page */
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

    /* Fix logo styling on checkout page */
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

    /* Add top padding to main content to avoid overlap with fixed header */
    .bg0.p-t-75 {
        padding-top: 150px !important;
    }

    /* Responsive: Show desktop menu on all screen sizes for checkout page */
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

<!-- Checkout -->
<div class="bg0 p-t-75 p-b-85">
    <div class="container">
        <?php if ($error = session_get_once('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= $this->e($error) ?>
            </div>
        <?php endif; ?>

        <form action="/cart/process-checkout" method="POST">
            <?= csrf_field() ?>
            <div class="row">
                <!-- Billing Details -->
                <div class="col-lg-7 col-xl-8 m-lr-auto m-b-50">
                    <div class="bor10 p-lr-40 p-t-30 p-b-40 m-lr-0-xl p-lr-15-sm">
                        <h4 class="mtext-109 cl2 p-b-30">
                            Billing Details
                        </h4>

                        <div class="bor8 bg0 m-b-22">
                            <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="fullname"
                                placeholder="Full Name *" required>
                        </div>

                        <div class="bor8 bg0 m-b-22">
                            <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="email" name="email"
                                placeholder="Email Address *" required>
                        </div>

                        <div class="bor8 bg0 m-b-22">
                            <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="tel" name="phone"
                                placeholder="Phone Number *" required>
                        </div>

                        <div class="bor8 bg0 m-b-22">
                            <textarea class="stext-111 cl8 plh3 size-111 p-lr-15" name="address" rows="3"
                                placeholder="Delivery Address *" required></textarea>
                        </div>

                        <div class="bor8 bg0 m-b-22">
                            <textarea class="stext-111 cl8 plh3 size-111 p-lr-15" name="notes" rows="3"
                                placeholder="Order Notes (optional)"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-sm-10 col-lg-5 col-xl-4 m-lr-auto m-b-50">
                    <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                        <h4 class="mtext-109 cl2 p-b-30">
                            Your Order
                        </h4>

                        <!-- Order Items -->
                        <div class="flex-w flex-t p-b-13">
                            <div class="size-208">
                                <span class="stext-110 cl2">
                                    <strong>Product</strong>
                                </span>
                            </div>

                            <div class="size-209 text-right">
                                <span class="stext-110 cl2">
                                    <strong>Subtotal</strong>
                                </span>
                            </div>
                        </div>

                        <?php foreach ($cartItems as $item): ?>
                            <div class="flex-w flex-t bor12 p-t-15 p-b-15">
                                <div class="size-208">
                                    <span class="stext-111 cl2">
                                        <?= $this->e($item['product_name']) ?> × <?= $item['quantity'] ?>
                                    </span>
                                </div>

                                <div class="size-209 text-right">
                                    <span class="stext-111 cl2">
                                        <?= number_format($item['product_price'] * $item['quantity'], 0, ',', '.') ?> đ
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Subtotal -->
                        <div class="flex-w flex-t bor12 p-t-15 p-b-15">
                            <div class="size-208">
                                <span class="stext-110 cl2">
                                    <strong>Subtotal</strong>
                                </span>
                            </div>

                            <div class="size-209 text-right">
                                <span class="mtext-110 cl2">
                                    <?= number_format($total, 0, ',', '.') ?> đ
                                </span>
                            </div>
                        </div>

                        <!-- Shipping -->
                        <div class="flex-w flex-t bor12 p-t-15 p-b-15">
                            <div class="size-208">
                                <span class="stext-110 cl2">
                                    Shipping
                                </span>
                            </div>

                            <div class="size-209 text-right">
                                <span class="stext-111 cl2">
                                    Free shipping
                                </span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="flex-w flex-t p-t-27 p-b-33">
                            <div class="size-208">
                                <span class="mtext-101 cl2">
                                    <strong>Total</strong>
                                </span>
                            </div>

                            <div class="size-209 text-right p-t-1">
                                <span class="mtext-110 cl2">
                                    <?= number_format($total, 0, ',', '.') ?> đ
                                </span>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="p-b-30">
                            <h5 class="mtext-108 cl2 p-b-15">
                                Payment Method
                            </h5>

                            <div class="bor8 bg0 m-b-12 p-lr-15 p-tb-10">
                                <label class="stext-111 cl2" style="cursor: pointer; display: block;">
                                    <input type="radio" name="payment_method" value="cod" checked
                                        style="margin-right: 10px;">
                                    Cash on Delivery
                                </label>
                            </div>

                            <div class="bor8 bg0 m-b-12 p-lr-15 p-tb-10">
                                <label class="stext-111 cl2" style="cursor: pointer; display: block;">
                                    <input type="radio" name="payment_method" value="bank_transfer"
                                        style="margin-right: 10px;">
                                    Bank Transfer
                                </label>
                            </div>
                        </div>

                        <button type="submit"
                            class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer w-full">
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
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
<script src="js/main.js"></script>

<?php $this->stop() ?>