<?php

declare(strict_types=1);

// Pintu masuk alternatif ketika folder proyek dibuka langsung dari htdocs.
// Konfigurasi yang disarankan tetap mengarahkan document root ke folder public/.
header('Location: public/');
exit;
