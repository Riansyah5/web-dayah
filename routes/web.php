<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DormController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ViolationController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\StudentExitController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\RoomAssignmentController;
use App\Http\Controllers\Academic\SyllabusController;
use App\Http\Controllers\Tahfizh\TahfizhExportController;
use App\Http\Controllers\Tahfizh\TahfizhReportController;
use App\Http\Controllers\Tahfizh\TahfizhHalaqahController;
use App\Http\Controllers\Tahfizh\TahfizhSetoranController;
use App\Http\Controllers\Tahfizh\TahfizhSettingController;
use App\Http\Controllers\Academic\Grading\CourseController;
use App\Http\Controllers\Academic\Student\AlumniController;
use App\Http\Controllers\Academic\Schedule\PicketController;
use App\Http\Controllers\System\SystemMaintenanceController;
use App\Http\Controllers\Academic\AcademicCalendarController;
use App\Http\Controllers\Tahfizh\TahfizhAssessmentController;
use App\Http\Controllers\Academic\Student\GraduationController;
use App\Http\Controllers\Tahfizh\Admin\MasterScheduleController;
use App\Http\Controllers\Academic\Report\ReportSettingController;
use App\Http\Controllers\Tahfizh\Admin\TahfizhScheduleController;
use App\Http\Controllers\Academic\Report\AcademicReportController;
use App\Http\Controllers\Academic\Report\StudentHistoryController;
use App\Http\Controllers\Tahfizh\Journal\TahfizhJournalController;
use App\Http\Controllers\Academic\Grading\TeacherGradingController;
use App\Http\Controllers\Academic\Journal\TeacherJournalController;
use App\Http\Controllers\Academic\Schedule\SchedulePrintController;
use App\Http\Controllers\Tahfizh\Admin\TahfizhMonitoringController;
use App\Http\Controllers\Academic\Grading\HomeroomGradingController;
use App\Http\Controllers\Academic\Schedule\LessonScheduleController;
use App\Http\Controllers\Academic\Student\PromoteToSeniorController;
use App\Http\Controllers\Academic\Grading\GradingDashboardController;
use App\Http\Controllers\Tahfizh\Teacher\TahfizhPermissionController;
use App\Http\Controllers\Academic\Permission\TeacherPermissionController;
use App\Http\Controllers\Tahfizh\Admin\TahfizhAttendanceReportController;

// Guest (Belum Login)
Route::middleware('guest')->group(function () {
	Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
	Route::post('/login', [AuthController::class, 'login'])->name('login.process');
	// Route::get('/login2', fn() => view('auth.login2'))->name('login2');
});

