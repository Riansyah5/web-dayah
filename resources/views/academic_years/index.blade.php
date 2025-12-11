@extends('layouts.app')
@section('title', 'Academic Years')
@push('link')
@endpush
@push('styles')
  
@endpush
@section('content')
<div class="card col-md-8 mx-auto">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Tahun Ajaran & Semester</h4>
        <a href="{{ route('academic-years.create') }}" class="btn btn-primary btn-sm">Buat Baru</a>
    </div>
    <div class="card-body">
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($years as $year)
                <tr class="{{ $year->is_active ? 'table-success' : '' }}">
                    <td>{{ $year->name }}</td>
                    <td>{{ $year->semester }}</td>
                    <td>
                        @if($year->is_active)
                            <span class="badge bg-success">AKTIF</span>
                        @else
                            <span class="badge bg-secondary">Non-Aktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('academic-years.edit', $year->id) }}" class="btn btn-warning btn-sm">Edit / Aktifkan</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('scripts')
@endpush
