<?php
include_once __DIR__ . '/../partials/header.php';
?>

<body>
    <?= $this->section("page") ?>
    <?php include_once __DIR__ . '/../partials/footer.php'; ?>

    <!-- Scripts -->
    <script src="/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="/vendor/animsition/js/animsition.min.js"></script>
    <script src="/vendor/select2/select2.min.js"></script>
    <script src="/vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
    <script src="/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/js/main.js"></script>

    <?= $this->section("page_specific_js") ?>
</body>

</html>