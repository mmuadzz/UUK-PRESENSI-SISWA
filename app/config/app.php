<?php

declare(strict_types=1);

return [
    'name' => 'Presensi Siswa SMK TI Airlangga',
    'short_name' => 'Presensi Siswa',
    'school' => 'SMK TI Airlangga Samarinda',
    'timezone' => 'Asia/Makassar',

    // Ubah menjadi "1" saat pengembangan untuk melihat detail error.
    'debug' => filter_var(getenv('APP_DEBUG') ?: '0', FILTER_VALIDATE_BOOL),

    // Kosongkan untuk deteksi otomatis berdasarkan document root.
    // Contoh jika dijalankan di subfolder: '/presensi-smk/public'
    'base_url' => getenv('APP_BASE_URL') ?: '',
];
