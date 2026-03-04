<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }

    /**
     * Hiển thị trang admin dashboard
     */
    public function index()
    {
        $this->sendPage('admin/index', [
            'title' => 'Trang Quản Trị'
        ]);
    }
}

