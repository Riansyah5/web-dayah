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
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\RoomAssignmentController;
use App\Http\Controllers\Academic\SyllabusController;
use App\Http\Controllers\Academic\Grading\CourseController;
use App\Http\Controllers\Academic\Grading\TeacherGradingController;
use App\Http\Controllers\Academic\Grading\HomeroomGradingController;
use App\Http\Controllers\Academic\Grading\GradingDashboardController;
use App\Http\Controllers\Academic\Report\StudentHistoryController;
use App\Http\Controllers\Academic\Report\ReportSettingController;
use App\Http\Controllers\Academic\AcademicCalendarController;
use App\Http\Controllers\StudentExitController;
use App\Http\Controllers\Academic\Student\GraduationController;
use App\Http\Controllers\Academic\Student\AlumniController;


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


		Route::prefix('academic/promotion')->name('promotion.')->group(function () {
			Route::get('/', [PromotionController::class, 'index'])->name('index');
			Route::post('/process', [PromotionController::class, 'process'])->name('process');
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