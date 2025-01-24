@extends('layouts.app')

@section('title', 'Attendances')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Absensi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Absensi</a></div>
                    <div class="breadcrumb-item">Semua Absensi</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Absensi</h2>
                <p class="section-lead">Kamu bisa melihat Absensi kehadiran berdasarkan nama, tanggal, bulan, dan tahun.</p>

                <div class="card">
                    <div class="card-header">
                        <h4>Filter Data</h4>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('attendances.index') }}">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Search by name" value="{{ request('name') }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control" id="date" name="date"
                                        value="{{ request('date') }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="month">Month</label>
                                    <select class="form-control" id="month" name="month">
                                        <option value="">Select Month</option>
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}"
                                                {{ request('month') == $m ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="year">Year</label>
                                    <select class="form-control" id="year" name="year">
                                        <option value="">Select Year</option>
                                        @for ($y = date('Y'); $y >= 2000; $y--)
                                            <option value="{{ $y }}"
                                                {{ request('year') == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('attendances.downloadPDF', request()->all()) }}" class="btn btn-danger">
                                Download PDF
                            </a>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Absen Pegawai</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-striped table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Latlong In</th>
                                        <th>Latlong Out</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances as $attendance)
                                        <tr>
                                            <td>{{ $attendance->user->name }}</td>
                                            <td>{{ $attendance->date }}</td>
                                            <td>{{ $attendance->time_in }}</td>
                                            <td>{{ $attendance->time_out }}</td>
                                            <td>{{ $attendance->latlon_in }}</td>
                                            <td>{{ $attendance->latlon_out }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right">
                            {{ $attendances->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
@endpush
