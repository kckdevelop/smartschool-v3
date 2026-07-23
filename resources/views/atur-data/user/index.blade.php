@extends('layouts.app')

@section('title', 'Atur User & Role — SmartSchool')
@section('header_title', 'Atur User & Hak Akses')
@section('header_subtitle', 'Kelola data pengguna web dashboard dan pembagian role / hak akses')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- ═════ STAT CARDS ═════ --}}
    <div class="user-stats-grid">
        <div class="user-stat-card" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
            <div class="user-stat-icon"><i class="fa-solid fa-users"></i></div>
            <div class="user-stat-info">
                <div class="user-stat-num">{{ $stats['total'] }}</div>
                <div class="user-stat-lbl">Total User</div>
            </div>
        </div>

        <div class="user-stat-card" style="background: linear-gradient(135deg, #7c3aed, #9333ea);">
            <div class="user-stat-icon"><i class="fa-solid fa-user-gear"></i></div>
            <div class="user-stat-info">
                <div class="user-stat-num">{{ $stats['super_admin'] }}</div>
                <div class="user-stat-lbl">Super Admin</div>
            </div>
        </div>

        <div class="user-stat-card" style="background: linear-gradient(135deg, #059669, #10b981);">
            <div class="user-stat-icon"><i class="fa-solid fa-user-graduate"></i></div>
            <div class="user-stat-info">
                <div class="user-stat-num">{{ $stats['admin_kurikulum'] }}</div>
                <div class="user-stat-lbl">Admin Kurikulum</div>
            </div>
        </div>

        <div class="user-stat-card" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
            <div class="user-stat-icon"><i class="fa-solid fa-user-doctor"></i></div>
            <div class="user-stat-info">
                <div class="user-stat-num">{{ $stats['guru_bk'] }}</div>
                <div class="user-stat-lbl">Guru BK</div>
            </div>
        </div>

        <div class="user-stat-card" style="background: linear-gradient(135deg, #e11d48, #f43f5e);">
            <div class="user-stat-icon"><i class="fa-solid fa-notes-medical"></i></div>
            <div class="user-stat-info">
                <div class="user-stat-num">{{ $stats['petugas_uks'] }}</div>
                <div class="user-stat-lbl">Petugas UKS</div>
            </div>
        </div>

        <div class="user-stat-card" style="background: linear-gradient(135deg, #0284c7, #38bdf8);">
            <div class="user-stat-icon"><i class="fa-solid fa-mosque"></i></div>
            <div class="user-stat-info">
                <div class="user-stat-num">{{ $stats['admin_ismuba'] }}</div>
                <div class="user-stat-lbl">Admin ISMUBA</div>
            </div>
        </div>

        <div class="user-stat-card" style="background: linear-gradient(135deg, #2563eb, #3b82f6);">
            <div class="user-stat-icon"><i class="fa-solid fa-briefcase"></i></div>
            <div class="user-stat-info">
                <div class="user-stat-num">{{ $stats['admin_pkl'] }}</div>
                <div class="user-stat-lbl">Admin PKL</div>
            </div>
        </div>
    </div>

    {{-- ═════ MAIN CARD ═════ --}}
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-user-shield"></i> Daftar Data User</h2>
            <div class="card-header-right">
                <form method="GET" action="{{ route('atur-data.user') }}" class="search-form" style="display: flex; gap: 8px;">
                    <select name="role" class="form-control form-control-sm" onchange="this.form.submit()" style="min-width: 170px;">
                        <option value="">Semua Role</option>
                        @foreach($rolesList as $key => $label)
                            <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / username..." class="form-control form-control-sm">
                    <button type="submit" class="btn btn-secondary btn-sm" title="Cari"><i class="fa-solid fa-search"></i></button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('atur-data.user') }}" class="btn btn-light btn-sm" title="Reset Filter"><i class="fa-solid fa-rotate-left"></i></a>
                    @endif
                </form>

                <button class="btn btn-primary btn-sm" onclick="openAddModal()" id="btn-tambah-user">
                    <i class="fa-solid fa-plus"></i> Tambah User
                </button>

                <button type="button" class="btn btn-danger btn-sm" id="btn-bulk-delete" style="display: none; background: #ef4444; border: none; font-weight: 600;" onclick="openBulkDeleteModal()">
                    <i class="fa-solid fa-trash-can"></i> Hapus Terpilih (<span id="bulk-select-count">0</span>)
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <form id="bulk-delete-form" method="POST" action="{{ route('atur-data.user.bulk-destroy') }}">
                @csrf
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 40px; text-align: center;"><input type="checkbox" id="check-all" style="cursor: pointer;"></th>
                                <th style="width: 50px; text-align: center;">#</th>
                                <th>Pengguna</th>
                                <th>Username</th>
                                <th>Role / Hak Akses</th>
                                <th style="text-align: center; width: 160px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($userList as $user)
                                <tr>
                                    <td style="text-align: center;">
                                        @if(Auth::id() != $user->id_user)
                                            <input type="checkbox" name="ids[]" class="row-checkbox" value="{{ $user->id_user }}" style="cursor: pointer;">
                                        @else
                                            <i class="fa-solid fa-lock text-muted" title="Akun Anda (Tidak dapat dihapus masal)"></i>
                                        @endif
                                    </td>
                                    <td style="text-align: center; font-size: 0.82rem; color: var(--text-muted); font-weight: 600;">
                                        {{ $userList->firstItem() + $loop->index }}
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div class="user-avatar-circle {{ $user->role_badge_class }}">
                                                {{ strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 700; font-size: 0.9rem; color: var(--text-primary);">
                                                    {{ $user->nama_lengkap }}
                                                </div>
                                                @if(Auth::id() == $user->id_user)
                                                    <span style="font-size: 0.7rem; font-weight: 700; color: #059669; background: #d1fae5; padding: 1px 6px; border-radius: 4px;">
                                                        <i class="fa-solid fa-circle-check"></i> Akun Anda
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code style="font-size: 0.85rem; font-weight: 700; color: #1e293b; background: #f1f5f9; padding: 3px 8px; border-radius: 6px;">
                                            {{ $user->username }}
                                        </code>
                                    </td>
                                    <td>
                                        <span class="badge-role {{ $user->role_badge_class }}">
                                            <i class="fa-solid fa-shield-halved"></i> {{ $user->role_label }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <div style="display: flex; gap: 4px; justify-content: center;">
                                            <button type="button" class="btn btn-secondary btn-sm btn-icon" title="Edit User"
                                                    onclick="editUser({{ json_encode($user) }})">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button type="button" class="btn btn-secondary btn-sm btn-icon" title="Reset Password"
                                                    style="background: #f59e0b; color: #fff; border: none;"
                                                    onclick="openResetPasswordModal({{ $user->id_user }}, '{{ addslashes($user->nama_lengkap) }}', '{{ $user->username }}')">
                                                <i class="fa-solid fa-key"></i>
                                            </button>
                                            @if(Auth::id() != $user->id_user)
                                                <button type="button" class="btn btn-danger btn-sm btn-icon" title="Hapus User"
                                                        onclick="confirmDelete('{{ route('atur-data.user.destroy', $user->id_user) }}', 'Yakin ingin menghapus user {{ addslashes($user->nama_lengkap) }}?')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-6" style="padding: 50px 20px;">
                                        <i class="fa-solid fa-users-slash" style="font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 10px;"></i>
                                        Belum ada data user yang sesuai dengan pencarian / filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>

        @if($userList->hasPages())
            <div style="padding: 14px 20px; border-top: 1px solid var(--border-color);">
                {{ $userList->links('pagination.presensi') }}
            </div>
        @endif
    </div>
</div>

{{-- ══════════════ MODAL TAMBAH / EDIT USER ══════════════ --}}
<div class="modal-overlay" id="modal-user">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3 id="modal-user-title">
                <i class="fa-solid fa-user-plus" style="color: var(--color-primary);"></i>
                Tambah User Baru
            </h3>
            <button onclick="closeModal('modal-user')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-user" action="{{ route('atur-data.user.store') }}" method="POST">
            @csrf
            <div id="user-method-field"></div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-id-card"></i> Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="nama_lengkap" id="u_nama_lengkap" class="form-control"
                           placeholder="Contoh: Drs. Ahmad Dahlan" required maxlength="100">
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-at"></i> Username <span class="required">*</span></label>
                    <input type="text" name="username" id="u_username" class="form-control"
                           placeholder="Contoh: ahmaddahlan" required maxlength="100">
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-shield-halved"></i> Role / Hak Akses <span class="required">*</span></label>
                    <select name="level" id="u_level" class="form-control" required>
                        <option value="">-- Pilih Role / Hak Akses --</option>
                        <option value="super_admin">⚡ Super Admin (Akses Penuh Seluruh Sistem)</option>
                        <option value="admin_kurikulum">📚 Admin Data & Kurikulum</option>
                        <option value="guru_bk">🌱 Guru BK / Konselor</option>
                        <option value="petugas_uks">🏥 Petugas UKS / Kesehatan</option>
                        <option value="admin_ismuba">🕌 Admin ISMUBA</option>
                        <option value="admin_pkl">🏢 Admin PKL / Prakerin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-solid fa-lock"></i> Password
                        <span id="password-required-star" class="required">*</span>
                    </label>
                    <input type="password" name="password" id="u_password" class="form-control"
                           placeholder="Masukkan password user..." minlength="6">
                    <small id="password-help-text" style="color: var(--text-muted); display: block; margin-top: 4px;">
                        Minimal 6 karakter.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-user')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Data User
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════ MODAL RESET PASSWORD ══════════════ --}}
<div class="modal-overlay" id="modal-reset-password">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3><i class="fa-solid fa-key" style="color: #f59e0b;"></i> Reset Password User</h3>
            <button onclick="closeModal('modal-reset-password')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-reset-password" action="" method="POST">
            @csrf
            <div class="modal-body">
                <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 14px; margin-bottom: 16px; display: flex; gap: 12px; align-items: flex-start;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #d97706; font-size: 1.2rem; margin-top: 2px;"></i>
                    <div style="font-size: 0.85rem; color: #92400e; line-height: 1.5;">
                        Anda akan memperbarui password untuk user <strong id="reset-user-nama">-</strong> (<code id="reset-user-username">-</code>).
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-lock"></i> Password Baru <span class="required">*</span></label>
                    <input type="password" name="new_password" id="reset_new_password" class="form-control"
                           placeholder="Masukkan password baru..." required minlength="6">
                    <small style="color: var(--text-muted); display: block; margin-top: 4px;">Minimal 6 karakter.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-reset-password')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" style="background: #f59e0b; border-color: #d97706;">
                    <i class="fa-solid fa-rotate-left"></i> Reset Password Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════ MODAL BULK DELETE ══════════════ --}}
