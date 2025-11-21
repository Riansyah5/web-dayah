@extends('layouts.app')
@section('title', 'Jabatan')
@section('content')
  <!--begin::Container Konten Utama-->
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">

        <h2 class="text-center fw-bold mb-4">Manajemen Jabatan</h2>
        <!--begin::Card Utama-->
        <div class="card modern-card">
          <div class="card-body p-4 p-md-5">

            <!--begin::Form Tambah Jabatan-->
            <h5 class="fw-bold mb-3">Tambah Jabatan Baru</h5>
            <form action="{{ route('jabatan.store') }}" id="formTambahJabatan" class="row g-3 mb-4 align-items-end"
              method="post">
              @csrf
              <div class="col-md-5">
                <label for="inputNamaJabatan" class="form-label">Nama Jabatan</label>
                <input type="text" class="form-control" id="inputNamaJabatan" placeholder="Contoh: Manajer"
                  name="name" required>
              </div>

              <div class="col-md-5">
                <label for="inputKeterangan" class="form-label">Keterangan</label>
                <input type="text" class="form-control" id="inputKeterangan" placeholder="Contoh: Bertanggung jawab..."
                  name="description" required>
              </div>

              <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                  <i class="bi bi-plus-lg"></i> Tambah
                </button>
              </div>

            </form>
            <!--end::Form Tambah Jabatan-->

            <hr>

            <!--begin::Daftar Jabatan-->
            <h5 class="fw-bold mb-3 mt-4">Daftar Jabatan</h5>
            <div class="table-responsive">
              <!--begin::Tabel Jabatan-->
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th scope="col" style="width: 5%;">No.</th>
                    <th scope="col" style="width: 30%;">Nama Jabatan</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col" class="text-end" style="width: 20%;">Aksi</th>
                  </tr>
                </thead>
                <tbody id="jabatanTableBody">
                  {{-- Perulangan untuk menampilkan data jabatan dari controller --}}
                  @forelse ($jabatans as $jabatan)
                    <tr data-id="{{ $jabatan->id }}">
                      <td class="row-number">{{ $loop->iteration }}</td>
                      <td class="nama-jabatan">{{ $jabatan->name }}</td>
                      <td class="keterangan">{{ $jabatan->description }}</td>
                      <td class="text-end">
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-outline-primary btn-sm btn-edit">
                            <i class="bi bi-pencil-fill"></i> Edit
                          </button>
                          <button type="button" class="btn btn-outline-danger btn-sm btn-hapus">
                            <i class="bi bi-trash-fill"></i> Hapus
                          </button>
                        </div>
                      </td>
                    </tr>
                  @empty
                    {{-- Tampilan jika tidak ada data jabatan --}}
                    <tr>
                      <td colspan="5" class="text-center">Data masih kosong</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
              <!--end::Tabel Jabatan-->
            </div>
            <!--end::Daftar Jabatan-->
          </div>
        </div>
        <!--end::Card Utama-->

      </div>
    </div>
  </div>
  <!--end::Container Konten Utama-->
  {{-- Modals tetap di sini karena mereka terkait langsung dengan konten halaman ini --}}
  <!--begin::Modal Edit-->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Jabatan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formEditJabatan">
            <div class="mb-3">
              <label for="editNamaJabatan" class="form-label">Nama Jabatan</label>
              <input type="text" class="form-control" id="editNamaJabatan" required>
            </div>
            <div class="mb-3">
              <label for="editKeterangan" class="form-label">Keterangan</label>
              <input type="text" class="form-control" id="editKeterangan">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="btnSimpanEdit">Simpan Perubahan</button>
        </div>
      </div>
    </div>
  </div>
  <!--end::Modal Edit-->
  <!--begin::Modal Hapus-->
  <div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="hapusModalLabel">Konfirmasi Hapus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin menghapus jabatan: <br>
            <strong id="jabatanYangDihapus"></strong>?
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger" id="btnKonfirmasiHapus">Ya, Hapus</button>
        </div>
      </div>
    </div>
  </div>
  <!--end::Modal Hapus-->
