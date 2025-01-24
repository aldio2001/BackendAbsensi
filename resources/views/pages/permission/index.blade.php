@extends('layouts.app')

@section('title', 'Permissions')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Permissions</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Permissions</a></div>
                    <div class="breadcrumb-item">All Permission</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Permission</h2>
                <p class="section-lead">Kamu bisa melihat Izin kehadiran berdasarkan nama, tanggal, bulan, dan tahun.</p>

                <div class="card">
                    <div class="card-header">
                        <h4>Filter Data</h4>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('permissions.index') }}">
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
                            <a href="{{ route('permission.downloadPDF', request()->all()) }}" class="btn btn-danger">
                                Download PDF
                            </a>


                                </form>
                            </div>
                        </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table-striped table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Position</th>
                                                <th>Department</th>
                                                <th>Date Permission</th>
                                                <th>Is Approval</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($permissions as $permission)
                                                <tr>
                                                    <td>{{ $permission->user->name ?? 'Data user tidak tersedia' }}</td>
                                                    <td>{{ $permission->user->position ?? 'Data user tidak tersedia' }}</td>
                                                    <td>{{ $permission->user->department ?? 'Data user tidak tersedia' }}</td>
                                                    <td>{{ $permission->date_permission }}</td>
                                                    <td>
                                                        {{ $permission->is_approved ? 'Approved' : 'Not Approved' }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('permissions.show', $permission->id) }}" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i> Detail
                                                            </a>
                                                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="ml-2">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger confirm-delete">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No permissions found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $permissions->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
@endpush
