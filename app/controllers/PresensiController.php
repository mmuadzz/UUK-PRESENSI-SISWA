<?php

declare(strict_types=1);

/**
 * Menangani simulasi tap kartu (input NISN) dan rekap presensi.
 */
final class PresensiController
{
    private Presensi $presensi;

    private Siswa $siswa;

    private Kelas $kelas;

    public function __construct()
    {
        $this->presensi = new Presensi();
        $this->siswa = new Siswa();
        $this->kelas = new Kelas();
    }

    /**
     * @return array<string, mixed>
     */
    public function simulasi(): array
    {
        $hasil = $_SESSION['tap_result'] ?? null;
        unset($_SESSION['tap_result']);

        $today = date('Y-m-d');

        return [
            'riwayat' => $this->presensi->historyToday(8),
            'hasil' => is_array($hasil) ? $hasil : null,
            'statistik' => [
                'hadir' => $this->presensi->countHadir($today),
                'total' => $this->siswa->count(),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function tap(): array
    {
        csrf_verify('presensi/simulasi.php');

        $nisn = trim((string) ($_POST['nisn'] ?? ''));

        if (!preg_match('/^\d{10}$/', $nisn)) {
            return [
                'status' => 'invalid',
                'message' => 'NISN harus terdiri dari tepat 10 digit angka.',
            ];
        }

        $result = $this->presensi->tap($nisn);

        if (isset($result['siswa']) && is_array($result['siswa'])) {
            $siswa = $result['siswa'];
            $result['siswa'] = [
                'nisn' => (string) $siswa['nisn'],
                'nama' => (string) $siswa['nama'],
                'nama_kelas' => (string) ($siswa['nama_kelas'] ?? ''),
                'foto_url' => !empty($siswa['foto']) ? base_url((string) $siswa['foto']) : null,
            ];
        }

        if (!empty($result['waktu'])) {
            $result['waktu_teks'] = format_jam((string) $result['waktu']);
            $result['tanggal_teks'] = format_tanggal((string) $result['waktu'], true);
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function rekap(): array
    {
        $tanggal = trim((string) ($_GET['tanggal'] ?? ''));
        if ($tanggal === '' || !valid_date($tanggal)) {
            $tanggal = date('Y-m-d');
        }

        $kelasParam = (string) ($_GET['kelas'] ?? '');
        $kelasId = null;

        if (ctype_digit($kelasParam) && (int) $kelasParam > 0) {
            $kelasId = (int) $kelasParam;

            if ($this->kelas->find($kelasId) === null) {
                $kelasId = null;
            }
        }

        $data = $this->presensi->rekap($tanggal, $kelasId);

        return [
            'tanggal' => $tanggal,
            'kelas_id' => $kelasId,
            'daftar_kelas' => $this->kelas->all(),
            'rows' => $data['rows'],
            'hadir' => $data['hadir'],
            'tidak_hadir' => $data['tidak_hadir'],
            'total' => $data['total'],
        ];
    }
}
