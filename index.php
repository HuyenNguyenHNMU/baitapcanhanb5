<?php
session_start();

// DỮ LIỆU MẪU ĐỂ LÀM DEMO TĨNH (KHÔNG CẦN KẾT NỐI DATABASE)
$ds_hoc_ky = [
    ['id' => 1, 'ten_hoc_ky' => 'Học kỳ 1 (2026 - 2027)'],
    ['id' => 2, 'ten_hoc_ky' => 'Học kỳ 2 (2026 - 2027)']
];

$ds_hoc_phan = [
    ['id' => 1, 'ma_hoc_phan' => 'MAT101', 'ten_hoc_phan' => 'Toán Cao Cấp', 'so_tin_chi' => 3],
    ['id' => 2, 'ma_hoc_phan' => 'WEB102', 'ten_hoc_phan' => 'Lập Trình Web Cơ Bản', 'so_tin_chi' => 3],
    ['id' => 3, 'ma_hoc_phan' => 'DB101', 'ten_hoc_phan' => 'Cơ Sở Dữ Liệu Quan Hệ', 'so_tin_chi' => 3],
    ['id' => 4, 'ma_hoc_phan' => 'DSA102', 'ten_hoc_phan' => 'Cấu Trúc Dữ Liệu & Giải Thuật', 'so_tin_chi' => 4]
];

$ds_giang_vien = [
    ['id' => 1, 'ho_ten' => 'ThS Trần Bình Trọng', 'email' => 'trong.tb@studenthub.edu.vn'],
    ['id' => 2, 'ho_ten' => 'TS Lê Phương Hoa', 'email' => 'hoa.lp@studenthub.edu.vn'],
    ['id' => 3, 'ho_ten' => 'PGS.TS Nguyễn Văn An', 'email' => 'an.nv@studenthub.edu.vn']
];

// Khởi tạo session lưu danh sách lớp demo để thao tác Thêm / Sửa / Xóa sống
if (!isset($_SESSION['demo_lop_hoc_phan'])) {
    $_SESSION['demo_lop_hoc_phan'] = [
        [
            'id' => 1, 'ma_lop' => 'LHP-WEB01', 'hoc_phan_id' => 2, 'hoc_ky_id' => 1, 'giang_vien_id' => 2,
            'si_so_toi_da' => 40, 'si_so_hien_tai' => 35, 'trang_thai' => 'mo',
            'lich_hoc' => 'Thứ 2 & Thứ 4 (Tiết 1 - 3, Sáng)', 'phong_hoc' => 'Lab 402 - Nhà C',
            'ten_hoc_phan' => 'Lập Trình Web Cơ Bản', 'ma_hoc_phan' => 'WEB102', 'so_tin_chi' => 3,
            'ten_hoc_ky' => 'Học kỳ 1 (2026 - 2027)', 'ten_giang_vien' => 'TS Lê Phương Hoa', 'email_giang_vien' => 'hoa.lp@studenthub.edu.vn'
        ],
        [
            'id' => 2, 'ma_lop' => 'LHP-DB01', 'hoc_phan_id' => 3, 'hoc_ky_id' => 1, 'giang_vien_id' => 3,
            'si_so_toi_da' => 40, 'si_so_hien_tai' => 22, 'trang_thai' => 'mo',
            'lich_hoc' => 'Thứ 3 & Thứ 5 (Tiết 1 - 3, Sáng)', 'phong_hoc' => 'Phòng 301 - Tòa A',
            'ten_hoc_phan' => 'Cơ Sở Dữ Liệu Quan Hệ', 'ma_hoc_phan' => 'DB101', 'so_tin_chi' => 3,
            'ten_hoc_ky' => 'Học kỳ 1 (2026 - 2027)', 'ten_giang_vien' => 'PGS.TS Nguyễn Văn An', 'email_giang_vien' => 'an.nv@studenthub.edu.vn'
        ],
        [
            'id' => 3, 'ma_lop' => 'LHP-DSA01', 'hoc_phan_id' => 4, 'hoc_ky_id' => 1, 'giang_vien_id' => 1,
            'si_so_toi_da' => 45, 'si_so_hien_tai' => 45, 'trang_thai' => 'khoa',
            'lich_hoc' => 'Thứ 2 & Thứ 6 (Tiết 4 - 6, Sáng)', 'phong_hoc' => 'Hội trường B2',
            'ten_hoc_phan' => 'Cấu Trúc Dữ Liệu & Giải Thuật', 'ma_hoc_phan' => 'DSA102', 'so_tin_chi' => 4,
            'ten_hoc_ky' => 'Học kỳ 1 (2026 - 2027)', 'ten_giang_vien' => 'ThS Trần Bình Trọng', 'email_giang_vien' => 'trong.tb@studenthub.edu.vn'
        ]
    ];
}

