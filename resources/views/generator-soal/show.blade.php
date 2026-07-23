@extends('layouts.app')

@section('title', 'Detail Bank Soal AI — SmartSchool')
@section('header_title', 'Detail Bank Soal AI')
@section('header_subtitle', 'Tinjau kembali soal yang sudah digenerate')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div style="margin-bottom: 20px;">
        <a href="{{ route('generator-soal.index') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr; gap: 24px;">
        
        <!-- DETAIL CARD -->
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <h2 class="card-title" style="margin:0;"><i class="fa-solid fa-file-invoice"></i> Soal: {{ $riwayat->topik }}</h2>
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn btn-secondary btn-sm" id="btn-copy-show">
                        <i class="fa-solid fa-copy"></i> Salin Soal
                    </button>
                    <a href="{{ route('generator-soal.show', $riwayat->id_riwayat) }}?print=1" target="_blank" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-print"></i> Cetak Soal
                    </a>
                    <a href="{{ route('generator-soal.show', $riwayat->id_riwayat) }}?print=1&kunci=1" target="_blank" class="btn btn-secondary btn-sm" style="color: #0f766e; border-color: rgba(15,118,110,0.3);">
                        <i class="fa-solid fa-print"></i> Cetak Kunci & Pembahasan
                    </a>
                    <button type="button" class="btn btn-primary btn-sm" id="btn-tugas-show" style="background: #0d9488;">
                        <i class="fa-solid fa-file-signature"></i> Jadikan Tugas
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- META SOAL -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; padding: 16px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-muted, #888); text-transform: uppercase; font-weight: 700;">Mata Pelajaran</span>
                        <div style="font-weight: 700; font-size: 0.95rem; margin-top: 2px;">{{ $riwayat->mapel->nama_mapel ?? 'Semua Mapel' }}</div>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-muted, #888); text-transform: uppercase; font-weight: 700;">Kelas / Tingkat</span>
                        <div style="font-weight: 700; font-size: 0.95rem; margin-top: 2px; color: #3b82f6;">Kelas {{ $riwayat->kelas->tingkat ?? '—' }}</div>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-muted, #888); text-transform: uppercase; font-weight: 700;">Detail Soal</span>
                        <div style="font-weight: 700; font-size: 0.95rem; margin-top: 2px;">
                            {{ $riwayat->jumlah_soal }} Soal ({{ $riwayat->tipe_soal === 'pilihan_ganda' ? 'PilGan' : ($riwayat->tipe_soal === 'benar_salah' ? 'Benar-Salah' : 'Essay') }})
                        </div>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-muted, #888); text-transform: uppercase; font-weight: 700;">Tingkat Kesulitan / Kognitif</span>
                        <div style="font-weight: 700; font-size: 0.95rem; margin-top: 2px; color: #ea580c;">{{ in_array(strtolower($riwayat->kesulitan), ['lots', 'mots', 'hots']) ? strtoupper($riwayat->kesulitan) : ucfirst($riwayat->kesulitan) }}</div>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-muted, #888); text-transform: uppercase; font-weight: 700;">Guru Pembuat</span>
                        <div style="font-weight: 700; font-size: 0.95rem; margin-top: 2px;">{{ $riwayat->guru->nama_guru ?? 'Guru SmartSchool' }}</div>
                    </div>
                </div>

                <!-- RENDERING SOAL -->
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($riwayat->hasil_json as $idx => $q)
                        <div class="q-card">
                            <div class="q-card-header" onclick="toggleCard(this)">
                                <span>Soal #{{ $q['no'] ?? ($idx + 1) }}</span> <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="q-card-body">
                                <p style="font-weight:600; font-size:0.95rem; margin:0 0 10px 0;">{{ $q['pertanyaan'] }}</p>


                                
                                @php
                                    $qType = $q['tipe'] ?? '';
                                    if (empty($qType)) {
                                        if (isset($q['pilihan']) && is_array($q['pilihan'])) {
                                            $qType = 'pilihan_ganda';
                                        } elseif (isset($q['kunci_jawaban']) && in_array($q['kunci_jawaban'], ['Benar', 'Salah'])) {
                                            $qType = 'benar_salah';
                                        } else {
                                            $qType = 'essay';
                                        }
                                    }
                                @endphp

                                @if($qType === 'pilihan_ganda' && isset($q['pilihan']))
                                    <ul class="q-pilihan-list">
                                        @foreach($q['pilihan'] as $key => $pText)
                                            <li class="q-pilihan-item">
                                                <span class="q-pilihan-badge">{{ $key }}</span>
                                                <span>{{ $pText }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @elseif($qType === 'benar_salah')
                                    <div style="display:flex; gap:10px; margin-bottom:8px;">
                                        <span style="background:#dcfce7; color:#15803d; padding:4px 14px; border-radius:20px; font-weight:600; font-size:0.88rem; border:1px solid #86efac;">✓ Benar</span>
                                        <span style="background:#fee2e2; color:#b91c1c; padding:4px 14px; border-radius:20px; font-weight:600; font-size:0.88rem; border:1px solid #fca5a5;">✗ Salah</span>
                                    </div>
                                @endif

                                <button type="button" class="toggle-panel-btn" onclick="toggleAnswerPanel(this)">
                                    <i class="fa-regular fa-lightbulb"></i> Tampilkan Kunci & Pembahasan
                                </button>

                                <div class="answer-panel" style="display: none;">
                                    <div style="font-weight:700; margin-bottom:6px; color:#0f766e;">
                                        Kunci Jawaban: <span style="background:#10b981; color:#fff; padding:2px 8px; border-radius:4px; font-weight:800;">{{ $q['kunci_jawaban'] ?? '-' }}</span>
                                    </div>
                                    <div style="line-height:1.5; color:var(--text-secondary);">
                                        <strong style="color:var(--text-primary);">Pembahasan:</strong> {{ $q['pembahasan'] ?? 'Tidak ada pembahasan.' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- POP-UP MODAL: JADIKAN TUGAS BARU -->
<div class="modal-overlay" id="modal-buat-tugas" style="z-index: 1050;">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3>Jadikan Tugas Baru</h3>
            <button onclick="closeModal('modal-buat-tugas')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="form-buat-tugas">
                @csrf
                <input type="hidden" name="id_riwayat" id="modal_id_riwayat" value="{{ $riwayat->id_riwayat }}">
                
                <div class="form-group mb-4">
                    <label class="form-label">Judul Tugas <span class="required">*</span></label>
                    <input type="text" name="judul_tugas" id="modal_judul_tugas" class="form-control" placeholder="Contoh: Tugas Fotosintesis Bab 2" value="Tugas: {{ $riwayat->topik }}" required max="50">
                    <small class="form-hint" style="color: var(--text-muted, #888); font-size: 0.82em; display: block; margin-top: 4px;">
                        Maksimum 50 karakter. Deskripsi tugas akan diisi dengan soal yang telah digenerate secara otomatis.
                    </small>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Pasangkan ke Kelas <span class="required">*</span></label>
                    <select name="id_kelas" id="modal_id_kelas" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-actions" style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modal-buat-tugas')">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-save-tugas" style="background: #0d9488; border-color: #0d9488;">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Sebagai Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.q-card {
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    background: var(--bg-primary, #fff);
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.q-card-header {
    background: var(--bg-secondary, #f8fafc);
    padding: 12px 18px;
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--text-primary);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border-color);
}
.q-card-body {
    padding: 16px 18px;
}
.q-pilihan-list {
    list-style: none;
    padding: 0;
    margin: 0 0 16px 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.q-pilihan-item {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.9rem;
}
.q-pilihan-badge {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--bg-secondary, #e2e8f0);
    color: var(--text-primary);
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    flex-shrink: 0;
}
.toggle-panel-btn {
    background: none;
    border: 1px solid #cbd5e1;
    color: #475569;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 10px;
}
.toggle-panel-btn:hover {
    background: var(--bg-secondary);
}
.answer-panel {
    background: #f0fdf4;
    border-left: 4px solid #10b981;
    border-radius: 0 8px 8px 0;
    padding: 12px 16px;
    font-size: 0.88rem;
}
</style>

<script>
function toggleCard(header) {
    const cardBody = header.nextElementSibling;
    const icon = header.querySelector('i');
    if (cardBody.style.display === 'none') {
        cardBody.style.display = 'block';
        icon.className = 'fa-solid fa-chevron-down';
    } else {
        cardBody.style.display = 'none';
        icon.className = 'fa-solid fa-chevron-right';
    }
}

function toggleAnswerPanel(btn) {
    const panel = btn.nextElementSibling;
    const icon = btn.querySelector('i');
    if (panel.style.display === 'none') {
        panel.style.display = 'block';
        btn.innerHTML = '<i class="fa-solid fa-lightbulb"></i> Sembunyikan Kunci & Pembahasan';
    } else {
        panel.style.display = 'none';
        btn.innerHTML = '<i class="fa-regular fa-lightbulb"></i> Tampilkan Kunci & Pembahasan';
    }
}

// Copy to clipboard
document.getElementById('btn-copy-show').addEventListener('click', function() {
    let copyText = "";
    @foreach($riwayat->hasil_json as $idx => $q)
        copyText += "Soal #{{ $idx + 1 }}\n";
        copyText += "{{ $q['pertanyaan'] }}\n";
        @if($riwayat->tipe_soal === 'pilihan_ganda' && isset($q['pilihan']))
            @foreach($q['pilihan'] as $k => $p)
                copyText += "{{ $k }}. {{ $p }}\n";
            @endforeach
        @endif
        copyText += "Kunci Jawaban: {{ $q['kunci_jawaban'] ?? '-' }}\n";
        copyText += "Pembahasan: {{ $q['pembahasan'] ?? '-' }}\n\n";
    @endforeach

    navigator.clipboard.writeText(copyText).then(function() {
        alert('Seluruh soal berhasil disalin ke clipboard!');
    }, function() {
        alert('Gagal menyalin soal.');
    });
});

// Jadikan Tugas
document.getElementById('btn-tugas-show').addEventListener('click', function() {
    openModal('modal-buat-tugas');
});

// Submit form-buat-tugas
document.getElementById('form-buat-tugas').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = document.getElementById('btn-save-tugas');
    submitBtn.disabled = true;

    fetch('{{ route("generator-soal.buat-tugas") }}', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json().then(data => ({ status: r.status, body: data })))
    .then(res => {
        submitBtn.disabled = false;
        closeModal('modal-buat-tugas');
        if (res.status === 200 && res.body.success) {
            alert(res.body.message);
        } else {
            alert(res.body.message || 'Gagal menyimpan tugas.');
        }
    })
    .catch(err => {
        submitBtn.disabled = false;
        closeModal('modal-buat-tugas');
        console.error('Error saving task:', err);
        alert('Terjadi kesalahan koneksi.');
    });
});

// Modal helper functions
function openModal(id) {
    document.getElementById(id).classList.add('open');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
</script>
@endsection
