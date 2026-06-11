@extends('layouts.app')

@section('title', 'Jam Kerja Kasir - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-clock', 'title' => 'Jam Kerja', 'subtitle' => 'Kasir: ' . $kasir->name])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <a href="{{ route('kasir-management.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-success-soft"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="form-card full">
        <p class="text-muted" style="margin-top:0;">Centang hari kerja dan atur jam masuk/keluar.</p>
        <form action="{{ route('kasir-management.update-schedule', $kasir->id) }}" method="POST">
            @csrf @method('PUT')
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr><th>Aktif</th><th>Hari</th><th>Jam Masuk</th><th>Jam Keluar</th></tr>
                    </thead>
                    <tbody>
                        @foreach($days as $day)
                            @php $sched = $schedules[$day] ?? null; @endphp
                            <tr>
                                <td><input type="checkbox" name="days[{{ $day }}][aktif]" value="1" style="width:18px;height:18px;accent-color:var(--brand-primary);" {{ $sched ? 'checked' : '' }}></td>
                                <td style="text-transform:capitalize; font-weight:500;">{{ $day }}</td>
                                <td><input type="time" name="days[{{ $day }}][jam_masuk]" class="form-control" style="max-width:150px;margin:0 auto;" value="{{ $sched->jam_masuk ?? '08:00' }}"></td>
                                <td><input type="time" name="days[{{ $day }}][jam_keluar]" class="form-control" style="max-width:150px;margin:0 auto;" value="{{ $sched->jam_keluar ?? '17:00' }}"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan Jadwal</button>
            </div>
        </form>
    </div>
@endsection
