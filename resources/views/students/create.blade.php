@extends('layouts.app')
@section('title', 'Tambah Santri')
@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  @push('styles')
    <style>
      /* Progress Steps Style */
      .steps-container {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        z-index: 1;
      }

      .step-item {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        z-index: 2;
        /* Agar circle di atas garis */
      }

      .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #fff;
        border: 2px solid #e5e7eb;
        color: #9ca3af;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 8px;
        transition: all 0.3s ease;
      }

      .step-text {
        font-size: 0.85rem;
        color: #9ca3af;
        font-weight: 500;
      }

      /* Garis Penghubung */
      .progress-line-bg {
        position: absolute;
        top: 20px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: #e5e7eb;
        z-index: 0;
      }

      .progress-line-fill {
        position: absolute;
        top: 20px;
        left: 0;
        width: 0%;
        height: 3px;
        background-color: #4f46e5;
        /* Primary Color */
        transition: width 0.3s ease;
        z-index: 0;
      }

      /* Active State */
      .step-item.active .step-circle {
        border-color: #4f46e5;
        background-color: #4f46e5;
        color: #fff;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
      }

      .step-item.active .step-text {
        color: #4f46e5;
        font-weight: 700;
      }

      /* Completed State */
      .step-item.completed .step-circle {
        border-color: #4f46e5;
        background-color: #4f46e5;
        color: #fff;
      }

      /* Floating Label Modern Fix */
      .form-floating>.form-control:focus~label {
        color: #4f46e5;
      }

      .form-floating>.form-control {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
      }

      .form-floating>.form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
      }
    </style>
  @endpush

  @section('content')
    {{-- <div class="container-fluid my-5">

  </div> --}}
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-10">

          <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark">Registrasi Santri Baru</h4>
            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
              <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
          </div>

          <div class="steps-container">
            <div class="progress-line-bg"></div>
            <div class="progress-line-fill" id="progressLine"></div>

            <div class="step-item active" data-step="1">
              <div class="step-circle"><i class="bi bi-person"></i></div>
              <div class="step-text">Biodata</div>
            </div>
            <div class="step-item" data-step="2">
              <div class="step-circle"><i class="bi bi-geo-alt"></i></div>
              <div class="step-text">Alamat</div>
            </div>
            <div class="step-item" data-step="3">
              <div class="step-circle"><i class="bi bi-people"></i></div>
              <div class="step-text">Orang Tua</div>
            </div>
            <div class="step-item" data-step="4">
              <div class="step-circle"><i class="bi bi-book"></i></div>
              <div class="step-text">Akademik</div>
            </div>
          </div>

          <div class="card card-modern shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-body p-4 p-md-5">

              <form id="studentForm" action="{{ route('students.store') }}" method="POST">
                @csrf

                <div class="form-step" id="step1">
                  <h5 class="mb-4 text-primary fw-bold">Biodata Utama</h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" name="nis" class="form-control" id="nis" placeholder="NIS"
                          value="{{ old('nis') }}" required>
                        <label for="nis">NIS (Nomor Induk Santri) *</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" name="nisn" class="form-control" id="nisn" placeholder="NISN"
                          value="{{ old('nisn', request('nisn')) }}">
                        <label for="nisn">NISN</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating">
                        <input type="text" name="name" class="form-control" id="name" placeholder="Nama Lengkap"
                          value="{{ old('name') }}" required>
                        <label for="name">Nama Lengkap Santri *</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <select class="form-select" name="gender" id="gender" required>
                          <option value="" disabled selected>Pilih Jenis Kelamin</option>
                          <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                          <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <label for="gender">Jenis Kelamin *</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="number" name="child_order" class="form-control" id="child_order" placeholder="Anak Ke"
                          value="{{ old('child_order') }}">
                        <label for="child_order">Anak Ke- (Opsional)</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" name="birth_place" class="form-control" id="birth_place"
                          placeholder="Tempat Lahir" value="{{ old('birth_place') }}" required>
                        <label for="birth_place">Tempat Lahir *</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="date" name="birth_date" class="form-control" id="birth_date"
                          value="{{ old('birth_date') }}" required>
                        <label for="birth_date">Tanggal Lahir *</label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-step d-none" id="step2">
                  <h5 class="mb-4 text-primary fw-bold">Alamat & Identitas</h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" name="nik" class="form-control" id="nik" placeholder="NIK"
                          value="{{ old('nik') }}">
                        <label for="nik">NIK (16 Digit)</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" name="family_card_number" class="form-control" id="kk"
                          placeholder="No KK" value="{{ old('family_card_number') }}">
                        <label for="kk">Nomor Kartu Keluarga</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <hr class="my-2 text-muted">
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" name="province" class="form-control" id="province"
                          placeholder="Provinsi" value="{{ old('province') }}">
                        <label for="province">Provinsi</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" name="regency" class="form-control" id="regency"
                          placeholder="Kabupaten" value="{{ old('regency') }}">
                        <label for="regency">Kabupaten/Kota</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" name="district" class="form-control" id="district"
                          placeholder="Kecamatan" value="{{ old('district') }}">
                        <label for="district">Kecamatan</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" name="village" class="form-control" id="village" placeholder="Desa"
                          value="{{ old('village') }}">
                        <label for="village">Desa/Kelurahan</label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-step d-none" id="step3">
                  <h5 class="mb-4 text-primary fw-bold">Data Orang Tua</h5>

                  <div class="accordion" id="parentsAccordion">
                    <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                      <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold bg-light" type="button" data-bs-toggle="collapse"
                          data-bs-target="#collapseFather">
                          <i class="bi bi-gender-male me-2"></i> Data Ayah
                        </button>
                      </h2>
                      <div id="collapseFather" class="accordion-collapse collapse show"
                        data-bs-parent="#parentsAccordion">
                        <div class="accordion-body">
                          <div class="row g-2">
                            <div class="col-md-3">
                              <div class="form-floating">
                                <select name="father_status" class="form-select" id="father_status">
                                  <option value="alive">Hidup</option>
                                  <option value="deceased">Meninggal</option>
                                </select>
                                <label for="father_status">Status Ayah</label>
                              </div>
                            </div>
                            <div class="col-md-5">
                              <input type="text" name="father_name" class="form-control mb-2" placeholder="Nama Ayah"
                                value="{{ old('father_name') }}">
                            </div>
                            <div class="col-md-4">
                              <input type="text" name="father_phone" class="form-control mb-2"
                                placeholder="No. HP/WA Ayah" value="{{ old('father_phone') }}">
                            </div>
                            <div class="col-md-4">
                              <input type="text" name="father_education" class="form-control"
                                placeholder="Pendidikan Terakhir" value="{{ old('father_education') }}">
                            </div>
                            <div class="col-md-8">
                              <input type="text" name="father_occupation" class="form-control"
                                placeholder="Pekerjaan" value="{{ old('father_occupation') }}">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-3 overflow-hidden">
                      <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold bg-light" type="button"
                          data-bs-toggle="collapse" data-bs-target="#collapseMother">
                          <i class="bi bi-gender-female me-2"></i> Data Ibu
                        </button>
                      </h2>
                      <div id="collapseMother" class="accordion-collapse collapse" data-bs-parent="#parentsAccordion">
                        <div class="accordion-body">
                          <div class="row g-2">
                            <div class="col-md-3">
                              <div class="form-floating">
                                <select name="mother_status" class="form-select" id="mother_status">
                                  <option value="alive">Hidup</option>
                                  <option value="deceased">Meninggal</option>
                                </select>
                                <label for="mother_status">Status Ibu</label>
                              </div>
                            </div>
                            <div class="col-md-5">
                              <input type="text" name="mother_name" class="form-control mb-2" placeholder="Nama Ibu"
                                value="{{ old('mother_name') }}">
                            </div>
                            <div class="col-md-4">
                              <input type="text" name="mother_phone" class="form-control mb-2"
                                placeholder="No. HP/WA Ibu" value="{{ old('mother_phone') }}">
                            </div>
                            <div class="col-md-4">
                              <input type="text" name="mother_education" class="form-control"
                                placeholder="Pendidikan Terakhir" value="{{ old('mother_education') }}">
                            </div>
                            <div class="col-md-8">
                              <input type="text" name="mother_occupation" class="form-control"
                                placeholder="Pekerjaan" value="{{ old('mother_occupation') }}">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-step d-none" id="step4">
                  <h5 class="mb-4 text-primary fw-bold">Data Akademik & Asrama</h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <select name="education_level" class="form-select" id="level" disabled>
                          <option value="MTS">MTS / SMP</option>
                          <option value="MA">MA / SMA</option>
                          <option value="Madin">Madin Saja</option>
                        </select>
                        <label for="level">Jenjang Pendidikan</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" name="class_group" class="form-control" placeholder="Misal: 1A"
                          value="{{ old('class_group') }}" disabled>
                        <label>Rombel / Kelas</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" name="dormitory" class="form-control" placeholder="Nama Asrama"
                          value="{{ old('dormitory') }}" disabled>
                        <label>Gedung Asrama</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" name="room" class="form-control" placeholder="Nomor Kamar"
                          value="{{ old('room') }}" disabled>
                        <label>Nama Kamar</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="date" name="acceptance_date" class="form-control" value="{{ date('Y-m-d') }}">
                        <label>Tanggal Masuk</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <select name="status" class="form-select" required>
                          <option value="active" selected>Aktif</option>
                          <option value="moved">Pindahan</option>
                        </select>
                        <label>Status Awal</label>
                      </div>
                    </div>
                  </div>

                  <div class="alert alert-light border-primary border-start border-4 mt-4" role="alert">
                    <i class="bi bi-info-circle me-2 text-primary"></i>
                    Pastikan semua data yang bertanda (*) sudah terisi dengan benar sebelum menyimpan.
                  </div>
                </div>

                <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                  <button type="button" class="btn btn-outline-secondary px-4 rounded-3 d-none" id="prevBtn"
                    onclick="changeStep(-1)">
                    <i class="bi bi-arrow-left me-2"></i> Sebelumnya
                  </button>
                  <button type="button" class="btn btn-primary px-4 rounded-3 ms-auto" id="nextBtn"
                    onclick="changeStep(1)">
                    Selanjutnya <i class="bi bi-arrow-right ms-2"></i>
                  </button>
                  <button type="submit" class="btn btn-success px-5 rounded-3 ms-auto d-none" id="submitBtn">
                    <i class="bi bi-save me-2"></i> Simpan Data
                  </button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>

  @endsection
  @push('scripts')
    <script>
      let currentStep = 1;
      const totalSteps = 4;

      function changeStep(n) {
        // Validasi Sederhana sebelum lanjut (Opsional, di sini kita skip validasi JS ketat agar fleksibel)
        // Jika ingin validasi HTML5 bekerja, kita harus mentrigger checkValidity()
        if (n === 1) {
          const currentInputs = document.getElementById('step' + currentStep).querySelectorAll(
            'input[required], select[required]');
          let valid = true;
          currentInputs.forEach(input => {
            if (!input.checkValidity()) {
              input.reportValidity();
              valid = false;
            }
          });
          if (!valid) return;
        }

        // Hide Current Step
        document.getElementById('step' + currentStep).classList.add('d-none');

        // Update Step Counter
        currentStep += n;

        // Show New Step
        document.getElementById('step' + currentStep).classList.remove('d-none');

        updateProgressBar();
        updateButtons();
      }

      function updateProgressBar() {
        // Update Active Class pada bulatan
        document.querySelectorAll('.step-item').forEach(item => {
          const stepNum = parseInt(item.getAttribute('data-step'));
          item.classList.remove('active', 'completed');
          if (stepNum < currentStep) {
            item.classList.add('completed');
          } else if (stepNum === currentStep) {
            item.classList.add('active');
          }
        });

        // Update Garis Progress
        const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
        document.getElementById('progressLine').style.width = progressPercentage + '%';
      }

      function updateButtons() {
        // Atur Tombol Prev
        const prevBtn = document.getElementById('prevBtn');
        if (currentStep === 1) {
          prevBtn.classList.add('d-none');
        } else {
          prevBtn.classList.remove('d-none');
        }

        // Atur Tombol Next vs Submit
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        if (currentStep === totalSteps) {
          nextBtn.classList.add('d-none');
          submitBtn.classList.remove('d-none');
        } else {
          nextBtn.classList.remove('d-none');
          submitBtn.classList.add('d-none');
        }
      }
    </script>

  @endpush