$notice = '';
$noticeType = 'success';

// Xử lý hành động POST (Thêm / Sửa / Xóa / Đổi trạng thái Mở - Khóa)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'store' || $action === 'update') {
        $id          = (int)($_POST['id'] ?? 0);
        $maLop       = strtoupper(trim($_POST['ma_lop'] ?? ''));
        $hocPhanId   = (int)($_POST['hoc_phan_id'] ?? 0);
        $hocKyId     = (int)($_POST['hoc_ky_id'] ?? 0);
        $giangVienId = (int)($_POST['giang_vien_id'] ?? 0);
        $siSo        = (int)($_POST['si_so_toi_da'] ?? 40);
        $lichHoc     = trim($_POST['lich_hoc'] ?? '');
        $phongHoc    = trim($_POST['phong_hoc'] ?? '');
        $trangThai   = $_POST['trang_thai'] ?? 'mo';

        // Tìm thông tin phụ trợ từ ID
        $hp_info = array_values(array_filter($ds_hoc_phan, fn($item) => $item['id'] === $hocPhanId))[0] ?? $ds_hoc_phan[0];
        $hk_info = array_values(array_filter($ds_hoc_ky, fn($item) => $item['id'] === $hocKyId))[0] ?? $ds_hoc_ky[0];
        $gv_info = array_values(array_filter($ds_giang_vien, fn($item) => $item['id'] === $giangVienId))[0] ?? $ds_giang_vien[0];

        if ($action === 'store') {
            $new_item = [
                'id' => time(),
                'ma_lop' => $maLop,
                'hoc_phan_id' => $hocPhanId,
                'hoc_ky_id' => $hocKyId,
                'giang_vien_id' => $giangVienId,
                'si_so_toi_da' => $siSo,
                'si_so_hien_tai' => 0,
                'trang_thai' => $trangThai,
                'lich_hoc' => $lichHoc,
                'phong_hoc' => $phongHoc,
                'ten_hoc_phan' => $hp_info['ten_hoc_phan'],
                'ma_hoc_phan' => $hp_info['ma_hoc_phan'],
                'so_tin_chi' => $hp_info['so_tin_chi'],
                'ten_hoc_ky' => $hk_info['ten_hoc_ky'],
                'ten_giang_vien' => $gv_info['ho_ten'],
                'email_giang_vien' => $gv_info['email']
            ];
            array_unshift($_SESSION['demo_lop_hoc_phan'], $new_item);
            $notice = "Thêm mới lớp học phần '$ma_lop' thành công!";
        } else {
            foreach ($_SESSION['demo_lop_hoc_phan'] as &$item) {
                if ($item['id'] === $id) {
                    $item['ma_lop'] = $maLop;
                    $item['hoc_phan_id'] = $hocPhanId;
                    $item['hoc_ky_id'] = $hocKyId;
                    $item['giang_vien_id'] = $giangVienId;
                    $item['si_so_toi_da'] = $siSo;
                    $item['trang_thai'] = $trangThai;
                    $item['lich_hoc'] = $lichHoc;
                    $item['phong_hoc'] = $phongHoc;
                    $item['ten_hoc_phan'] = $hp_info['ten_hoc_phan'];
                    $item['ma_hoc_phan'] = $hp_info['ma_hoc_phan'];
                    $item['so_tin_chi'] = $hp_info['so_tin_chi'];
                    $item['ten_hoc_ky'] = $hk_info['ten_hoc_ky'];
                    $item['ten_giang_vien'] = $gv_info['ho_ten'];
                    $item['email_giang_vien'] = $gv_info['email'];
                    break;
                }
            }
            $notice = "Cập nhật lớp học phần '$ma_lop' thành công!";
        }
    } elseif ($action === 'toggle_status') {
        $id = (int)($_POST['id'] ?? 0);
        foreach ($_SESSION['demo_lop_hoc_phan'] as &$item) {
            if ($item['id'] === $id) {
                $item['trang_thai'] = ($item['trang_thai'] === 'mo') ? 'khoa' : 'mo';
                $notice = "Đã chuyển đổi trạng thái cầu dao lớp '{$item['ma_lop']}' thành công!";
                break;
            }
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $_SESSION['demo_lop_hoc_phan'] = array_values(array_filter($_SESSION['demo_lop_hoc_phan'], fn($item) => $item['id'] !== $id));
        $notice = "Đã xóa lớp học phần thành công!";
    }
    header('Location: index.php');
    exit;
}

