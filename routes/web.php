<?php

use App\Http\Controllers\Academic\AcademicCalendarController;
use App\Http\Controllers\Academic\Grading\CourseController;
use App\Http\Controllers\Academic\Grading\GradingDashboardController;
use App\Http\Controllers\Academic\Grading\HomeroomGradingController;
use App\Http\Controllers\Academic\Grading\TeacherGradingController;
use App\Http\Controllers\Academic\Journal\TeacherJournalController;
use App\Http\Controllers\Academic\Permission\TeacherPermissionController;
use App\Http\Controllers\Academic\Report\AcademicReportController;
use App\Http\Controllers\Academic\Report\ReportSettingController;
use App\Http\Controllers\Academic\Report\StudentHistoryController;
use App\Http\Controllers\Academic\Schedule\LessonScheduleController;
use App\Http\Controllers\Academic\Schedule\PicketController;
use App\Http\Controllers\Academic\Schedule\SchedulePrintController;
use App\Http\Controllers\Academic\Student\AlumniController;
use App\Http\Controllers\Academic\Student\GraduationController;
use App\Http\Controllers\Academic\Student\PromoteToSeniorController;
use App\Http\Controllers\Academic\SyllabusController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\DormController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RoomAssignmentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SidebarSettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentExitController;
use App\Http\Controllers\System\SystemMaintenanceController;
use App\Http\Controllers\Tahfizh\Admin\MasterScheduleController;
use App\Http\Controllers\Tahfizh\Admin\TahfizhAttendanceReportController;
use App\Http\Controllers\Tahfizh\Admin\TahfizhCleanupController;
use App\Http\Controllers\Tahfizh\Admin\TahfizhMonitoringController;
use App\Http\Controllers\Tahfizh\Admin\TahfizhScheduleController;
use App\Http\Controllers\Tahfizh\Journal\TahfizhJournalController;
use App\Http\Controllers\Tahfizh\TahfizhAssessmentController;
use App\Http\Controllers\Tahfizh\TahfizhExportController;
use App\Http\Controllers\Tahfizh\TahfizhHalaqahController;
use App\Http\Controllers\Tahfizh\TahfizhReportController;
use App\Http\Controllers\Tahfizh\TahfizhSetoranController;
use App\Http\Controllers\Tahfizh\TahfizhSettingController;
use App\Http\Controllers\Tahfizh\Teacher\TahfizhPermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViolationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cbt\Admin\CbtAccountController;
use App\Http\Controllers\Cbt\Teacher\QuestionBankController;
use App\Http\Controllers\Cbt\Teacher\QuestionController;
use App\Http\Controllers\Cbt\Admin\CbtExamController;
use App\Http\Controllers\Cbt\Teacher\ExamResultController;
use App\Http\Controllers\Cbt\Admin\CbtMonitorController;

