<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Helper: Resolve id_guru dari user autentikasi.
     */
    private function resolveIdGuru(Request $request): ?int
    {
        $user = $request->user();
        if (!$user) return null;

        if (isset($user->id_guru)) return $user->id_guru;

        if (isset($user->no_id)) {
            return Guru::where('no_id', $user->no_id)->value('id_guru');
        }

        return null;
    }

    /**
     * Ambil daftar notifikasi untuk guru yang sedang login.
     * Endpoint: GET /api/notifikasi
     */
    public function index(Request $request)
    {
        $idGuru = $this->resolveIdGuru($request);

        if (!$idGuru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan.',
                'data'    => [],
                'unread_count' => 0,
            ], 403);
        }

        $limit = $request->input('limit', 30);
        $filter = $request->input('filter'); // 'unread', 'all'

        $query = Notifikasi::where('id_guru', $idGuru)
            ->orderBy('created_at', 'desc');

        if ($filter === 'unread') {
            $query->unread();
        }

        $notifikasi = $query->limit($limit)->get();
        $unreadCount = Notifikasi::where('id_guru', $idGuru)->unread()->count();

        return response()->json([
            'success'      => true,
            'data'         => $notifikasi,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Ambil jumlah notifikasi yang belum dibaca saja.
     * Endpoint: GET /api/notifikasi/unread-count
     */
    public function unreadCount(Request $request)
    {
        $idGuru = $this->resolveIdGuru($request);

        if (!$idGuru) {
            return response()->json([
                'success' => false,
                'unread_count' => 0,
            ], 403);
        }

        $unreadCount = Notifikasi::where('id_guru', $idGuru)->unread()->count();

        return response()->json([
            'success'      => true,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Tandai notifikasi spesifik sebagai telah dibaca.
     * Endpoint: POST /api/notifikasi/{id}/read
     */
    public function markAsRead(Request $request, $id)
    {
        $idGuru = $this->resolveIdGuru($request);

        $notifikasi = Notifikasi::where('id_notifikasi', $id);
        if ($idGuru) {
            $notifikasi->where('id_guru', $idGuru);
        }
        $item = $notifikasi->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan.',
            ], 404);
        }

        $item->markAsRead();

        $unreadCount = $idGuru ? Notifikasi::where('id_guru', $idGuru)->unread()->count() : 0;

        return response()->json([
            'success'      => true,
            'message'      => 'Notifikasi ditandai telah dibaca.',
            'data'         => $item,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Tandai semua notifikasi guru sebagai telah dibaca.
     * Endpoint: POST /api/notifikasi/read-all
     */
    public function markAllAsRead(Request $request)
    {
        $idGuru = $this->resolveIdGuru($request);

        if (!$idGuru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan.',
            ], 403);
        }

        Notifikasi::where('id_guru', $idGuru)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => Carbon::now(),
            ]);

        return response()->json([
            'success'      => true,
            'message'      => 'Semua notifikasi telah ditandai dibaca.',
            'unread_count' => 0,
        ]);
    }

    /**
     * Hapus notifikasi.
     * Endpoint: DELETE /api/notifikasi/{id}
     */
    public function destroy(Request $request, $id)
    {
        $idGuru = $this->resolveIdGuru($request);

        $notifikasi = Notifikasi::where('id_notifikasi', $id);
        if ($idGuru) {
            $notifikasi->where('id_guru', $idGuru);
        }
        $item = $notifikasi->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan.',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus.',
        ]);
    }
}