// Xử lý bộ lọc tìm kiếm giao diện
$tu_khoa       = trim($_GET['tu_khoa'] ?? '');
$hoc_ky_id     = $_GET['hoc_ky_id'] ?? '';
$trang_thai    = $_GET['trang_thai'] ?? '';

$classes = array_filter($_SESSION['demo_lop_hoc_phan'], function($item) use ($tu_khoa, $hoc_ky_id, $trang_thai) {
    if ($tu_khoa !== '') {
        $match = str_contains(strtolower($item['ma_lop']), strtolower($tu_khoa)) || 
                 str_contains(strtolower($item['ten_hoc_phan']), strtolower($tu_khoa));
        if (!$match) return false;
    }
    if ($hoc_ky_id !== '' && (int)$item['hoc_ky_id'] !== (int)$hoc_ky_id) return false;
    if ($trang_thai !== '' && $item['trang_thai'] !== $trang_thai) return false;
    return true;
});

// Thống kê số liệu
$tong_lop = count($_SESSION['demo_lop_hoc_phan']);
$lop_mo = count(array_filter($_SESSION['demo_lop_hoc_phan'], fn($i) => $i['trang_thai'] === 'mo'));
$lop_khoa = count(array_filter($_SESSION['demo_lop_hoc_phan'], fn($i) => $i['trang_thai'] === 'khoa'));
$tong_sv = array_sum(array_column($_SESSION['demo_lop_hoc_phan'], 'si_so_hien_tai'));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh sách lớp học phần - Demo Quản trị viên</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --navy-sidebar: #0c192c;
            --blue-active: #2563eb;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --background: #f8fafc;
            --white: #ffffff;
            --green-status: #10b981;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--background); color: var(--text); font-size: 14px; }
        a { color: inherit; text-decoration: none; }
        .layout { display: flex; min-height: 100vh; align-items: flex-start; }

        /* SIDEBAR */
        .sidebar {
            width: 260px; height: 100vh; flex-shrink: 0; display: flex; flex-direction: column;
            justify-content: space-between; padding: 24px 16px; background-color: var(--navy-sidebar);
            color: #fff; position: sticky; top: 0; overflow-y: auto;
        }
        .brand { display: flex; align-items: center; gap: 12px; padding: 4px 8px 24px 8px; }
        .brand-icon {
            width: 44px; height: 44px; display: grid; place-items: center; border-radius: 14px;
            background: var(--blue-active); color: #fff; font-size: 22px; font-weight: 800;
        }
        .brand strong { font-size: 20px; font-weight: 800; line-height: 1.2; }
        .brand small { color: #94a3b8; font-size: 12px; }
        .menu-title { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin: 16px 0 10px 12px; }
        .menu { display: flex; flex-direction: column; gap: 6px; }
        .menu-link {
            min-height: 44px; display: flex; align-items: center; gap: 12px; padding: 12px 16px;
            border-radius: 12px; color: #94a3b8; font-size: 13.5px; font-weight: 600; transition: all 0.2s;
        }
        .menu-link:hover, .menu-link.active { background: var(--blue-active); color: #fff; font-weight: 700; }
        .sidebar-bottom { margin-top: auto; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px; }

        /* MAIN */
        .main { flex: 1; min-width: 0; }
        .topbar {
            min-height: 108px; display: flex; justify-content: space-between; align-items: center;
            padding: 24px 34px; background: var(--white); border-bottom: 1px solid var(--border);
        }
        .breadcrumb { color: var(--muted); font-size: 12px; margin-bottom: 4px; }
        .topbar h1 { color: #0f172a; font-size: 24px; font-weight: 800; }
        .admin-profile { display: flex; align-items: center; gap: 12px; }
        .avatar { width: 42px; height: 42px; display: grid; place-items: center; border-radius: 50%; background: #dbeafe; color: #1d4ed8; font-weight: 800; }

        .content { max-width: 1480px; margin: 0 auto; padding: 28px 34px; }

        /* KPI */
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 28px; }
        .stat-card { padding: 22px; border: 1px solid var(--border); border-radius: 14px; background: var(--white); }
        .stat-top { display: flex; align-items: center; justify-content: space-between; }
        .stat-label { color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase; }
        .stat-card h3 { margin-top: 12px; font-size: 30px; font-weight: 800; color: #0f172a; }
        .stat-card p { margin-top: 6px; color: #94a3b8; font-size: 12px; }

        /* SECTION BAR */
        .section-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .section-bar h2 { font-size: 19px; font-weight: 800; }
        .top-actions { display: flex; gap: 10px; }
        .btn-add-new { background: #2563eb; color: #fff; padding: 10px 18px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; }
        .btn-export-pdf { background: #fff; color: #dc2626; border: 1px solid #fca5a5; padding: 10px 16px; border-radius: 10px; font-weight: 700; cursor: pointer; }

        /* FILTER */
        .filter-box { background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 16px; margin-bottom: 20px; display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 10px; }
        .filter-control { height: 40px; padding: 0 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 13px; outline: none; width: 100%; }
        .btn-filter { height: 40px; padding: 0 20px; background: #2563eb; color: #fff; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; }

        /* TABLE */
        .table-card { background: #fff; border: 1px solid var(--border); border-radius: 14px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 1050px; }
        thead { background: #f8fafc; border-bottom: 1px solid var(--border); }
        th { padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; }
        td { padding: 16px; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #334155; vertical-align: middle; }
        tbody tr:hover { background: #f8fafc; }

        .code-badge { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-weight: 800; font-size: 12px; padding: 6px 10px; border-radius: 8px; }
        .course-name { font-weight: 700; color: #0f172a; }
        .course-sub { font-size: 12px; color: #64748b; }
        .status-pill { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 99px; font-size: 12px; font-weight: 700; }
        .status-pill.open { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .status-pill.locked { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

        .btn-toggle-lock { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 8px; font-weight: 700; cursor: pointer; }
        .btn-toggle-open { background: #d1fae5; color: #047857; border: 1px solid #a7f3d0; padding: 6px 12px; border-radius: 8px; font-weight: 700; cursor: pointer; }
        .btn-edit { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; padding: 5px 12px; border-radius: 6px; font-weight: 700; cursor: pointer; }
        .btn-delete { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; padding: 5px 12px; border-radius: 6px; font-weight: 700; cursor: pointer; }

        /* MODAL */
        .modal-backdrop { position: fixed; inset: 0; background: rgba(15,23,42,0.55); display: none; place-items: center; z-index: 999; padding: 20px; }
        .modal-backdrop.active { display: grid; }
        .modal-box { background: #fff; border-radius: 16px; width: 100%; max-width: 620px; padding: 26px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 14px; }
        .form-field { display: flex; flex-direction: column; gap: 6px; }
        .form-field.full { grid-column: span 2; }
        .form-field label { font-size: 12.5px; font-weight: 700; color: #334155; }
    </style>
</head>
<body>

<div class="layout">
    <aside class="sidebar">
        <div>
            <div class="brand">
                <div class="brand-icon">S</div>
                <div>
                    <strong>StudentHub</strong>
                    <small>Cổng quản trị (Demo)</small>
                </div>
            </div>
            <div class="menu-title">QUẢN TRỊ HỆ THỐNG</div>
            <nav class="menu">
                <a href="#" class="menu-link"><span>🏠</span> Tổng quan</a>
                <a href="#" class="menu-link"><span>👥</span> Quản lý tài khoản</a>
                <a href="#" class="menu-link"><span>📘</span> Quản lý học phần</a>
                <a href="#" class="menu-link active"><span>🎓</span> Quản lý lớp học phần</a>
            </nav>
        </div>
        <div class="sidebar-bottom">
            <a href="#" class="menu-link" style="color: #f87171;"><span>↪</span> Đăng xuất</a>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <div>
                <div class="breadcrumb">Trang chủ / Quản lý lớp học phần</div>
                <h1>Quản lý danh sách lớp học phần (Demo)</h1>
            </div>
            <div class="admin-profile">
                <div class="avatar">QT</div>
                <div>
                    <strong>Quản trị viên Hệ thống</strong>
                    <span style="font-size:11.5px; color:#64748b;">Phiên làm việc an toàn</span>
                </div>
            </div>
        </header>

        <main class="content">
            <?php if ($notice !== ''): ?>
                <div style="padding:14px; background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; border-radius:10px; margin-bottom:20px; font-weight:600;">
                    <?= htmlspecialchars($notice) ?>
                </div>
            <?php endif; ?>

            <!-- 4 THẺ KPI -->
            <div class="stats">
                <div class="stat-card">
                    <span class="stat-label">TỔNG SỐ LỚP</span>
                    <h3><?= $tong_lop ?></h3>
                    <p>Lớp học phần trên hệ thống</p>
                </div>
                <div class="stat-card">
                    <span class="stat-label">LỚP ĐANG MỞ</span>
                    <h3><?= $lop_mo ?></h3>
                    <p>Sinh viên đăng ký bình thường</p>
                </div>
                <div class="stat-card">
                    <span class="stat-label">LỚP ĐANG KHÓA</span>
                    <h3><?= $lop_khoa ?></h3>
                    <p>Đóng cổng đăng ký</p>
                </div>
                <div class="stat-card">
                    <span class="stat-label">LƯỢT GHI DANH</span>
                    <h3><?= $tong_sv ?></h3>
                    <p>Tổng số sinh viên đăng ký</p>
                </div>
            </div>

            <div class="section-bar">
                <h2>Danh sách lớp học phần đào tạo</h2>
                <div class="top-actions">
                    <button type="button" class="btn-add-new" onclick="openModal()">+ Thêm lớp học phần mới</button>
                    <button type="button" class="btn-export-pdf" onclick="window.print()">📄 Xuất danh sách (PDF)</button>
                </div>
            </div>

            <!-- BỘ LỌC -->
            <form method="GET" action="index.php" class="filter-box">
                <input type="text" name="tu_khoa" class="filter-control" placeholder="Tìm theo mã lớp, tên môn..." value="<?= htmlspecialchars($tu_khoa) ?>">
                <select name="hoc_ky_id" class="filter-control" onchange="this.form.submit()">
                    <option value="">-- Tất cả học kỳ --</option>
                    <?php foreach ($ds_hoc_ky as $hk): ?>
                        <option value="<?= $hk['id'] ?>" <?= (int)$hoc_ky_id === $hk['id'] ? 'selected' : '' ?>><?= $hk['ten_hoc_ky'] ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="trang_thai" class="filter-control" onchange="this.form.submit()">
                    <option value="">-- Trạng thái --</option>
                    <option value="mo" <?= $trang_thai === 'mo' ? 'selected' : '' ?>>Đang mở</option>
                    <option value="khoa" <?= $trang_thai === 'khoa' ? 'selected' : '' ?>>Đã khóa</option>
                </select>
                <button type="submit" class="btn-filter">Lọc</button>
            </form>

            <!-- BẢNG DỮ LIỆU -->
            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>Mã lớp</th>
                            <th>Môn học &amp; Số TC</th>
                            <th>Học kỳ</th>
                            <th>Giảng viên</th>
                            <th>Lịch học &amp; Phòng</th>
                            <th>Sĩ số / Chỗ</th>
                            <th>Trạng thái</th>
                            <th>Cầu dao Mở/Khóa</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($classes)): ?>
                            <tr><td colspan="9" style="text-align:center; padding:30px; color:#64748b;">Không tìm thấy lớp học phần nào.</td></tr>
                        <?php else: ?>
                            <?php foreach ($classes as $row): 
                                $conLai = max(0, $row['si_so_toi_da'] - $row['si_so_hien_tai']);
                                $isOpen = ($row['trang_thai'] === 'mo');
                            ?>
                                <tr>
                                    <td><span class="code-badge"><?= htmlspecialchars($row['ma_lop']) ?></span></td>
                                    <td>
                                        <span class="course-name"><?= htmlspecialchars($row['ten_hoc_phan']) ?></span>
                                        <span class="course-sub">Mã: <?= htmlspecialchars($row['ma_hoc_phan']) ?> • <b><?= $row['so_tin_chi'] ?> TC</b></span>
                                    </td>
                                    <td><?= htmlspecialchars($row['ten_hoc_ky']) ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['ten_giang_vien']) ?></strong><br>
                                        <span style="font-size:11.5px; color:#64748b;"><?= htmlspecialchars($row['email_giang_vien']) ?></span>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['lich_hoc']) ?></strong><br>
                                        <span style="font-size:12px; color:#64748b;"><?= htmlspecialchars($row['phong_hoc']) ?></span>
                                    </td>
                                    <td>
                                        <b><?= $row['si_so_hien_tai'] ?></b> / <?= $row['si_so_toi_da'] ?> 
                                        <span style="color:#059669; font-size:12px;">(Còn <?= $conLai ?>)</span>
                                    </td>
                                    <td>
                                        <span class="status-pill <?= $isOpen ? 'open' : 'locked' ?>">
                                            ● <?= $isOpen ? 'Đang mở' : 'Đã khóa' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="index.php">
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <?php if ($isOpen): ?>
                                                <button type="submit" class="btn-toggle-lock">🔒 Khóa lớp</button>
                                            <?php else: ?>
                                                <button type="submit" class="btn-toggle-open">🔓 Mở lớp</button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                    <td>
                                        <div style="display:flex; gap:6px;">
                                            <button type="button" class="btn-edit" onclick='openModal(<?= json_encode($row) ?>)'>Sửa</button>
                                            <form method="POST" action="index.php" onsubmit="return confirm('Xóa lớp này?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn-delete">Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- MODAL FORM -->
<div class="modal-backdrop" id="classModal">
    <div class="modal-box">
        <h3 id="modalTitle" style="font-size:18px; font-weight:800; margin-bottom:14px;">Thêm lớp học phần mới</h3>
        <form method="POST" action="index.php">
            <input type="hidden" name="action" id="f_action" value="store">
            <input type="hidden" name="id" id="f_id" value="0">
            <div class="form-grid">
                <div class="form-field">
                    <label>Mã lớp (*)</label>
                    <input type="text" name="ma_lop" id="f_ma_lop" class="filter-control" placeholder="VD: LHP-WEB03" required>
                </div>
                <div class="form-field">
                    <label>Sĩ số tối đa (*)</label>
                    <input type="number" name="si_so_toi_da" id="f_si_so" class="filter-control" value="40" required>
                </div>
                <div class="form-field full">
                    <label>Hôn học phần (*)</label>
                    <select name="hoc_phan_id" id="f_hoc_phan_id" class="filter-control" required>
                        <?php foreach ($ds_hoc_phan as $hp): ?>
                            <option value="<?= $hp['id'] ?>"><?= $hp['ma_hoc_phan'] . ' - ' . $hp['ten_hoc_phan'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-field">
                    <label>Học kỳ (*)</label>
                    <select name="hoc_ky_id" id="f_hoc_ky_id" class="filter-control" required>
                        <?php foreach ($ds_hoc_ky as $hk): ?>
                            <option value="<?= $hk['id'] ?>"><?= $hk['ten_hoc_ky'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-field">
                    <label>Giảng viên (*)</label>
                    <select name="giang_vien_id" id="f_giang_vien_id" class="filter-control" required>
                        <?php foreach ($ds_giang_vien as $gv): ?>
                            <option value="<?= $gv['id'] ?>"><?= $gv['ho_ten'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-field">
                    <label>Lịch học (*)</label>
                    <input type="text" name="lich_hoc" id="f_lich_hoc" class="filter-control" placeholder="Thứ 2 & 4 (Tiết 1-3)" required>
                </div>
                <div class="form-field">
                    <label>Phòng học (*)</label>
                    <input type="text" name="phong_hoc" id="f_phong_hoc" class="filter-control" placeholder="Lab 402" required>
                </div>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <button type="button" onclick="closeModal()" class="filter-control" style="width:auto; cursor:pointer;">Hủy</button>
                <button type="submit" class="btn-add-new">Lưu dữ liệu</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(row = null) {
        if (row) {
            document.getElementById('modalTitle').textContent = 'Cập nhật lớp: ' + row.ma_lop;
            document.getElementById('f_action').value = 'update';
            document.getElementById('f_id').value = row.id;
            document.getElementById('f_ma_lop').value = row.ma_lop;
            document.getElementById('f_si_so').value = row.si_so_toi_da;
            document.getElementById('f_hoc_phan_id').value = row.hoc_phan_id;
            document.getElementById('f_hoc_ky_id').value = row.hoc_ky_id;
            document.getElementById('f_giang_vien_id').value = row.giang_vien_id;
            document.getElementById('f_lich_hoc').value = row.lich_hoc;
            document.getElementById('f_phong_hoc').value = row.phong_hoc;
        } else {
            document.getElementById('modalTitle').textContent = 'Thêm lớp học phần mới';
            document.getElementById('f_action').value = 'store';
            document.getElementById('f_id').value = 0;
            document.getElementById('f_ma_lop').value = '';
            document.getElementById('f_si_so').value = 40;
            document.getElementById('f_lich_hoc').value = '';
            document.getElementById('f_phong_hoc').value = '';
        }
        document.getElementById('classModal').classList.add('active');
    }
    function closeModal() {
        document.getElementById('classModal').classList.remove('active');
    }
</script>
</body>
</html>