// ==========================================
// PORTAL GUEST (BELUM LOGIN)
// ==========================================
Route::middleware('guest')->group(function () {
  Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

// ==========================================
// PORTAL AUTHENTICATED (GLOBAL)
// ==========================================
Route::middleware('auth')->group(function () {
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	Route::resource('/user', UserController::class)->only('show');

  // ==========================================
  // PENGATURAN SISTEM & MAINTENANCE
  // ==========================================
  Route::middleware(['permission:kelola-pengaturan-sistem'])->group(function () {
    Route::get('/settings/sidebar', [SidebarSettingController::class, 'index'])->name('sidebar-settings.index');
    Route::post('/settings/sidebar/update', [SidebarSettingController::class, 'update'])->name('sidebar-settings.update');
    
    Route::prefix('system')->name('system.')->group(function () {
      Route::get('/maintenance', [SystemMaintenanceController::class, 'index'])->name('maintenance.index');
      Route::post('/maintenance/cleanup', [SystemMaintenanceController::class, 'cleanup'])->name('maintenance.cleanup');
    });

    Route::prefix('tahfizh/admin/cleanup')->name('tahfizh.admin.cleanup.')->group(function () {
      Route::get('/', [TahfizhCleanupController::class, 'index'])->name('index');
      Route::post('/run', [TahfizhCleanupController::class, 'runCleanup'])->name('run');
    });
  });

  // ==========================================
  // MASTER DATA 
  // ==========================================
  Route::middleware(['permission:kelola-master-data'])->group(function () {
    Route::resource('/academic-years', AcademicYearController::class);
    
    Route::prefix('admin/master-data')->name('master.')->group(function () {
      Route::get('/', [DataMasterController::class, 'index'])->name('index');
      Route::post('/stages', [DataMasterController::class, 'storeStage'])->name('stages.store');
      Route::put('/stages/{stage}', [DataMasterController::class, 'updateStage'])->name('stages.update');
      Route::delete('/stages/{stage}', [DataMasterController::class, 'destroyStage'])->name('stages.destroy');
      Route::post('/levels', [DataMasterController::class, 'storeLevel'])->name('levels.store');
      Route::delete('/levels/{level}', [DataMasterController::class, 'destroyLevel'])->name('levels.destroy');
      Route::post('/majors', [DataMasterController::class, 'storeMajor'])->name('majors.store');
      Route::delete('/majors/{major}', [DataMasterController::class, 'destroyMajor'])->name('majors.destroy');
      Route::post('/academic-years', [DataMasterController::class, 'storeAcademicYear'])->name('academic-years.store');
      Route::put('/academic-years/{id}/activate', [DataMasterController::class, 'activateYear'])->name('academic-years.activate');
      Route::delete('/academic-years/{academicYear}', [DataMasterController::class, 'destroyAcademicYear'])->name('academic-years.destroy');
      Route::post('/subjects', [DataMasterController::class, 'storeSubject'])->name('subjects.store');
      Route::delete('/subjects/{subject}', [DataMasterController::class, 'destroySubject'])->name('subjects.destroy');
      Route::post('/teachers', [DataMasterController::class, 'storeTeacher'])->name('teachers.store');
      Route::delete('/teachers/{teacher}', [DataMasterController::class, 'destroyTeacher'])->name('teachers.destroy');
      
      Route::prefix('academic/syllabus')->name('syllabus.')->group(function () {
        Route::get('/{subject}', [SyllabusController::class, 'index'])->name('index');
        Route::post('/{subject}', [SyllabusController::class, 'store'])->name('store');
      });
    });
  });

  // ==========================================
  // KELOLA PEGAWAI & AKUN
  // ==========================================
  Route::middleware(['permission:kelola-user-pegawai'])->group(function () {
    Route::resource('/kategori', KategoriController::class);
    Route::resource('/jabatan', JabatanController::class);
    Route::get('/pegawai/template', [PegawaiController::class, 'downloadTemplate'])->name('pegawai.template');
    Route::post('/pegawai/import', [PegawaiController::class, 'import'])->name('pegawai.import');
    Route::resource('/pegawai', PegawaiController::class);
    
    Route::resource('/user', UserController::class)->except(['show']);
    Route::get('/user/create/{pegawai}', [UserController::class, 'create'])->name('tambah-akun');
    Route::post('/user/create/{pegawai}', [UserController::class, 'store'])->name('simpan-akun');
    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');

		// --- MANAJEMEN ROLE AKUN ---
		Route::get('/users/{user}/edit-role', [UserController::class, 'editRole'])->name('users.editRole');
    Route::patch('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::patch('/users/{user}/toggle-permission', [UserController::class, 'togglePermission'])->name('users.togglePermission');
    // ------------------------------
  });

  // ==========================================
  // KESISWAAN (DATA SANTRI & MUTASI)
  // ==========================================
  Route::middleware(['permission:lihat-data-santri|kelola-data-santri'])->group(function () {
    Route::get('/students/rooms', [StudentController::class, 'rooms'])->name('students.rooms');
    Route::get('/students/template', [StudentController::class, 'downloadTemplate'])->name('students.template');
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::put('students/{student}/move-room', [StudentController::class, 'moveRoom'])->name('students.moveRoom');
    Route::resource('/students', StudentController::class);
    
    Route::get('/student/{student}/history', [StudentHistoryController::class, 'show'])->name('student.history');
    Route::get('/student/{student}/biodata', [StudentHistoryController::class, 'printBiodata'])->name('student.biodata.print');
    Route::get('/student/{student}/biodatashow', [StudentHistoryController::class, 'showBiodata'])->name('student.biodata.show');
    
    Route::post('/students/{student}/exit', [StudentExitController::class, 'store'])->name('students.exit.store');
    Route::get('/students/{student}/print-mutation', [StudentExitController::class, 'printLetter'])->name('students.exit.print');
    Route::get('/students/{student}/print-skl', [StudentExitController::class, 'printSkl'])->name('students.exit.print-skl');


    Route::prefix('academic/graduation')->name('graduation.')->group(function () {
      Route::get('/', [GraduationController::class, 'index'])->name('index');
      Route::get('/{classroom}', [GraduationController::class, 'create'])->name('create');
      Route::post('/{classroom}', [GraduationController::class, 'store'])->name('store');
    });
  });


  // ==========================================================
  // AKADEMIK & KBM (JADWAL, JURNAL, NILAI, KELAS)
  // ==========================================================
  Route::middleware(['permission:lihat-kelas|kelola-kelas'])->group(function () {
		Route::get('/academic/alumni', [AlumniController::class, 'index'])->name('alumni.index');
    Route::resource('academic/classrooms', ClassroomController::class);
    Route::post('academic/classrooms/{classroom}/add', [ClassroomController::class, 'addStudent'])->name('classrooms.addStudent');
    Route::delete('academic/classrooms/{classroom}/remove/{studentId}', [ClassroomController::class, 'removeStudent'])->name('classrooms.removeStudent');
    Route::put('academic/classrooms/{classroom}/move/{studentId}', [ClassroomController::class, 'moveStudent'])->name('classrooms.moveStudent');
    Route::get('academic/classrooms/{classroom}/print-attendance', [ClassroomController::class, 'printAttendance'])->name('classrooms.print-attendance');
    Route::get('academic/classrooms/{classroom}/print-attendance2', [ClassroomController::class, 'printAttendance2'])->name('classrooms.print-attendance2');
    Route::get('classrooms/{classroom}/export', [ClassroomController::class, 'export'])->name('classrooms.export');

    Route::prefix('academic/promotion')->name('promotion.')->group(function () {
      Route::get('/', [PromotionController::class, 'index'])->name('index');
      Route::post('/process', [PromotionController::class, 'process'])->name('process');
      Route::get('/promote-to-senior', [PromoteToSeniorController::class, 'index'])->name('promote_to_senior');
      Route::post('/promotion', [PromoteToSeniorController::class, 'store'])->name('promote_to_senior.store');
      Route::get('/api/search-alumni', [PromoteToSeniorController::class, 'searchAlumni'])->name('promote_to_senior.search');
    });
	});

  // Kalender Akademik (Dapat dilihat oleh semua yang terkait KBM)
  Route::middleware(['permission:lihat-jadwal-pelajaran|kelola-jadwal-pelajaran'])->group(function () {
    Route::get('/academic/calendar/agenda', [AcademicCalendarController::class, 'agenda'])->name('calendar.agenda');
    Route::get('/academic/calendar/feed', [AcademicCalendarController::class, 'feed'])->name('calendar.feed');
    Route::get('/academic/calendar', [AcademicCalendarController::class, 'index'])->name('calendar.index');
  });

  Route::middleware(['permission:kelola-jadwal-pelajaran'])->group(function () {
    Route::post('/academic/calendar', [AcademicCalendarController::class, 'store'])->name('calendar.store');
    Route::delete('/academic/calendar/{calendar}', [AcademicCalendarController::class, 'destroy'])->name('calendar.destroy');
    
    Route::get('/academic/schedule/print-master', [SchedulePrintController::class, 'printAll'])->name('academic.schedule.print_master');
    Route::get('/academic/schedule', [LessonScheduleController::class, 'index'])->name('academic.schedule.index');
    Route::get('/academic/schedule/{classroom}', [LessonScheduleController::class, 'show'])->name('academic.schedule.show');
    Route::post('/academic/schedule/{classroom}', [LessonScheduleController::class, 'store'])->name('academic.schedule.store');
    Route::delete('/academic/schedule/{schedule}', [LessonScheduleController::class, 'destroy'])->name('academic.schedule.destroy');
    
    Route::get('academic/grading/plotting', [CourseController::class, 'index'])->name('grading.plotting.index');
    Route::post('academic/grading/plotting/update', [CourseController::class, 'update'])->name('grading.plotting.update');
  });

  Route::middleware(['permission:kelola-piket-badal'])->group(function () {
    Route::get('academic/picket', [PicketController::class, 'index'])->name('academic.picket.index');
    Route::patch('academic/picket/permission/{id}', [PicketController::class, 'updatePermissionStatus'])->name('academic.picket.permission.update');
    Route::post('academic/picket/assign', [PicketController::class, 'assignSubstitute'])->name('academic.picket.assign');
    Route::delete('academic/picket/remove/{id}', [PicketController::class, 'removeSubstitute'])->name('academic.picket.remove');
    
    Route::get('academic/report/teacher-performance', [AcademicReportController::class, 'teacherRecap'])->name('academic.report.teacher');
    Route::get('academic/report/student-subject', [AcademicReportController::class, 'studentSubjectRecap'])->name('academic.report.student_subject');
    Route::get('academic/report/teacher-performance/{teacher}', [AcademicReportController::class, 'teacherDetail'])->name('academic.report.teacher.detail');
    Route::post('academic/report/teacher-performance/{teacher}/evaluate', [AcademicReportController::class, 'storeEvaluation'])->name('academic.report.teacher.evaluate');
  });

  Route::middleware(['permission:isi-jurnal-guru'])->group(function () {
    Route::get('academic/my-schedule', [TeacherJournalController::class, 'index'])->name('academic.journal.dashboard');
    Route::get('academic/journal/create/{schedule}', [TeacherJournalController::class, 'create'])->name('academic.journal.create');
    Route::post('academic/journal/store/{schedule}', [TeacherJournalController::class, 'store'])->name('academic.journal.store');
    Route::get('academic/journal/{journal}/attendance', [TeacherJournalController::class, 'attendance'])->name('academic.journal.attendance');
    Route::post('academic/journal/{journal}/attendance', [TeacherJournalController::class, 'storeAttendance'])->name('academic.journal.store_attendance');
  });

  Route::middleware(['permission:ajukan-izin-guru'])->group(function () {
    Route::get('academic/my-permissions', [TeacherPermissionController::class, 'index'])->name('academic.permission.index');
    Route::get('academic/my-permissions/create', [TeacherPermissionController::class, 'create'])->name('academic.permission.create');
    Route::post('academic/my-permissions', [TeacherPermissionController::class, 'store'])->name('academic.permission.store');
    Route::get('academic/my-permissions/get-schedules', [TeacherPermissionController::class, 'getSchedulesByDate'])->name('academic.permission.get_schedules');
  });

  Route::middleware(['permission:isi-nilai-mapel'])->group(function () {
    Route::get('academic/grading/teacher', [TeacherGradingController::class, 'index'])->name('grading.teacher.index');
    Route::get('academic/grading/teacher/{course}', [TeacherGradingController::class, 'show'])->name('grading.teacher.show');
    Route::post('academic/grading/teacher/{course}', [TeacherGradingController::class, 'update'])->name('grading.teacher.update');
    Route::get('academic/grading/teacher/{course}/export', [TeacherGradingController::class, 'exportExcel'])->name('grading.teacher.export');
    Route::post('academic/grading/teacher/{course}/import', [TeacherGradingController::class, 'importExcel'])->name('grading.teacher.import');
  });

  Route::middleware(['permission:kelola-leger-rapor'])->group(function () {
    Route::get('academic/grading/homeroom', [HomeroomGradingController::class, 'index'])->name('grading.homeroom.index');
    Route::get('academic/grading/homeroom/{classroom}', [HomeroomGradingController::class, 'show'])->name('grading.homeroom.show');
    Route::post('academic/grading/homeroom/update', [HomeroomGradingController::class, 'update'])->name('grading.homeroom.update');
    Route::get('academic/grading/homeroom/print/{studentId}/{classroomId}', [HomeroomGradingController::class, 'print'])->name('grading.homeroom.print');
    Route::get('academic/grading/homeroom/preview/{studentId}/{classroomId}', [HomeroomGradingController::class, 'preview'])->name('grading.homeroom.preview');
  });

  Route::middleware(['permission:kelola-setting-rapor'])->group(function () {
    Route::get('academic/report/settings', [ReportSettingController::class, 'index'])->name('report.settings.index');
    Route::post('academic/report/settings', [ReportSettingController::class, 'store'])->name('report.settings.store');
  });

	
  // ==========================================
  // PENGASUHAN (ASRAMA, PERIZINAN, PELANGGARAN)
  // ==========================================
  Route::middleware(['permission:lihat-asrama|kelola-asrama-kamar'])->group(function () {
    Route::resource('/dorms', DormController::class);
    Route::resource('/rooms', RoomController::class);
    Route::get('assignments/create', [RoomAssignmentController::class, 'create'])->name('assignments.create');
    Route::post('assignments', [RoomAssignmentController::class, 'store'])->name('assignments.store');
  });

  Route::middleware(['permission:kelola-perizinan-santri'])->group(function () {
    Route::resource('/permissions', PermissionController::class);
    Route::put('/permissions/{id}/return', [PermissionController::class, 'markAsReturned'])->name('permissions.return');
    Route::get('/permissions/{id}/print', [PermissionController::class, 'print'])->name('permissions.print');
    Route::get('/permissions/{id}/downloadpdf', [PermissionController::class, 'downloadPdf'])->name('permissions.downloadpdf');
    Route::get('/students/{student}/permissions', [PermissionController::class, 'history'])->name('students.permissions');
    Route::get('/students/{student}/permissions/pdf', [PermissionController::class, 'pdf'])->name('permissions.pdf');
  });

  Route::middleware(['permission:kelola-pelanggaran'])->group(function () {
    Route::get('violations/dashboard', [ViolationController::class, 'indexAll'])->name('violations.dashboard');
    Route::get('students/{student}/violations', [ViolationController::class, 'index'])->name('violations.index');
    Route::post('violations', [ViolationController::class, 'store'])->name('violations.store');
  });

  // ==========================================
  // TAHFIZH
  // ==========================================
  Route::prefix('tahfizh')->name('tahfizh.')->group(function () {
    
    Route::middleware(['permission:lihat-halaqah|kelola-halaqah'])->group(function () {
      Route::resource('halaqah', TahfizhHalaqahController::class);
      Route::post('/halaqah/{halaqah}/add-member', [TahfizhHalaqahController::class, 'addMember'])->name('halaqah.add-member');
      Route::delete('/halaqah/{halaqah}/remove-member/{student}', [TahfizhHalaqahController::class, 'removeMember'])->name('halaqah.remove-member');
    });

    Route::middleware(['permission:isi-setoran-tahfizh'])->group(function () {
      Route::get('/setoran/create/{student}', [TahfizhSetoranController::class, 'create'])->name('setoran.create');
      Route::post('/setoran/store/{student}', [TahfizhSetoranController::class, 'store'])->name('setoran.store');
    });

    Route::middleware(['permission:kelola-rapor-tahfizh'])->group(function () {
      Route::get('/report/{student}', [TahfizhReportController::class, 'show'])->name('report.show');
      Route::get('/assessment/{student}', [TahfizhAssessmentController::class, 'edit'])->name('assessment.edit');
      Route::post('/assessment/{student}', [TahfizhAssessmentController::class, 'update'])->name('assessment.update');
      Route::get('/assessment/{student}/print', [TahfizhAssessmentController::class, 'print'])->name('assessment.print');
      Route::get('/assessment/{student}/history', [TahfizhAssessmentController::class, 'history'])->name('assessment.history');
      Route::get('/assessment/{student}/preview', [TahfizhAssessmentController::class, 'preview'])->name('assessment.preview');
      Route::get('/export/hafalan/{student}', [TahfizhExportController::class, 'form'])->name('export.form');
      Route::post('/export/hafalan/{student}', [TahfizhExportController::class, 'print'])->name('export.print');
      Route::post('/export/hafalan/{student}/preview', [TahfizhExportController::class, 'preview'])->name('export.preview');
    });

    Route::middleware(['permission:isi-jurnal-tahfizh'])->group(function () {
      Route::get('/journal', [TahfizhJournalController::class, 'index'])->name('journal.dashboard');
      Route::get('/journal/open/{schedule}', [TahfizhJournalController::class, 'createJournal'])->name('journal.open');
      Route::post('/journal/store-header/{schedule}', [TahfizhJournalController::class, 'storeJournalHeader'])->name('journal.store_header');
      Route::get('/journal/attendance/{journal}', [TahfizhJournalController::class, 'editStudentAttendance'])->name('journal.attendance');
      Route::post('/journal/update-attendance/{journal}', [TahfizhJournalController::class, 'updateStudentAttendance'])->name('journal.update_attendance');
    });

    Route::middleware(['permission:ajukan-izin-guru'])->group(function () {
      Route::get('/permission/permissions', [TahfizhPermissionController::class, 'index'])->name('permission.index');
      Route::get('/permission/create', [TahfizhPermissionController::class, 'create'])->name('permission.create');
      Route::post('/permission/store', [TahfizhPermissionController::class, 'store'])->name('permission.store');
      Route::get('/permission/get-schedules', [TahfizhPermissionController::class, 'getSchedules'])->name('permission.get_schedules');
    });

    // ADMIN TAHFIZH
    Route::prefix('admin')->name('admin.')->group(function () {
      
      Route::middleware(['permission:pantau-tahfizh-admin'])->group(function () {
        Route::get('/reports/teacher', [TahfizhAttendanceReportController::class, 'teacherRecap'])->name('reports.teacher');
        Route::get('/reports/student', [TahfizhAttendanceReportController::class, 'studentRecap'])->name('reports.student');
        Route::get('/reports/teacher/{id}', [TahfizhAttendanceReportController::class, 'teacherDetail'])->name('reports.teacher_detail');
        Route::get('/reports/student/{id}', [TahfizhAttendanceReportController::class, 'studentDetail'])->name('reports.student_detail');
        Route::post('/reports/teacher/{teacher}/hours', [TahfizhReportController::class, 'storeHours'])->name('reports.store_hours');
        
        Route::get('/monitoring', [TahfizhMonitoringController::class, 'index'])->name('monitoring.index');
        Route::get('/monitoring/data', [TahfizhMonitoringController::class, 'getRealtimeData'])->name('monitoring.data');
        Route::post('/monitoring/assign-badal', [TahfizhMonitoringController::class, 'assignBadal'])->name('monitoring.assign_badal');
        Route::post('/monitoring/approve-permission', [TahfizhMonitoringController::class, 'approvePermission'])->name('monitoring.approve_permission');
        Route::delete('/monitoring/remove-badal', [TahfizhMonitoringController::class, 'removeBadal'])->name('monitoring.remove_badal');
        
        Route::get('/setting', [TahfizhSettingController::class, 'index'])->name('setting.index'); // Alias untuk setting
        Route::post('/setting', [TahfizhSettingController::class, 'update'])->name('setting.update');
      });

      Route::middleware(['permission:kelola-jadwal-tahfizh'])->group(function () {
        Route::get('/schedules', [MasterScheduleController::class, 'index'])->name('schedules.index');
        Route::post('/schedules', [MasterScheduleController::class, 'store'])->name('schedules.store');
        Route::put('/schedules/{id}', [MasterScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{id}', [MasterScheduleController::class, 'destroy'])->name('schedules.destroy');
        Route::post('/schedules/{id}/toggle', [MasterScheduleController::class, 'toggleStatus'])->name('schedules.toggle');
      });
    });
    
    // Setting global (jika tidak masuk admin panel)
    Route::middleware(['permission:pantau-tahfizh-admin'])->group(function () {
      Route::get('/setting', [TahfizhSettingController::class, 'index'])->name('setting.index');
      Route::post('/setting', [TahfizhSettingController::class, 'update'])->name('setting.update');
    });
  });

  // ==========================================
  // CBT SYSTEM
  // ==========================================
  Route::prefix('admin/cbt')->name('admin.cbt.')->group(function () {
    Route::middleware(['permission:kelola-akun-cbt'])->group(function () {
      Route::get('/accounts', [CbtAccountController::class, 'index'])->name('accounts.index');
      Route::post('/cbt-accounts/generate-batch', [CbtAccountController::class, 'generateBatch'])->name('generate.batch');
      Route::post('/cbt-accounts/reset-batch', [CbtAccountController::class, 'resetBatch'])->name('reset.batch');
      Route::get('/accounts/print', [CbtAccountController::class, 'printCards'])->name('accounts.print');
      Route::post('/accounts/activate-massal', [CbtAccountController::class, 'activateMassal'])->name('accounts.activate_massal');
      Route::post('/accounts/deactivate-massal', [CbtAccountController::class, 'deactivateMassal'])->name('accounts.deactivate_massal');
      Route::post('/accounts/{account}/reset', [CbtAccountController::class, 'resetPin'])->name('accounts.reset');
      Route::post('/accounts/{account}/toggle', [CbtAccountController::class, 'toggleStatus'])->name('accounts.toggle');
    });

    Route::middleware(['permission:kelola-jadwal-ujian-cbt'])->group(function () {
      Route::get('/exams', [CbtExamController::class, 'index'])->name('exams.index');
      Route::get('/exams/create', [CbtExamController::class, 'create'])->name('exams.create');
      Route::post('/exams', [CbtExamController::class, 'store'])->name('exams.store');
      // Tambahkan route ini untuk update
      Route::put('/exams/{exam}', [CbtExamController::class, 'update'])->name('exams.update');
      Route::post('/exams/{exam}/toggle-pause', [CbtExamController::class, 'togglePause'])->name('exams.toggle_pause');
      Route::post('/exams/{exam}/refresh-token', [CbtExamController::class, 'refreshToken'])->name('exams.refresh_token');
      Route::delete('/exams/{exam}', [CbtExamController::class, 'destroy'])->name('exams.destroy');
    });

    Route::middleware(['permission:pantau-ujian-cbt'])->group(function () {
      Route::get('/exams/{exam}/monitor', [CbtMonitorController::class, 'index'])->name('exams.monitor');
      Route::get('/exams/{exam}/monitor/api', [CbtMonitorController::class, 'apiData'])->name('exams.monitor.api');
      Route::post('/exams/{exam}/force-finish/{studentExamId}', [CbtMonitorController::class, 'forceFinish'])->name('exams.force_finish');
      Route::post('/exams/{exam}/send-message/{studentExamId}', [CbtMonitorController::class, 'sendMessage'])->name('exams.send_message');
      // TAMBAHKAN BARIS INI UNTUK FITUR TEGUR SEMUA
      Route::post('/exams/{exam}/send-message-all', [CbtMonitorController::class, 'sendMessageAll'])->name('exams.send_message_all');
    });
  });

  Route::prefix('teacher/cbt')->name('teacher.cbt.')->group(function () {
    Route::middleware(['permission:kelola-bank-soal'])->group(function () {
      Route::get('/banks', [QuestionBankController::class, 'index'])->name('banks.index');
      Route::post('/banks', [QuestionBankController::class, 'store'])->name('banks.store');
      Route::get('/banks/{bank}', [QuestionBankController::class, 'show'])->name('banks.show');
      Route::delete('/banks/{bank}', [QuestionBankController::class, 'destroy'])->name('banks.destroy');
      Route::post('/banks/{bank}/toggle-status', [QuestionBankController::class, 'toggleStatus'])->name('banks.toggle_status');
      
      Route::get('/banks/{bank}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
      Route::post('/banks/{bank}/questions', [QuestionController::class, 'store'])->name('questions.store');
      Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
      Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
      Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    });

    Route::middleware(['permission:koreksi-hasil-ujian'])->group(function () {
      Route::get('/results', [ExamResultController::class, 'index'])->name('results.index');
      Route::get('/results/{exam}', [ExamResultController::class, 'show'])->name('results.show');
      Route::get('/results/correct/{studentExam}', [ExamResultController::class, 'correct'])->name('results.correct');
      Route::post('/results/correct/{studentExam}', [ExamResultController::class, 'storeCorrection'])->name('results.store_correction');
      Route::get('/results/{exam}/export/pdf', [ExamResultController::class, 'exportPdf'])->name('results.export.pdf');
      Route::get('/results/{exam}/export/excel', [ExamResultController::class, 'exportExcel'])->name('results.export.excel');
    });
  });
});

// ==========================================
// PORTAL UJIAN (CBT) SANTRI
// ==========================================
use App\Http\Controllers\Cbt\CbtAuthController;
use App\Http\Controllers\Cbt\Student\ExamEngineController;

Route::prefix('cbt')->name('cbt.')->group(function () {
  Route::middleware('guest:cbt')->group(function () {
    Route::get('/login', [CbtAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CbtAuthController::class, 'login'])->name('login.post');
  });

  Route::middleware('auth:cbt')->group(function () {
    Route::get('/dashboard', [ExamEngineController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [CbtAuthController::class, 'logout'])->name('logout');
    
    Route::post('/exam/{exam}/start', [ExamEngineController::class, 'startExam'])->name('engine.start');
    Route::get('/exam/engine/{studentExamId}', [ExamEngineController::class, 'showEngine'])->name('engine.show');
    Route::post('/exam/autosave/{answerId}', [ExamEngineController::class, 'autosave'])->name('engine.autosave');
    Route::post('/exam/finish/{studentExamId}', [ExamEngineController::class, 'finishExam'])->name('engine.finish');
    Route::post('/exam/heartbeat/{studentExamId}', [ExamEngineController::class, 'heartbeat'])->name('engine.heartbeat');
  });
});