<div class="modal-overlay" id="modal-bulk-confirm">
    <div class="modal modal-md" style="max-width: 440px;">
        <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 18px 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="background: #fee2e2; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #ef4444; font-size: 1.1rem;"></i>
                </div>
                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">Hapus Masal Terpilih</h3>
            </div>
            <button onclick="closeModal('modal-bulk-confirm')" class="modal-close">&times;</button>
        </div>
        <div class="modal-body" style="padding: 20px 24px; text-align: center;">
            <p style="margin: 0 0 8px; font-size: 0.95rem; color: var(--text-primary);">Apakah Anda yakin ingin menghapus sebanyak</p>
            <p style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #ef4444;"><span id="bulk-confirm-count">0</span> Data User?</p>
            <p style="margin: 8px 0 0; font-size: 0.83rem; color: var(--text-secondary); line-height: 1.4;">Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer" style="padding: 14px 24px; display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" onclick="closeModal('modal-bulk-confirm')" class="btn btn-secondary" style="padding: 8px 18px; font-size: 0.88rem;">Batal</button>
            <button type="button" id="btn-submit-bulk-delete" class="btn" style="background: #ef4444; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-size: 0.88rem; font-weight: 600; display: flex; align-items: center; gap: 7px;">
                <i class="fa-solid fa-trash-can"></i> Ya, Hapus Semua
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Stat grid */
.user-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
}
.user-stat-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border-radius: var(--radius-card, 12px);
    color: #fff;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: transform 0.2s ease;
}
.user-stat-card:hover { transform: translateY(-2px); }
.user-stat-icon {
    width: 40px; height: 40px;
    background: rgba(255,255,255,0.22);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; flex-shrink: 0;
}
.user-stat-num { font-size: 1.5rem; font-weight: 800; line-height: 1; }
.user-stat-lbl { font-size: 0.75rem; opacity: 0.9; margin-top: 3px; font-weight: 600; }

