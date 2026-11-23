<?php

declare(strict_types=1);
?>
        </main>

        <footer class="app-footer">
            <span>&copy; <?= e((string) date('Y')) ?> <?= e(APP_SCHOOL) ?></span>
            <span class="app-footer-note">Aplikasi Presensi Siswa &middot; Data demo</span>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(asset_url('js/app.js')) ?>"></script>
</body>
</html>
