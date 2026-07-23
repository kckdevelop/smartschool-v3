<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;

class KategoriRewardController extends Controller
{
    public function index(Request $request)
    {
        $query = Reward::query();

        if ($request->filled('search')) {
            $query->where('detail_reward', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('detail_reward')->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_reward' => 'required|string|max:150|unique:reward,detail_reward',
            'poin' => 'required|integer|min:1',
        ]);

        $reward = Reward::create([
            'detail_reward' => $request->nama_reward,
            'skor' => $request->poin,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori reward berhasil ditambahkan.',
            'data' => $reward,
        ], 201);
    }

    public function show($id)
    {
        $reward = Reward::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $reward,
        ]);
    }

    public function update(Request $request, $id)
    {
        $reward = Reward::findOrFail($id);

        $request->validate([
            'nama_reward' => 'sometimes|required|string|max:150|unique:reward,detail_reward,' . $id . ',id_reward',
            'poin' => 'sometimes|required|integer|min:1',
        ]);

        $reward->update([
            'detail_reward' => $request->nama_reward ?? $reward->detail_reward,
            'skor' => $request->poin ?? $reward->skor,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori reward berhasil diperbarui.',
            'data' => $reward,
        ]);
    }

    public function destroy($id)
    {
        $reward = Reward::findOrFail($id);
        $reward->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori reward berhasil dihapus.',
        ]);
    }

    /**
     * AJAX / Mobile: search kategori reward by keyword
     */
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $results = Reward::when($q, function ($query) use ($q) {
                $query->where('detail_reward', 'like', "%{$q}%");
            })
            ->orderBy('detail_reward')
            ->limit(20)
            ->get(['id_reward', 'detail_reward', 'skor']);

        return response()->json([
            'success' => true,
            'data' => $results->map(function($r) {
                return [
                    'id_reward' => $r->id_reward,
                    'nama_reward' => $r->detail_reward, // map ke nama_reward agar kompatibel
                    'poin_reward' => $r->skor,           // map ke poin_reward agar kompatibel
                ];
            })
        ]);
    }
}
