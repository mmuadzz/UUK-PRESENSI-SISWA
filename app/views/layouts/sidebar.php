<?php

declare(strict_types=1);

/** @var string $activeMenu */

$currentUser = auth_user();

$menuItems = [
    [
        'key' => 'dashboard',
        'label' => 'Dashboard',
        'icon' => 'bi-grid-1x2',
        'url' => 'dashboard.php',
    ],
    [
        'key' => 'kelas',
        'label' => 'Data Kelas',
        'icon' => 'bi-buildings',
        'url' => 'kelas/index.php',
    ],
    [
        'key' => 'siswa',
        'label' => 'Data Siswa',
        'icon' => 'bi-people',
        'url' => 'siswa/index.php',
    ],
    [
        'key' => 'simulasi',
        'label' => 'Simulasi Presensi',
        'icon' => 'bi-upc-scan',
        'url' => 'presensi/simulasi.php',
    ],
    [
        'key' => 'rekap',
        'label' => 'Rekap Presensi',
        'icon' => 'bi-clipboard-data',
        'url' => 'presensi/rekap.php',
    ],
];
?>
<aside class="offcanvas-lg offcanvas-start app-sidebar" tabindex="-1" id="appSidebar"
       aria-labelledby="appSidebarLabel">
    <div class="sidebar-brand">
        <span class="brand-mark"><i class="bi bi-mortarboard-fill"></i></span>
        <div class="min-w-0">
            <div class="brand-school" id="appSidebarLabel">SMK TI Airlangga</div>
            <div class="brand-app">Presensi Siswa</div>
        </div>
        <button type="button" class="btn-close btn-close-white sidebar-close d-lg-none"
                data-bs-dismiss="offcanvas" data-bs-target="#appSidebar" aria-label="Tutup menu"></button>
    </div>

    <div class="sidebar-section-label">Menu Utama</div>

    <nav class="sidebar-nav" aria-label="Navigasi utama">
        <?php foreach ($menuItems as $item): ?>
            <a class="sidebar-link<?= $activeMenu === $item['key'] ? ' active' : '' ?>"
               href="<?= e(base_url($item['url'])) ?>"
               <?= $activeMenu === $item['key'] ? 'aria-current="page"' : '' ?>>
                <i class="bi <?= e($item['icon']) ?>" aria-hidden="true"></i>
                <span><?= e($item['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <span class="avatar avatar-sm avatar-initials avatar-on-dark">
                <?= e(initials((string) ($currentUser['nama_lengkap'] ?? 'Administrator'))) ?>
            </span>
            <div class="min-w-0">
                <div class="sidebar-user-name text-truncate"><?= e($currentUser['nama_lengkap'] ?? 'Administrator') ?></div>
                <div class="sidebar-user-role">Administrator</div>
            </div>
        </div>

        <form method="post" action="<?= e(base_url('logout.php')) ?>">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-logout w-100">
                <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