@endsection
@push('scripts')
  {{-- Menambahkan skrip spesifik halaman ke stack 'scripts' --}}
  <script>
    // Event listener yang dijalankan setelah seluruh halaman HTML dimuat
    document.addEventListener('DOMContentLoaded', function() {

      // --- Inisialisasi Instance Modal Bootstrap ---
      const editModal = new bootstrap.Modal(document.getElementById('editModal'));
      const hapusModal = new bootstrap.Modal(document.getElementById('hapusModal'));

      // Ambil CSRF token dari meta tag untuk keamanan request AJAX ke Laravel
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');


      // --- Seleksi Elemen-elemen DOM Penting ---
      const formTambahJabatan = document.getElementById('formTambahJabatan');
      const jabatanTableBody = document.getElementById('jabatanTableBody');

      // Elemen-elemen di dalam Modal Edit
      const formEditJabatan = document.getElementById('formEditJabatan');
      const editNamaJabatanInput = document.getElementById('editNamaJabatan');
      const editKeteranganInput = document.getElementById('editKeterangan');
      const btnSimpanEdit = document.getElementById('btnSimpanEdit');

      // Elemen-elemen di dalam Modal Hapus
      const jabatanYangDihapus = document.getElementById('jabatanYangDihapus');
      const btnKonfirmasiHapus = document.getElementById('btnKonfirmasiHapus');

      // Variabel untuk menyimpan state (baris mana yang sedang dioperasikan)
      let rowToEdit = null;
      let rowToDelete = null;

      // Langsung fokuskan kursor ke input nama jabatan saat halaman dimuat
      document.getElementById('inputNamaJabatan').focus();

      // --- FUNGSI 1: TAMBAH JABATAN BARU (menggunakan AJAX) ---
      formTambahJabatan.addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah form melakukan submit tradisional (reload halaman)

        // Ambil nilai dari form
        const namaJabatan = document.getElementById('inputNamaJabatan').value.trim();
        const keterangan = document.getElementById('inputKeterangan').value.trim();

        if (namaJabatan === '') {
          alert('Nama jabatan tidak boleh kosong!');
          return;
        }

        // Kirim data ke server menggunakan fetch API
        fetch("{{ route('jabatan.store') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              name: namaJabatan,
              description: keterangan
            })
          })
          .then(response => {
            // Cek jika respons dari server tidak OK (bukan status 2xx)
            if (!response.ok) {
              return response.json().then(err => {
                throw err;
              });
            }
            return response.json();
          })
          .then(result => {
            console.log(result.message); // Tampilkan pesan sukses dari server di console
            const jabatanBaru = result.data; // Ambil data jabatan baru dari respons server

            // Hapus baris "Data masih kosong" jika ada
            const rowKosong = document.getElementById('row-jabatan-kosong');
            if (rowKosong) {
              rowKosong.remove();
            }

            // Buat baris baru untuk tabel menggunakan data dari server
            const newRow = document.createElement('tr');
            newRow.setAttribute('data-id', jabatanBaru.id); // Gunakan ID dari server

            newRow.innerHTML = `
                  <td class="row-number"></td>
                  <td class="nama-jabatan">${jabatanBaru.name}</td>
                  <td class="keterangan">${jabatanBaru.description}</td>
                  <td class="text-end">
                      <div class="btn-group" role="group">
                          <button type="button" class="btn btn-outline-primary btn-sm btn-edit">
                              <i class="bi bi-pencil-fill"></i> Edit
                          </button>
                          <button type="button" class="btn btn-outline-danger btn-sm btn-hapus">
                              <i class="bi bi-trash-fill"></i> Hapus
                          </button>
                      </div>
                  </td>
              `;

            jabatanTableBody.appendChild(newRow); // Tambahkan baris baru ke dalam tabel
            formTambahJabatan.reset(); // Kosongkan input form
            document.getElementById('inputNamaJabatan').focus(); // Kembalikan fokus ke input nama
            updateRowNumbers(); // Panggil fungsi untuk memperbarui nomor urut
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menambah data. Cek console untuk detail.');
          });
      });
      // --- END FUNGSI 1 ---

      // --- FUNGSI 2: EVENT LISTENER UNTUK TOMBOL EDIT DAN HAPUS (menggunakan Event Delegation) ---
      jabatanTableBody.addEventListener('click', function(e) {
        const targetRow = e.target.closest('tr');
        if (!targetRow) return;

        // --- Tombol EDIT diklik ---
        if (e.target.classList.contains('btn-edit') || e.target.closest('.btn-edit')) {
          rowToEdit = targetRow; // Simpan baris yang akan diedit

          // Ambil data dari baris
          const namaJabatan = rowToEdit.querySelector('.nama-jabatan').textContent;
          const keterangan = rowToEdit.querySelector('.keterangan').textContent;

          // Isi form modal
          editNamaJabatanInput.value = namaJabatan;
          editKeteranganInput.value = keterangan;

          editModal.show();
        }

        // --- Tombol HAPUS diklik ---
        if (e.target.classList.contains('btn-hapus') || e.target.closest('.btn-hapus')) {
          rowToDelete = targetRow; // Simpan baris yang akan dihapus

          const namaJabatan = rowToDelete.querySelector('.nama-jabatan').textContent;
          jabatanYangDihapus.textContent = namaJabatan;

          hapusModal.show();
        }
      });
      // --- END FUNGSI 2 ---

      // --- FUNGSI 3: SIMPAN PERUBAHAN DARI MODAL EDIT ---
      btnSimpanEdit.addEventListener('click', function() {
        if (!rowToEdit) return;

        const id = rowToEdit.getAttribute('data-id');
        const newNamaJabatan = editNamaJabatanInput.value.trim();
        const newKeterangan = editKeteranganInput.value.trim();

        if (newNamaJabatan === '') {
          alert('Nama jabatan tidak boleh kosong!');
          return;
        }

        // Kirim data update ke server Laravel menggunakan fetch API
        fetch(`/jabatans/${id}`, {
            method: 'PUT', // atau 'PATCH'
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken, // Kirim CSRF token
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              name: newNamaJabatan, // DIUBAH: sesuaikan dengan validasi controller
              description: newKeterangan
            }) // DIUBAH: sesuaikan dengan validasi controller
          })
          .then(response => {
            if (!response.ok) {
              // Jika server merespon dengan error (misal: validasi gagal)
              throw new Error('Gagal menyimpan perubahan.');
            }
            return response.json();
          })
          .then(data => {
            // Jika server merespons sukses, perbarui tampilan di tabel
            console.log(data.message); // Tampilkan pesan sukses dari server di console
            rowToEdit.querySelector('.nama-jabatan').textContent = newNamaJabatan;
            rowToEdit.querySelector('.keterangan').textContent = newKeterangan;

            editModal.hide(); // Tutup modal
            rowToEdit = null; // Reset variabel state
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data.');
          });
      });
      // --- END FUNGSI 3 ---

      // --- FUNGSI 4: KONFIRMASI HAPUS DARI MODAL HAPUS ---
      btnKonfirmasiHapus.addEventListener('click', function() {
        if (!rowToDelete) return;

        const id = rowToDelete.getAttribute('data-id');

        // Kirim request hapus ke server Laravel
        fetch(`/jabatan/${id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            }
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Gagal menghapus data.');
            }
            return response.json();
          })
          .then(data => {
            // Jika server merespons sukses, hapus baris dari tabel di tampilan
            console.log(data.message); // Tampilkan pesan sukses dari server di console
            rowToDelete.remove(); // Hapus baris dari DOM

            hapusModal.hide(); // Tutup modal
            rowToDelete = null; // Reset variabel state
            updateRowNumbers(); // Panggil fungsi untuk memperbarui nomor urut
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data.');
          });
      });
      // --- END FUNGSI 4 ---

      // --- FUNGSI BANTUAN: Memperbarui nomor urut di tabel ---
      function updateRowNumbers() {
        const rows = jabatanTableBody.querySelectorAll('tr');
        rows.forEach((row, index) => {
          // Cari sel dengan class 'row-number' dan isi dengan nomor urut baru
          const rowNumberCell = row.querySelector('.row-number');
          if (rowNumberCell) { // Hanya update jika sel nomor ditemukan
            rowNumberCell.textContent = index + 1;
          }
        });
      }

      // Panggil fungsi penomoran saat halaman pertama kali dimuat untuk memastikan nomor urut awal sudah benar
      updateRowNumbers();
    });
  </script>
@endpush {{-- Mengakhiri penambahan skrip --}}