// Authenticated
Route::middleware('auth')->group(function () {
	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

	// KHUSUS ADMIN
	Route::middleware('role:Admin')->group(function () {
		// student routes
		Route::put('students/{student}/move-room', [StudentController::class, 'moveRoom'])->name('students.moveRoom');
		Route::get('/students/rooms', [StudentController::class, 'rooms'])->name('students.rooms');
		Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
		Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
		Route::get('/students/template', [StudentController::class, 'downloadTemplate'])->name('students.template');
		Route::get('/student/{student}/history', [StudentHistoryController::class, 'show'])->name('student.history');
		Route::get('/student/{student}/biodata', [StudentHistoryController::class, 'printBiodata'])->name('student.biodata.print');
		Route::get('/student/{student}/biodatashow', [StudentHistoryController::class, 'showBiodata'])->name('student.biodata.show');
		// Route untuk proses mutasi (POST)
		Route::post('/students/{student}/exit', [StudentExitController::class, 'store'])->name('students.exit.store');
		Route::get('/students/{student}/print-mutation', [StudentExitController::class, 'printLetter'])->name('students.exit.print');
		// Route Cetak SKL
		Route::get('/students/{student}/print-skl', [StudentExitController::class, 'printSkl'])->name('students.exit.print-skl');
		Route::resource('/students', StudentController::class);


		// pegawai routes
		Route::resource('/kategori', KategoriController::class);
		Route::resource('/jabatan', JabatanController::class);
		Route::resource('/pegawai', PegawaiController::class);
		// User Routes
		Route::resource('/user', UserController::class);
		Route::get('/user/create/{pegawai}', [UserController::class, 'create'])->name('tambah-akun');
		Route::post('/user/create/{pegawai}', [UserController::class, 'store'])->name('simpan-akun');
		// Route untuk update status user
		Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');

		// dorm controller routes
		Route::resource('/dorms', DormController::class);
		// room controller routes
		Route::resource('/rooms', RoomController::class);

		// academic year controller routes
		Route::resource('/academic-years', AcademicYearController::class);

		// Route untuk assign kamar
		Route::get('assignments/create', [RoomAssignmentController::class, 'create'])->name('assignments.create');
		Route::post('assignments', [RoomAssignmentController::class, 'store'])->name('assignments.store');

		// Route untuk melihat daftar perizinan santri
		Route::resource('/permissions', PermissionController::class);
		Route::put('/permissions/{id}/return', [PermissionController::class, 'markAsReturned'])->name('permissions.return');
		Route::get('/permissions/{id}/print', [PermissionController::class, 'print'])->name('permissions.print');
		Route::get('/permissions/{id}/downloadpdf', [PermissionController::class, 'downloadPdf'])->name('permissions.downloadpdf');
		Route::get('/students/{student}/permissions', [PermissionController::class, 'history'])->name('students.permissions');
		Route::get('/students/{student}/permissions/pdf', [PermissionController::class, 'pdf'])->name('permissions.pdf');

		// Route untuk melihat riwayat dan download PDF (gabung di index)
		Route::get('violations/dashboard', [ViolationController::class, 'indexAll'])->name('violations.dashboard');
		Route::get('students/{student}/violations', [ViolationController::class, 'index'])->name('violations.index');
		Route::post('violations', [ViolationController::class, 'store'])->name('violations.store');


		// Group Admin Master Data
		Route::prefix('admin/master-data')->name('master.')->group(function () {
			Route::get('/', [DataMasterController::class, 'index'])->name('index');
			Route::post('/stages', [DataMasterController::class, 'storeStage'])->name('stages.store');
			Route::post('/levels', [DataMasterController::class, 'storeLevel'])->name('levels.store');
			Route::post('/majors', [DataMasterController::class, 'storeMajor'])->name('majors.store');
			Route::post('/academic-years', [DataMasterController::class, 'storeAcademicYear'])->name('academic-years.store');
			Route::put('/academic-years/{id}/activate', [DataMasterController::class, 'activateYear'])->name('academic-years.activate');
			// Route Delete Baru
			Route::delete('/stages/{stage}', [DataMasterController::class, 'destroyStage'])->name('stages.destroy');
			Route::delete('/levels/{level}', [DataMasterController::class, 'destroyLevel'])->name('levels.destroy');
			Route::delete('/majors/{major}', [DataMasterController::class, 'destroyMajor'])->name('majors.destroy');
			Route::delete('/academic-years/{academicYear}', [DataMasterController::class, 'destroyAcademicYear'])->name('academic-years.destroy');
			// Subject Routes (mata pelajaran)
			Route::post('/subjects', [DataMasterController::class, 'storeSubject'])->name('subjects.store');
			Route::delete('/subjects/{subject}', [DataMasterController::class, 'destroySubject'])->name('subjects.destroy');
			// Teacher Routes (input nilai)
			Route::post('/teachers', [DataMasterController::class, 'storeTeacher'])->name('teachers.store');
			Route::delete('/teachers/{teacher}', [DataMasterController::class, 'destroyTeacher'])->name('teachers.destroy');
			// Syllabus Routes (batasan materi)
			Route::prefix('academic/syllabus')->name('syllabus.')->group(function () {
				// URL: academic/syllabus/1 (dimana 1 adalah ID Mapel)
				Route::get('/{subject}', [SyllabusController::class, 'index'])->name('index');
				Route::post('/{subject}', [SyllabusController::class, 'store'])->name('store');
			});
		});

		// Group Akademik Kelas
		Route::resource('academic/classrooms', ClassroomController::class);
		Route::post('academic/classrooms/{classroom}/add', [ClassroomController::class, 'addStudent'])->name('classrooms.addStudent');
		Route::delete('academic/classrooms/{classroom}/remove/{studentId}', [ClassroomController::class, 'removeStudent'])->name('classrooms.removeStudent');
		Route::put('academic/classrooms/{classroom}/move/{studentId}', [ClassroomController::class, 'moveStudent'])->name('classrooms.moveStudent');
		// Route Cetak Absen Kelas (PDF)
		Route::get('academic/classrooms/{classroom}/print-attendance', [ClassroomController::class, 'printAttendance'])->name('classrooms.print-attendance');

		// Group Modul Promosi Siswa
		Route::prefix('academic/promotion')->name('promotion.')->group(function () {
			Route::get('/', [PromotionController::class, 'index'])->name('index');
			Route::post('/process', [PromotionController::class, 'process'])->name('process');
			// Route Promosi Jenjang SMA
			Route::get('/promote-to-senior', [PromoteToSeniorController::class, 'index'])->name('promote_to_senior');
			Route::post('/promotion', [PromoteToSeniorController::class, 'store'])->name('promote_to_senior.store');
			Route::get('/api/search-alumni', [PromoteToSeniorController::class, 'searchAlumni'])->name('promote_to_senior.search');
		});

		// Group Modul Rapor / Grading
		Route::prefix('academic/grading')->name('grading.')->group(function () {

			// DASHBOARD UTAMA (INDEX)
			// Route::get('/', [GradingDashboardController::class, 'index'])->name('dashboard');
			// 1. Plotting Mapel & Guru (KBM)
			Route::get('/plotting', [CourseController::class, 'index'])->name('plotting.index');
			Route::post('/plotting/update', [CourseController::class, 'update'])->name('plotting.update');

			// Nanti disini kita tambah route Import Excel, Input Nilai, dll.
			// 2. Input Nilai (Guru)
			Route::get('/teacher', [TeacherGradingController::class, 'index'])->name('teacher.index');
			Route::get('/teacher/{course}', [TeacherGradingController::class, 'show'])->name('teacher.show');
			Route::post('/teacher/{course}', [TeacherGradingController::class, 'update'])->name('teacher.update');

			// Excel Features
			Route::get('/teacher/{course}/export', [TeacherGradingController::class, 'exportExcel'])->name('teacher.export');
			Route::post('/teacher/{course}/import', [TeacherGradingController::class, 'importExcel'])->name('teacher.import');

			// 3. Wali Kelas (Leger & Cetak)
			Route::get('/homeroom', [HomeroomGradingController::class, 'index'])->name('homeroom.index');
			Route::get('/homeroom/{classroom}', [HomeroomGradingController::class, 'show'])->name('homeroom.show');
			Route::post('/homeroom/update', [HomeroomGradingController::class, 'update'])->name('homeroom.update');
			Route::get('/homeroom/print/{studentId}/{classroomId}', [HomeroomGradingController::class, 'print'])->name('homeroom.print');
			Route::get('/homeroom/preview/{studentId}/{classroomId}', [HomeroomGradingController::class, 'preview'])->name('homeroom.preview');
		});

		// Group Modul Pengaturan Rapor
		Route::prefix('academic/report')->name('report.settings.')->group(function () {
			Route::get('/settings', [ReportSettingController::class, 'index'])->name('index');
			Route::post('/settings', [ReportSettingController::class, 'store'])->name('store');
		});

		// Group Modul Kalender Akademik
		Route::get('/academic/calendar/agenda', [AcademicCalendarController::class, 'agenda'])->name('calendar.agenda');
		Route::get('/academic/calendar/feed', [AcademicCalendarController::class, 'feed'])->name('calendar.feed');
		Route::get('/academic/calendar', [AcademicCalendarController::class, 'index'])->name('calendar.index');
		Route::post('/academic/calendar', [AcademicCalendarController::class, 'store'])->name('calendar.store');
		Route::delete('/academic/calendar/{calendar}', [AcademicCalendarController::class, 'destroy'])->name('calendar.destroy');

		// Group Modul Kelulusan Santri
		Route::prefix('academic/graduation')->name('graduation.')->group(function () {
			Route::get('/', [GraduationController::class, 'index'])->name('index'); // Pilih Kelas
			Route::get('/{classroom}', [GraduationController::class, 'create'])->name('create'); // Form Checklist
			Route::post('/{classroom}', [GraduationController::class, 'store'])->name('store'); // Proses
		});
		// Group Modul Alumni
		Route::get('/academic/alumni', [AlumniController::class, 'index'])->name('alumni.index');

		// Group Modul Jadwal & Jurnal Guru
		Route::prefix('academic')->name('academic.')->group(function () {
			// Route Jadwal Pelajaran
			Route::get('/schedule/print-master', [SchedulePrintController::class, 'printAll'])->name('schedule.print_master');
			Route::get('/schedule', [LessonScheduleController::class, 'index'])->name('schedule.index');
			Route::get('/schedule/{classroom}', [LessonScheduleController::class, 'show'])->name('schedule.show'); // Manage
			Route::post('/schedule/{classroom}', [LessonScheduleController::class, 'store'])->name('schedule.store');
			Route::delete('/schedule/{schedule}', [LessonScheduleController::class, 'destroy'])->name('schedule.destroy');
			// Route Piket (Monitoring & Badal)
			Route::get('/picket', [PicketController::class, 'index'])->name('picket.index');
			// 2. Akses Admin (Approval)
			Route::patch('/picket/permission/{id}', [PicketController::class, 'updatePermissionStatus'])->name('picket.permission.update');
			Route::post('/picket/assign', [PicketController::class, 'assignSubstitute'])->name('picket.assign');
			Route::delete('/picket/remove/{id}', [PicketController::class, 'removeSubstitute'])->name('picket.remove');
			// Dashboard Guru & Jurnal
			Route::get('/my-schedule', [TeacherJournalController::class, 'index'])->name('journal.dashboard');
			Route::get('/journal/create/{schedule}', [TeacherJournalController::class, 'create'])->name('journal.create');
			Route::post('/journal/store/{schedule}', [TeacherJournalController::class, 'store'])->name('journal.store');
			// Route Absensi Siswa
			Route::get('/journal/{journal}/attendance', [TeacherJournalController::class, 'attendance'])->name('journal.attendance');
			Route::post('/journal/{journal}/attendance', [TeacherJournalController::class, 'storeAttendance'])->name('journal.store_attendance');
			// Group Modul Perizinan Guru // 1. Akses Guru (Pengajuan)
			Route::get('/my-permissions', [TeacherPermissionController::class, 'index'])->name('permission.index');
			Route::get('/my-permissions/create', [TeacherPermissionController::class, 'create'])->name('permission.create');
			Route::post('/my-permissions', [TeacherPermissionController::class, 'store'])->name('permission.store');
			Route::get('/my-permissions/get-schedules', [TeacherPermissionController::class, 'getSchedulesByDate'])->name('permission.get_schedules');
		});

		// Group Modul Laporan Akademik
		Route::prefix('academic/report')->name('academic.report.')->group(function () {
			Route::get('/teacher-performance', [AcademicReportController::class, 'teacherRecap'])->name('teacher');
			Route::get('/student-subject', [AcademicReportController::class, 'studentSubjectRecap'])->name('student_subject');
			Route::get('/teacher-performance/{teacher}', [AcademicReportController::class, 'teacherDetail'])->name('teacher.detail');
			Route::post('/teacher-performance/{teacher}/evaluate', [AcademicReportController::class, 'storeEvaluation'])->name('teacher.evaluate');
		});

		// Group Modul Tahfizh
		Route::prefix('tahfizh')->name('tahfizh.')->group(function () {
			// Route untuk membuka form input setoran per siswa
			Route::get('/setoran/create/{student}', [TahfizhSetoranController::class, 'create'])->name('setoran.create');
			Route::post('/setoran/store/{student}', [TahfizhSetoranController::class, 'store'])->name('setoran.store');

			// Resource Route CRUD
			Route::resource('halaqah', TahfizhHalaqahController::class);

			// Custom Route untuk Plotting Anggota
			Route::post('/halaqah/{halaqah}/add-member', [TahfizhHalaqahController::class, 'addMember'])->name('halaqah.add-member');
			Route::delete('/halaqah/{halaqah}/remove-member/{student}', [TahfizhHalaqahController::class, 'removeMember'])->name('halaqah.remove-member');
			// Route untuk melihat Rapor Tahfizh per Santri
			Route::get('/report/{student}', [TahfizhReportController::class, 'show'])->name('report.show');
			// Route Export Surat
			Route::post('/export/hafalan/{student}/preview', [TahfizhExportController::class, 'preview'])->name('export.preview');
			Route::get('/export/hafalan/{student}', [TahfizhExportController::class, 'form'])->name('export.form');
			Route::post('/export/hafalan/{student}', [TahfizhExportController::class, 'print'])->name('export.print');
			// Route Input Rapor
			Route::get('/assessment/{student}', [TahfizhAssessmentController::class, 'edit'])->name('assessment.edit');
			Route::post('/assessment/{student}', [TahfizhAssessmentController::class, 'update'])->name('assessment.update');
			Route::get('/assessment/{student}/print', [TahfizhAssessmentController::class, 'print'])->name('assessment.print');
			Route::get('/assessment/{student}/history', [TahfizhAssessmentController::class, 'history'])->name('assessment.history');
			Route::get('/assessment/{student}/preview', [TahfizhAssessmentController::class, 'preview'])->name('assessment.preview');
			// Route setting Rapor Tahfizh
			Route::get('/setting', [TahfizhSettingController::class, 'index'])->name('setting.index');
			Route::post('/setting', [TahfizhSettingController::class, 'update'])->name('setting.update');
		});

		// routes maintenance system
		Route::prefix('system')->name('system.')->group(function () {
			Route::get('/maintenance', [SystemMaintenanceController::class, 'index'])->name('maintenance.index');
			Route::post('/maintenance/cleanup', [SystemMaintenanceController::class, 'cleanup'])->name('maintenance.cleanup');
		});

		// Group Modul Admin Tahfizh (Jadwal)
		Route::prefix('tahfizh/admin')->name('tahfizh.admin.')->group(function () {
			// routes untuk mengelola jadwal tahfizh
			// Route::get('/schedules', [TahfizhScheduleController::class, 'index'])->name('schedule.index');
			// Route::post('/schedules/update', [TahfizhScheduleController::class, 'updateGlobal'])->name('schedule.update');
			// Monitoring
			Route::get('/monitoring', [TahfizhMonitoringController::class, 'index'])->name('monitoring.index');
			Route::get('/monitoring/data', [TahfizhMonitoringController::class, 'getRealtimeData'])->name('monitoring.data');
			Route::post('/monitoring/assign-badal', [TahfizhMonitoringController::class, 'assignBadal'])->name('monitoring.assign_badal');
			Route::post('/monitoring/approve-permission', [TahfizhMonitoringController::class, 'approvePermission'])->name('monitoring.approve_permission');
			Route::post('/monitoring/assign-badal', [TahfizhMonitoringController::class, 'assignBadal'])->name('monitoring.assign_badal');
			Route::delete('/monitoring/remove-badal', [TahfizhMonitoringController::class, 'removeBadal'])->name('monitoring.remove_badal');
		});

		Route::prefix('tahfizh/admin/schedules')->name('tahfizh.admin.schedules.')->group(function () {
			Route::get('/', [MasterScheduleController::class, 'index'])->name('index');
			Route::post('/', [MasterScheduleController::class, 'store'])->name('store');
			Route::put('/{id}', [MasterScheduleController::class, 'update'])->name('update');
			Route::delete('/{id}', [MasterScheduleController::class, 'destroy'])->name('destroy');
			Route::post('/{id}/toggle', [MasterScheduleController::class, 'toggleStatus'])->name('toggle');
		});

		// Group Modul Admin Tahfizh (Laporan)
		Route::prefix('tahfizh/admin/reports')->name('tahfizh.admin.reports.')->group(function () {
			Route::get('/teacher', [TahfizhAttendanceReportController::class, 'teacherRecap'])->name('teacher');
			Route::get('/student', [TahfizhAttendanceReportController::class, 'studentRecap'])->name('student');
		});

		// Dashboard & Absensi
		Route::prefix('tahfizh')->name('tahfizh.')->group(function () {
			// Dashboard
			Route::get('/journal', [TahfizhJournalController::class, 'index'])->name('journal.dashboard');
			// LANGKAH 1: Buka Halaqah (Absen Guru)
			Route::get('/journal/open/{schedule}', [TahfizhJournalController::class, 'createJournal'])->name('journal.open');
			Route::post('/journal/store-header/{schedule}', [TahfizhJournalController::class, 'storeJournalHeader'])->name('journal.store_header');
			// LANGKAH 2: Absen Santri (Bisa diakses berulang kali untuk update)
			// Parameter diganti jadi {journal} karena jurnalnya sudah tercipta
			Route::get('/journal/attendance/{journal}', [TahfizhJournalController::class, 'editStudentAttendance'])->name('journal.attendance');
			Route::post('/journal/update-attendance/{journal}', [TahfizhJournalController::class, 'updateStudentAttendance'])->name('journal.update_attendance');
		});

		// Group Modul Perizinan Guru Tahfizh
		Route::prefix('tahfizh/permission')->name('tahfizh.permission.')->middleware(['auth'])->group(function () {
			Route::get('/permissions', [TahfizhPermissionController::class, 'index'])->name('index');
			Route::get('/create', [TahfizhPermissionController::class, 'create'])->name('create');
			Route::post('/store', [TahfizhPermissionController::class, 'store'])->name('store');
			Route::get('/get-schedules', [TahfizhPermissionController::class, 'getSchedules'])->name('get_schedules');
		});
	});




	// KHUSUS GURU
	Route::middleware('role:Guru')->group(function () {
		// // Route untuk assign kamar
		// Route::get('assignments/create', [RoomAssignmentController::class, 'create'])->name('assignments.create');
		// Route::post('assignments', [RoomAssignmentController::class, 'store'])->name('assignments.store');

		// // student routes
		// Route::put('students/{student}/move-room', [StudentController::class, 'moveRoom'])->name('students.moveRoom');
		// Route::get('/students/rooms', [StudentController::class, 'rooms'])->name('students.rooms');
		// Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
		// Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
		// Route::get('/students/template', [StudentController::class, 'downloadTemplate'])->name('students.template');
		// Route::get('/student/{student}/history', [StudentHistoryController::class, 'show'])->name('student.history');
		// Route::get('/student/{student}/biodata', [StudentHistoryController::class, 'printBiodata'])->name('student.biodata.print');
		// Route::get('/student/{student}/biodatashow', [StudentHistoryController::class, 'showBiodata'])->name('student.biodata.show');
		// Route::resource('/students', StudentController::class);

		// // Group Akademik Kelas
		// Route::resource('academic/classrooms', ClassroomController::class);
		// Route::post('academic/classrooms/{classroom}/add', [ClassroomController::class, 'addStudent'])->name('classrooms.addStudent');
		// Route::delete('academic/classrooms/{classroom}/remove/{studentId}', [ClassroomController::class, 'removeStudent'])->name('classrooms.removeStudent');
		// Route::put('academic/classrooms/{classroom}/move/{studentId}', [ClassroomController::class, 'moveStudent'])->name('classrooms.moveStudent');

		// // Group Modul Rapor / Grading
		// Route::prefix('academic/grading')->name('grading.')->group(function () {
		// 	// DASHBOARD UTAMA (INDEX)
		// 	// Route::get('/', [GradingDashboardController::class, 'index'])->name('dashboard');
		// 	// 1. Plotting Mapel & Guru (KBM)
		// 	Route::get('/plotting', [CourseController::class, 'index'])->name('plotting.index');
		// 	Route::post('/plotting/update', [CourseController::class, 'update'])->name('plotting.update');

		// 	// Nanti disini kita tambah route Import Excel, Input Nilai, dll.
		// 	// 2. Input Nilai (Guru)
		// 	Route::get('/teacher', [TeacherGradingController::class, 'index'])->name('teacher.index');
		// 	Route::get('/teacher/{course}', [TeacherGradingController::class, 'show'])->name('teacher.show');
		// 	Route::post('/teacher/{course}', [TeacherGradingController::class, 'update'])->name('teacher.update');

		// 	// Excel Features
		// 	Route::get('/teacher/{course}/export', [TeacherGradingController::class, 'exportExcel'])->name('teacher.export');
		// 	Route::post('/teacher/{course}/import', [TeacherGradingController::class, 'importExcel'])->name('teacher.import');

		// 	// 3. Wali Kelas (Leger & Cetak)
		// 	Route::get('/homeroom', [HomeroomGradingController::class, 'index'])->name('homeroom.index');
		// 	Route::get('/homeroom/{classroom}', [HomeroomGradingController::class, 'show'])->name('homeroom.show');
		// 	Route::post('/homeroom/update', [HomeroomGradingController::class, 'update'])->name('homeroom.update');
		// 	Route::get('/homeroom/print/{studentId}/{classroomId}', [HomeroomGradingController::class, 'print'])->name('homeroom.print');
		// 	Route::get('/homeroom/preview/{studentId}/{classroomId}', [HomeroomGradingController::class, 'preview'])->name('homeroom.preview');
		// });

		// // Group Modul Pengaturan Rapor
		// Route::prefix('academic/report/settings')->name('report.settings.')->group(function () {
		// 	Route::get('/report/settings', [ReportSettingController::class, 'index'])->name('index');
		// 	Route::post('/report/settings', [ReportSettingController::class, 'store'])->name('store');
		// });
	});
});


// Route::resource('/kategori', KategoriController::class);
		// Route::resource('/jabatan', JabatanController::class);
		// Route::resource('/pegawai', PegawaiController::class);
		// // User Routes
		// Route::resource('/user', UserController::class);
		// Route::get('/user/create/{pegawai}', [UserController::class, 'create'])->name('tambah-akun');
		// Route::post('/user/create/{pegawai}', [UserController::class, 'store'])->name('simpan-akun');
		// // Route untuk update status user
		// Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');