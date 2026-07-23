<?php

namespace App\Http\Controllers\Bk;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;

class KategoriRewardController extends Controller
{
    public function index()
    {
        $data = Reward::orderBy('detail_reward')->paginate(20);
        return view('bk.kategori-reward.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'detail_reward' => 'required|string|max:100|unique:reward,detail_reward',
            'skor'          => 'required|integer|min:1|max:1000',
        ]);

        Reward::create($request->only(['detail_reward', 'skor']));

        return redirect()->route('bk.kategori-reward.index')
            ->with('success', 'Kategori reward berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'detail_reward' => 'required|string|max:100|unique:reward,detail_reward,'.$id.',id_reward',
            'skor'          => 'required|integer|min:1|max:1000',
        ]);

        Reward::findOrFail($id)->update($request->only(['detail_reward', 'skor']));

        return redirect()->route('bk.kategori-reward.index')
            ->with('success', 'Kategori reward berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Reward::findOrFail($id)->delete();
        return redirect()->route('bk.kategori-reward.index')
            ->with('success', 'Kategori reward berhasil dihapus.');
    }

    /**
     * AJAX: search kategori reward by keyword (for catat-reward form)
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

        return response()->json($results);
    }
}
