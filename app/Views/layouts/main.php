<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Cyklistické závody') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@7.2.3/css/flag-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --red: #e63946;
            --red-dark: #c1121f;
            --dark: #0f0f1a;
            --dark2: #1a1a2e;
            --dark3: #16213e;
            --light-bg: #f0f2f8;
            --card-bg: #ffffff;
            --text-muted2: #6b7280;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background-color: var(--light-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #1a1a2e;
        }

        /*
           NAVBAR
        */
        .navbar {
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark3) 100%) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,.4);
            padding: 0;
        }
        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .9rem 0;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: .6rem;
            text-decoration: none;
        }
        .navbar-brand .brand-icon {
            width: 38px; height: 38px;
            background: var(--red);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            color: #fff;
            box-shadow: 0 2px 8px rgba(230,57,70,.4);
        }
        .navbar-brand .brand-text {
            font-size: 1.2rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.3px;
        }
        .navbar-brand .brand-text span { color: var(--red); }
        .navbar-nav-custom { display: flex; align-items: center; gap: .5rem; }
        .navbar-nav-custom a {
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-size: .9rem;
            font-weight: 500;
            padding: .45rem .9rem;
            border-radius: 8px;
            transition: all .2s;
            display: flex; align-items: center; gap: .4rem;
        }
        .navbar-nav-custom a:hover, .navbar-nav-custom a.active {
            color: #fff;
            background: rgba(255,255,255,.1);
        }
        .navbar-toggler-custom {
            background: rgba(255,255,255,.1);
            border: none;
            border-radius: 8px;
            padding: .4rem .6rem;
            color: #fff;
            font-size: 1.2rem;
            cursor: pointer;
            display: none;
        }
        @media (max-width: 768px) {
            .navbar-toggler-custom { display: flex; }
            .navbar-nav-custom { display: none; }
            .navbar-nav-custom.show { display: flex; flex-direction: column; width: 100%; padding: .5rem 0 1rem; }
        }

        /* 
           HERO BANNER (jen na /races)
        */
        .hero-banner {
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark3) 60%, #1e3a5f 100%);
            color: #fff;
            padding: 3.5rem 0 3rem;
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(230,57,70,.25) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -40px; left: 10%;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(30,58,95,.5) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-banner .container { position: relative; z-index: 1; }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: .5rem;
            background: rgba(230,57,70,.2);
            border: 1px solid rgba(230,57,70,.4);
            border-radius: 20px;
            padding: .25rem .8rem;
            font-size: .78rem;
            font-weight: 600;
            color: #ff8a93;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }
        .hero-title {
            font-size: clamp(2rem, 5vw, 3.2rem);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: .75rem;
            letter-spacing: -1px;
        }
        .hero-title span { color: var(--red); }
        .hero-subtitle {
            font-size: 1.05rem;
            color: rgba(255,255,255,.65);
            max-width: 520px;
            line-height: 1.6;
        }
        .hero-stats {
            display: flex; gap: 2rem; margin-top: 2rem; flex-wrap: wrap;
        }
        .hero-stat {
            text-align: center;
        }
        .hero-stat .num {
            font-size: 1.8rem; font-weight: 800; color: #fff; line-height: 1;
        }
        .hero-stat .num span { color: var(--red); }
        .hero-stat .lbl {
            font-size: .75rem; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: .5px; margin-top: .2rem;
        }
        .hero-icon-big {
            font-size: 8rem;
            color: rgba(255,255,255,.05);
            position: absolute;
            right: 5%;
            top: 50%;
            transform: translateY(-50%);
            line-height: 1;
        }

        /* 
           PAGE HEADER (ostatní stránky)
        */
        .page-header-bar {
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark3) 100%);
            color: #fff;
            padding: 1.8rem 0;
            margin-bottom: 2rem;
        }
        .page-header-bar h1 { font-size: 1.6rem; font-weight: 700; margin: 0; }
        .page-header-bar .breadcrumb { margin: 0; }
        .page-header-bar .breadcrumb-item a { color: rgba(255,255,255,.6); text-decoration: none; }
        .page-header-bar .breadcrumb-item.active { color: rgba(255,255,255,.9); }
        .page-header-bar .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.3); }

        /* 
           RACE CARDS
        */
        .race-card {
            background: var(--card-bg);
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
            transition: transform .18s, box-shadow .18s;
            cursor: pointer;
            height: 100%;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
        }
        .race-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(0,0,0,.13);
            color: inherit;
        }
        .race-card-accent {
            height: 4px;
            background: linear-gradient(90deg, var(--red), #ff6b6b);
        }
        .race-card-body {
            padding: 1.1rem 1.2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .race-card-name {
            font-weight: 700;
            font-size: .95rem;
            line-height: 1.35;
            margin-bottom: .6rem;
            color: var(--dark2);
        }
        .race-card-badge {
            display: inline-block;
            background: #f0f2f8;
            color: #555;
            font-size: .72rem;
            font-weight: 600;
            padding: .2rem .6rem;
            border-radius: 6px;
            margin-bottom: .8rem;
        }
        .race-card-footer {
            padding: .7rem 1.2rem;
            background: #fafbff;
            border-top: 1px solid #f0f2f8;
            font-size: .82rem;
            color: var(--text-muted2);
            display: flex;
            align-items: center;
            gap: .4rem;
        }
        .race-card-footer i { color: var(--red); }

        /*
           TABLE
        */
        .table-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0,0,0,.07);
        }
        .table-card .table { margin: 0; }
        .table-card .table thead th {
            background: var(--dark2) !important;
            color: rgba(255,255,255,.9);
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .6px;
            font-weight: 600;
            border: none;
            padding: 1rem 1.1rem;
            white-space: nowrap;
        }
        .table-card .table tbody tr { transition: background .12s; }
        .table-card .table tbody tr:hover { background: #f5f7ff; }
        .table-card .table td {
            vertical-align: middle;
            padding: .85rem 1.1rem;
            border-color: #f0f2f8;
            font-size: .9rem;
        }

        /* 
           LOGO THUMB
        */
        .logo-thumb {
            max-height: 44px;
            max-width: 90px;
            object-fit: contain;
            border-radius: 4px;
        }

        /*
           FLAGS
        */
        .fi { font-size: 1.2rem; vertical-align: middle; border-radius: 3px; }

        /*
           BUTTONS
        */
        .btn-red {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
            font-weight: 600;
            border-radius: 10px;
            padding: .5rem 1.1rem;
            font-size: .9rem;
            transition: all .2s;
        }
        .btn-red:hover { background: var(--red-dark); border-color: var(--red-dark); color: #fff; }

        /* 
           ALERTS
        */
        .alert { border-radius: 12px; border: none; font-size: .9rem; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger { background: #fee2e2; color: #991b1b; }

        /* 
           PAGINATION
        */
        .pagination .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            border: none;
            color: var(--dark2);
            font-weight: 500;
            font-size: .88rem;
            min-width: 36px;
            text-align: center;
        }
        .pagination .page-item.active .page-link {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
        }
        .pagination .page-link:hover { background: #fee2e2; color: var(--red); }
        .pagination .page-item.disabled .page-link { color: #ccc; }

        /* 
           SECTION TITLE
        */
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark2);
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .section-title::after {
            content: '';
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, #e5e7eb, transparent);
            margin-left: .5rem;
        }

        /* 
           FOOTER
        */
        footer {
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark3) 100%);
            color: rgba(255,255,255,.45);
            padding: 1.5rem 0;
            font-size: .83rem;
            margin-top: auto;
        }
        footer .footer-brand { color: rgba(255,255,255,.8); font-weight: 700; }
        footer .footer-brand i { color: var(--red); }
        footer a { color: rgba(255,255,255,.45); text-decoration: none; }
        footer a:hover { color: var(--red); }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <div class="navbar-inner w-100">
            <a class="navbar-brand" href="<?= base_url('races') ?>">
                <div class="brand-icon"><i class="bi bi-bicycle"></i></div>
                <span class="brand-text">Cycling<span>DB</span></span>
            </a>
            <button class="navbar-toggler-custom" onclick="this.nextElementSibling.classList.toggle('show')">
                <i class="bi bi-list"></i>
            </button>
            <div class="navbar-nav-custom">
                <a href="<?= base_url('races') ?>">
                    <i class="bi bi-flag-fill"></i> Závody
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- FLASH MESSAGES -->
<?php if (session()->getFlashdata('success') || session()->getFlashdata('errors')): ?>
<div class="container mt-3">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0 mt-1">
                <?php foreach ((array)session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<main class="pb-5" style="flex:1">
    <?= $this->renderSection('content') ?>
</main>

<!-- FOOTER -->
<footer>
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <span class="footer-brand"><i class="bi bi-bicycle"></i> CyclingDB</span>
            <span class="mx-2">·</span>
            Databáze cyklistických závodů
        </div>
        <div>&copy; <?= date('Y') ?> &nbsp;·&nbsp; Všechna práva vyhrazena</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>