/* User avatar circle */
.user-avatar-circle {
    width: 38px; height: 38px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 0.95rem;
    flex-shrink: 0;
}

/* Badge Role */
.badge-role {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 700;
}

.badge-role-super      { background: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe; }
.badge-role-kurikulum  { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.badge-role-bk         { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
.badge-role-uks        { background: #ffe4e6; color: #9f1239; border: 1px solid #fecdd3; }
.badge-role-ismuba     { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
.badge-role-pkl        { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
.badge-role-default    { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

.btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }
</style>
@endpush

@push('scripts')
<script>
function openAddModal() {
    document.getElementById('form-user').action = '{{ route("atur-data.user.store") }}';
    document.getElementById('user-method-field').innerHTML = '';
    document.getElementById('modal-user-title').innerHTML =
        '<i class="fa-solid fa-user-plus" style="color: var(--color-primary);"></i> Tambah User Baru';
    document.getElementById('u_nama_lengkap').value = '';
    document.getElementById('u_username').value = '';
    document.getElementById('u_level').value = '';
    document.getElementById('u_password').value = '';
    document.getElementById('u_password').required = true;
    document.getElementById('password-required-star').style.display = 'inline';
    document.getElementById('password-help-text').textContent = 'Minimal 6 karakter.';
    openModal('modal-user');
}

function editUser(user) {
    document.getElementById('form-user').action = `/atur-data/user/${user.id_user}`;
    document.getElementById('user-method-field').innerHTML = '<input type="hidden" name="_method" value="POST">';
    document.getElementById('modal-user-title').innerHTML =
        '<i class="fa-solid fa-user-pen" style="color: var(--color-primary);"></i> Edit Data User';

    document.getElementById('u_nama_lengkap').value = user.nama_lengkap || '';
    document.getElementById('u_username').value = user.username || '';

    // Map legacy level values if any
    let levelVal = user.level;
    if (levelVal === 'admin') levelVal = 'admin_kurikulum';
    if (levelVal === 'bk') levelVal = 'guru_bk';
    if (levelVal === 'uks') levelVal = 'petugas_uks';
    if (levelVal === 'ismuba') levelVal = 'admin_ismuba';
    if (levelVal === 'pkl') levelVal = 'admin_pkl';

    document.getElementById('u_level').value = levelVal;
    document.getElementById('u_password').value = '';
    document.getElementById('u_password').required = false;
    document.getElementById('password-required-star').style.display = 'none';
    document.getElementById('password-help-text').textContent = 'Kosongkan jika tidak ingin mengubah password.';

    openModal('modal-user');
}

function openResetPasswordModal(id, nama, username) {
    document.getElementById('form-reset-password').action = `/atur-data/user/${id}/reset-password`;
    document.getElementById('reset-user-nama').textContent = nama;
    document.getElementById('reset-user-username').textContent = username;
    document.getElementById('reset_new_password').value = '';
    openModal('modal-reset-password');
}

// Bulk delete logic
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('check-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const btnBulkDelete = document.getElementById('btn-bulk-delete');
    const bulkSelectCount = document.getElementById('bulk-select-count');

    function updateBulkButton() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        if (checkedCount > 0) {
            bulkSelectCount.textContent = checkedCount;
            btnBulkDelete.style.display = 'inline-flex';
        } else {
            btnBulkDelete.style.display = 'none';
        }
    }

    if (checkAll) {
        checkAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => {
                cb.checked = checkAll.checked;
            });
            updateBulkButton();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (!this.checked) {
                checkAll.checked = false;
            } else {
                const totalChecked = document.querySelectorAll('.row-checkbox:checked').length;
                if (totalChecked === rowCheckboxes.length) {
                    checkAll.checked = true;
                }
            }
            updateBulkButton();
        });
    });

    const submitBulkBtn = document.getElementById('btn-submit-bulk-delete');
    if (submitBulkBtn) {
        submitBulkBtn.addEventListener('click', function() {
            document.getElementById('bulk-delete-form').submit();
        });
    }
});

function openBulkDeleteModal() {
    const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
    document.getElementById('bulk-confirm-count').textContent = checkedCount;
    openModal('modal-bulk-confirm');
}
</script>
@endpush
@endsection
