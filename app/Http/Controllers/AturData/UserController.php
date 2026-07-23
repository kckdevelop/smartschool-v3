<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Models\UserSmartschool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = UserSmartschool::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%');
            });
        }

        // Role Filter
        if ($request->filled('role')) {
            $roleFilter = $request->role;
            if ($roleFilter === 'admin_kurikulum') {
                $query->whereIn('level', ['admin_kurikulum', 'admin']);
            } elseif ($roleFilter === 'guru_bk') {
                $query->whereIn('level', ['guru_bk', 'bk']);
            } elseif ($roleFilter === 'petugas_uks') {
                $query->whereIn('level', ['petugas_uks', 'uks']);
            } elseif ($roleFilter === 'admin_ismuba') {
                $query->whereIn('level', ['admin_ismuba', 'ismuba']);
            } elseif ($roleFilter === 'admin_pkl') {
                $query->whereIn('level', ['admin_pkl', 'pkl']);
            } else {
                $query->where('level', $roleFilter);
            }
        }

        $perPage  = (int) $request->input('per_page', 20);
        $perPage  = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;
        $userList = $query->orderBy('id_user', 'desc')->paginate($perPage)->withQueryString();

        // Stats calculation per role
        $allUsers = UserSmartschool::all();
        $stats = [
            'total'           => $allUsers->count(),
            'super_admin'     => $allUsers->where('level', 'super_admin')->count(),
            'admin_kurikulum' => $allUsers->filter(fn($u) => in_array($u->level, ['admin_kurikulum', 'admin']))->count(),
            'guru_bk'         => $allUsers->filter(fn($u) => in_array($u->level, ['guru_bk', 'bk']))->count(),
            'petugas_uks'     => $allUsers->filter(fn($u) => in_array($u->level, ['petugas_uks', 'uks']))->count(),
            'admin_ismuba'    => $allUsers->filter(fn($u) => in_array($u->level, ['admin_ismuba', 'ismuba']))->count(),
            'admin_pkl'       => $allUsers->filter(fn($u) => in_array($u->level, ['admin_pkl', 'pkl']))->count(),
        ];

        $rolesList = UserSmartschool::ROLES;

        return view('atur-data.user.index', compact('userList', 'stats', 'rolesList'));
    }

    public function store(Request $request)
    {
        $validRoles = implode(',', array_keys(UserSmartschool::getRoleLabels()));

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:100|unique:user_smartschool,username',
            'password'     => 'required|string|min:6',
            'level'        => 'required|in:' . $validRoles,
        ], [
            'username.unique' => 'Username sudah digunakan oleh user lain.',
            'password.min'    => 'Password minimal 6 karakter.',
        ]);

        UserSmartschool::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => trim($request->username),
            'password'     => Hash::make($request->password),
            'level'        => $request->level,
        ]);

        return redirect()->route('atur-data.user')->with('success', 'User baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = UserSmartschool::findOrFail($id);
        $validRoles = implode(',', array_keys(UserSmartschool::getRoleLabels()));

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:100|unique:user_smartschool,username,' . $id . ',id_user',
            'level'        => 'required|in:' . $validRoles,
            'password'     => 'nullable|string|min:6',
        ], [
            'username.unique' => 'Username sudah digunakan oleh user lain.',
            'password.min'    => 'Password minimal 6 karakter.',
        ]);

        $updateData = [
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => trim($request->username),
            'level'        => $request->level,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('atur-data.user')->with('success', 'Data user ' . $user->username . ' berhasil diperbarui.');
    }

    public function resetPassword(Request $request, $id)
    {
        $user = UserSmartschool::findOrFail($id);

        $request->validate([
            'new_password' => 'required|string|min:6',
        ], [
            'new_password.min' => 'Password baru minimal 6 karakter.',
        ]);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('atur-data.user')->with('success', 'Password user ' . $user->username . ' berhasil Direset.');
    }

    public function destroy($id)
    {
        $user = UserSmartschool::findOrFail($id);

        if (Auth::check() && Auth::id() == $user->id_user) {
            return redirect()->route('atur-data.user')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan.');
        }

        $user->delete();

        return redirect()->route('atur-data.user')->with('success', 'User berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('atur-data.user')->with('error', 'Tidak ada data user yang terpilih.');
        }

        $currentUserId = Auth::id();
        $deletedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                if ($id == $currentUserId) {
                    continue; // skip current logged in user
                }

                $user = UserSmartschool::find($id);
                if ($user) {
                    $user->delete();
                    $deletedCount++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('atur-data.user')->with('error', 'Terjadi kesalahan saat menghapus data user: ' . $e->getMessage());
        }

        if ($deletedCount === 0) {
            return redirect()->route('atur-data.user')->with('error', 'Tidak ada user yang terhapus (akun Anda sendiri dilewati untuk keamanan).');
        }

        return redirect()->route('atur-data.user')->with('success', 'Berhasil menghapus ' . $deletedCount . ' data user terpilih.');
    }
}
