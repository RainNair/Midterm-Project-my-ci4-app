<?php $pageTitle = $pageTitle ?? 'POS System'; $role = session()->get('role'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle) ?> | POS System</title>
    <style>
        :root { --navy:#172554; --blue:#2563eb; --bg:#f1f5f9; --card:#fff; --text:#1e293b; --muted:#64748b; --danger:#dc2626; }
        * { box-sizing:border-box; } body { margin:0; background:var(--bg); color:var(--text); font:15px/1.5 Arial,sans-serif; }
        .topbar { background:var(--navy); color:white; padding:16px 5%; display:flex; justify-content:space-between; align-items:center; gap:20px; flex-wrap:wrap; }
        .brand { color:white; font-weight:bold; font-size:20px; text-decoration:none; } .topbar nav { display:flex; gap:12px; flex-wrap:wrap; align-items:center; }
        .topbar nav a { color:#dbeafe; text-decoration:none; padding:6px 9px; border-radius:6px; } .topbar nav a:hover { background:#1e40af; }
        .role-badge { background:#fbbf24; color:#422006; border-radius:999px; padding:3px 9px; font-size:12px; font-weight:bold; text-transform:uppercase; }
        main { width:min(1100px,92%); margin:30px auto; } .card { background:var(--card); border-radius:12px; padding:24px; box-shadow:0 4px 18px #0f172a12; }
        h1 { margin-top:0; color:var(--navy); } h2 { color:var(--navy); } a { color:var(--blue); } label { display:block; font-weight:bold; margin:12px 0 5px; }
        input,select { width:100%; max-width:520px; padding:10px; border:1px solid #cbd5e1; border-radius:6px; background:white; } button,.button { background:var(--blue); color:#fff; border:0; border-radius:6px; padding:10px 15px; cursor:pointer; text-decoration:none; display:inline-block; }
        button.danger,.danger { background:var(--danger); } .actions { display:flex; gap:8px; align-items:center; flex-wrap:wrap; } table { width:100%; border-collapse:collapse; background:white; } th,td { padding:11px; border-bottom:1px solid #e2e8f0; text-align:left; vertical-align:middle; } th { background:#e0e7ff; color:var(--navy); }
        .notice { padding:12px 15px; border-radius:7px; margin-bottom:18px; } .success { background:#dcfce7; color:#166534; } .error { background:#fee2e2; color:#991b1b; } .muted { color:var(--muted); }
        footer { color:var(--muted); text-align:center; padding:20px; font-size:13px; } img.thumb { object-fit:cover; border-radius:6px; }
    </style>
</head>
<body>
<?php if (session()->get('logged_in')): ?>
<header class="topbar">
    <a class="brand" href="<?= site_url('dashboard') ?>">POS System</a>
    <nav>
        <a href="<?= site_url('dashboard') ?>">Dashboard</a>
        <a href="<?= site_url('products') ?>">Products</a>
        <a href="<?= site_url('customers') ?>">Customers</a>
        <a href="<?= site_url('sales/history') ?>">Sales</a>
        <?php if ($role === 'admin'): ?><a href="<?= site_url('staff') ?>">Staff</a><?php endif; ?>
        <span class="role-badge"><?= esc($role ?: 'staff') ?></span>
        <a href="<?= site_url('logout') ?>">Logout</a>
    </nav>
</header>
<?php endif; ?>
<main>
<?php if (session()->getFlashdata('success')): ?><div class="notice success"><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
<?php if (session()->getFlashdata('error')): ?><div class="notice error"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>
