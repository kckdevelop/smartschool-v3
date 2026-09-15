<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\Kemajuan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotifikasiService
{
    /**
     * Kirim notifikasi umum ke guru.
     */
    public static function sendToGuru(
        int $idGuru,
        string $judul,
        string $pesan,
        string $tipe = 'general',
        ?int $referensiId = null,
        ?array $data = null
    ): ?Notifikasi {
        try {
            return Notifikasi::create([
                'id_guru'      => $idGuru,
                'role'         => 'guru',
                'judul'        => $judul,
                'pesan'        => $pesan,
                'tipe'         => $tipe,
                'referensi_id' => $referensiId,
                'data'         => $data,
                'is_read'      => false,
            ]);
        } catch (\Throwable $e) {
            Log::error('[NotifikasiService] Gagal mengirim notifikasi ke guru: ' . $e->getMessage(), [
                'id_guru' => $idGuru,
                'judul'   => $judul,
                'trace'   => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Kirim notifikasi ketika jurnal mengajar disetujui admin.
     */
    public static function notifyJurnalApproved(Kemajuan $jurnal): ?Notifikasi
    {
        $jurnal->loadMissing(['mapel', 'kelas', 'guru']);

        $mapelNama = $jurnal->mapel?->nama_mapel ?? 'Mata Pelajaran';
        $kelasNama = $jurnal->kelas?->nama_kelas ?? 'Kelas';
        $tanggal   = Carbon::parse($jurnal->tanggal)->locale('id')->isoFormat('D MMMM Y');
        $jamKe     = $jurnal->jam_ke ?? '-';

        $judul = 'Jurnal Mengajar Disetujui';
        $pesan = "Jurnal mengajar {$mapelNama} kelas {$kelasNama} (Jam ke-{$jamKe}) pada tanggal {$tanggal} telah disetujui oleh admin.";

        $dataPayload = [
            'id_jurnal' => $jurnal->id_kemajuan,
            'tanggal'   => is_string($jurnal->tanggal) ? $jurnal->tanggal : $jurnal->tanggal->format('Y-m-d'),
            'id_kelas'  => $jurnal->id_kelas,
            'kelas'     => $kelasNama,
            'id_mapel'  => $jurnal->id_mapel,
            'mapel'     => $mapelNama,
            'jam_ke'    => $jamKe,
            'materi'    => $jurnal->materi,
            'status'    => 'approved',
        ];

        return self::sendToGuru(
            idGuru: $jurnal->id_guru,
            judul: $judul,
            pesan: $pesan,
            tipe: 'jurnal_approved',
            referensiId: $jurnal->id_kemajuan,
            data: $dataPayload
        );
    }

    /**
     * Kirim notifikasi ketika jurnal mengajar ditolak admin.
     */
    public static function notifyJurnalRejected(Kemajuan $jurnal): ?Notifikasi
    {
        $jurnal->loadMissing(['mapel', 'kelas', 'guru']);

        $mapelNama = $jurnal->mapel?->nama_mapel ?? 'Mata Pelajaran';
        $kelasNama = $jurnal->kelas?->nama_kelas ?? 'Kelas';
        $tanggal   = Carbon::parse($jurnal->tanggal)->locale('id')->isoFormat('D MMMM Y');
        $jamKe     = $jurnal->jam_ke ?? '-';

        $judul = 'Jurnal Mengajar Ditolak';
        $pesan = "Jurnal mengajar {$mapelNama} kelas {$kelasNama} (Jam ke-{$jamKe}) pada tanggal {$tanggal} ditolak oleh admin.";

        $dataPayload = [
            'id_jurnal' => $jurnal->id_kemajuan,
            'tanggal'   => is_string($jurnal->tanggal) ? $jurnal->tanggal : $jurnal->tanggal->format('Y-m-d'),
            'id_kelas'  => $jurnal->id_kelas,
            'kelas'     => $kelasNama,
            'id_mapel'  => $jurnal->id_mapel,
            'mapel'     => $mapelNama,
            'jam_ke'    => $jamKe,
            'materi'    => $jurnal->materi,
            'status'    => 'rejected',
        ];

        return self::sendToGuru(
            idGuru: $jurnal->id_guru,
            judul: $judul,
            pesan: $pesan,
            tipe: 'jurnal_rejected',
            referensiId: $jurnal->id_kemajuan,
            data: $dataPayload
        );
    }
}
