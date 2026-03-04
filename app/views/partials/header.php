<!DOCTYPE html>
<html lang="en">

<head>
  <title><?= $this->e($title ?? 'CT275 Store') ?></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="<?= \App\CsrfProtection::getToken() ?>">
  <link rel="icon" type="image/png" href="/images/icons/favicon.png" />
  <link rel="stylesheet" type="text/css" href="/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="/fonts/iconic/css/material-design-iconic-font.min.css">
  <link rel="stylesheet" type="text/css" href="/fonts/linearicons-v1.0.0/icon-font.min.css">
  <link rel="stylesheet" type="text/css" href="/vendor/animate/animate.css">
  <link rel="stylesheet" type="text/css" href="/vendor/css-hamburgers/hamburgers.min.css">
  <link rel="stylesheet" type="text/css" href="/vendor/animsition/css/animsition.min.css">
  <link rel="stylesheet" type="text/css" href="/vendor/select2/select2.min.css">
  <link rel="stylesheet" type="text/css" href="/vendor/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" type="text/css" href="/vendor/slick/slick.css">
  <link rel="stylesheet" type="text/css" href="/vendor/MagnificPopup/magnific-popup.css">
  <link rel="stylesheet" type="text/css" href="/vendor/perfect-scrollbar/perfect-scrollbar.css">
  <link rel="stylesheet" type="text/css" href="/css/util.css">
  <link rel="stylesheet" type="text/css" href="/css/main.css">


  <style>
    .auth-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .auth-card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 15px 35px rgba(50, 50, 93, .1), 0 5px 15px rgba(0, 0, 0, .07);
      padding: 40px;
      width: 100%;
      max-width: 450px;
    }

    .auth-card h2 {
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      color: #333;
      margin-bottom: 30px;
      text-align: center;
    }

    .auth-card .form-control {
      height: 50px;
      border: 1px solid #e0e0e0;
      border-radius: 5px;
      padding: 0 15px;
      font-size: 14px;
      transition: all 0.3s;
    }

    .auth-card .form-control:focus {
      border-color: #717fe0;
      box-shadow: 0 0 0 0.2rem rgba(113, 127, 224, 0.25);
    }

    .auth-card .btn-auth {
      width: 100%;
      height: 50px;
      background-color: #717fe0;
      border: none;
      border-radius: 5px;
      color: #fff;
      font-size: 14px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s;
    }

    .auth-card .btn-auth:hover {
      background-color: #5c6bc0;
    }

    .auth-card .form-group {
      margin-bottom: 20px;
    }

    .auth-card label {
      font-size: 13px;
      color: #666;
      margin-bottom: 8px;
      font-weight: 500;
    }

    .auth-links {
      text-align: center;
      margin-top: 20px;
    }

    .auth-links a {
      color: #717fe0;
      text-decoration: none;
      font-size: 14px;
    }

    .auth-links a:hover {
      text-decoration: underline;
    }

    .is-invalid {
      border-color: #dc3545 !important;
    }

    .invalid-feedback {
      color: #dc3545;
      font-size: 12px;
      margin-top: 5px;
    }

    .alert {
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .alert-success {
      background-color: #d4edda;
      border-color: #c3e6cb;
      color: #155724;
    }

    .alert-danger {
      background-color: #f8d7da;
      border-color: #f5c6cb;
      color: #721c24;
    }

    .back-home {
      position: absolute;
      top: 20px;
      left: 20px;
    }

    .back-home a {
      color: #333;
      text-decoration: none;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .back-home a:hover {
      color: #717fe0;
    }
  </style>

  <?= $this->section("page_specific_css") ?>
</head>