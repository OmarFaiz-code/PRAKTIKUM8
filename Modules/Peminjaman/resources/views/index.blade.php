@extends('layouts.app')
@section('title', 'Peminjaman')

@section('content')
<div id="alert-ajax"></div>

<div class="row g-4">
    {{-- ===== FORM ===== --}}
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header" id="form-title">Tambah Peminjaman</div>
            <div class="card-body">
                <form id="form-peminjaman" enctype="multipart/form-data" novalidate>
                    <input type="hidden" id="peminjaman-id">

                    <div class="mb-3">
                        <label class="form-label">Nama Peminjam</label>
                        <input type="text" name="nama_peminjam" id="nama_peminjam" class="form-control">
                        <div class="invalid-feedback" id="error-nama_peminjam"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" name="judul_buku" id="judul_buku" class="form-control">
                        <div class="invalid-feedback" id="error-judul_buku"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control">
                        <div class="invalid-feedback" id="error-tanggal_pinjam"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control">
                        <div class="invalid-feedback" id="error-tanggal_kembali"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="Dipinjam">Dipinjam</option>
                            <option value="Dikembalikan">Dikembalikan</option>
                        </select>
                        <div class="invalid-feedback" id="error-status"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bukti Peminjaman (jpg/png/pdf, maks 2 MB)</label>
                        <input type="file" name="bukti" id="bukti" class="form-control"
                               accept=".jpg,.jpeg,.png,.pdf">
                        <div class="invalid-feedback" id="error-bukti"></div>
                        <div class="form-text" id="bukti-lama"></div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                    <button type="button" class="btn btn-secondary" id="btn-batal">Batal</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== TABEL ===== --}}
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">Daftar Peminjaman</div>
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th><th>Peminjam</th><th>Buku</th><th>Pinjam</th>
                            <th>Kembali</th><th>Status</th><th>Bukti</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-peminjaman"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrf   = document.querySelector('meta[name="csrf-token"]').content;
const URL_P  = "{{ url('peminjaman') }}";
const form   = document.getElementById('form-peminjaman');
const FIELDS = ['nama_peminjam','judul_buku','tanggal_pinjam','tanggal_kembali','status','bukti'];

// 'Accept: application/json' membuat Laravel membalas error validasi sebagai JSON (HTTP 422)
// Content-Type TIDAK diisi manual: browser mengisinya sendiri untuk FormData.
const HEADERS = { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' };

// Escape teks agar aman dimasukkan ke innerHTML (cegah XSS)
function esc(s) {
    const d = document.createElement('div');
    d.textContent = s ?? '';
    return d.innerHTML;
}

function showAlert(msg, type = 'success') {
    document.getElementById('alert-ajax').innerHTML =
        `<div class="alert alert-${type} alert-dismissible fade show">${esc(msg)}
         <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
}

function clearErrors() {
    FIELDS.forEach(f => {
        document.getElementById(f).classList.remove('is-invalid');
        document.getElementById('error-' + f).textContent = '';
    });
}

// Tampilkan pesan error 422 di bawah tiap input
function showErrors(errors) {
    Object.keys(errors).forEach(f => {
        const el = document.getElementById(f);
        if (el) {
            el.classList.add('is-invalid');
            document.getElementById('error-' + f).textContent = errors[f][0];
        }
    });
}

function resetForm() {
    form.reset();
    document.getElementById('peminjaman-id').value = '';
    document.getElementById('form-title').textContent = 'Tambah Peminjaman';
    document.getElementById('bukti-lama').textContent = '';
    clearErrors();
}

// ===== READ: ambil data lalu gambar ulang isi tabel (tanpa refresh halaman) =====
async function loadData() {
    const res  = await fetch(URL_P + '/data', { headers: HEADERS });
    const json = await res.json();
    const tbody = document.getElementById('tbody-peminjaman');

    if (!json.data.length) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">Belum ada data peminjaman.</td></tr>';
        return;
    }

    tbody.innerHTML = json.data.map((p, i) => `
        <tr>
            <td>${i + 1}</td>
            <td>${esc(p.nama_peminjam)}</td>
            <td>${esc(p.judul_buku)}</td>
            <td>${esc(p.tanggal_pinjam)}</td>
            <td>${esc(p.tanggal_kembali) || '-'}</td>
            <td><span class="badge ${p.status === 'Dipinjam' ? 'bg-warning text-dark' : 'bg-success'}">${esc(p.status)}</span></td>
            <td>${p.bukti_url ? `<a href="${esc(p.bukti_url)}" target="_blank">Lihat</a>` : '-'}</td>
            <td>
                <button class="btn btn-sm btn-warning btn-edit" data-id="${p.id}">Edit</button>
                <button class="btn btn-sm btn-danger btn-hapus" data-id="${p.id}">Hapus</button>
            </td>
        </tr>`).join('');
}

// ===== CREATE & UPDATE =====
form.addEventListener('submit', async (e) => {
    e.preventDefault();               // cegah submit biasa (yang akan me-refresh halaman)
    clearErrors();

    const id   = document.getElementById('peminjaman-id').value;
    const data = new FormData(form);  // otomatis ikut membawa file
    if (id) data.append('_method', 'PUT'); // PHP tidak membaca file di request PUT asli, jadi di-spoof lewat POST

    const res  = await fetch(id ? `${URL_P}/${id}` : URL_P, { method: 'POST', headers: HEADERS, body: data });
    const json = await res.json();

    if (res.status === 422) {         // gagal validasi
        showErrors(json.errors);
        return;
    }
    if (!res.ok) {
        showAlert(json.message ?? 'Terjadi kesalahan.', 'danger');
        return;
    }

    resetForm();
    await loadData();
    showAlert(json.message);
});

// ===== Tombol Edit & Hapus (event delegation karena baris dibuat dinamis) =====
document.getElementById('tbody-peminjaman').addEventListener('click', async (e) => {
    const editBtn  = e.target.closest('.btn-edit');
    const hapusBtn = e.target.closest('.btn-hapus');

    if (editBtn) {
        const res = await fetch(`${URL_P}/${editBtn.dataset.id}`, { headers: HEADERS });
        const { data: p } = await res.json();
        clearErrors();
        document.getElementById('peminjaman-id').value = p.id;
        document.getElementById('nama_peminjam').value = p.nama_peminjam;
        document.getElementById('judul_buku').value = p.judul_buku;
        document.getElementById('tanggal_pinjam').value = p.tanggal_pinjam ?? '';
        document.getElementById('tanggal_kembali').value = p.tanggal_kembali ?? '';
        document.getElementById('status').value = p.status;
        document.getElementById('bukti').value = ''; // input file tidak boleh diisi lewat JS
        document.getElementById('bukti-lama').innerHTML = p.bukti_url
            ? `File saat ini: <a href="${esc(p.bukti_url)}" target="_blank">lihat</a> (kosongkan jika tidak ingin mengganti)` : '';
        document.getElementById('form-title').textContent = 'Edit Peminjaman';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    if (hapusBtn) {
        if (!confirm('Hapus data peminjaman ini?')) return;
        const res  = await fetch(`${URL_P}/${hapusBtn.dataset.id}`, { method: 'DELETE', headers: HEADERS });
        const json = await res.json();
        await loadData();
        showAlert(json.message, res.ok ? 'success' : 'danger');
    }
});

document.getElementById('btn-batal').addEventListener('click', resetForm);

loadData(); // muat data saat halaman pertama kali dibuka
</script>
{{-- Bootstrap JS dipakai hanya untuk tombol close pada alert --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
