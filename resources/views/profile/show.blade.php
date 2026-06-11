@extends('layouts.app')

@section('title', 'Profil - MyStock')

@section('content')
    @include('components.appbar', [
        'color' => 'blue',
        'icon' => 'bi-person-circle',
        'title' => 'Profil Saya',
        'subtitle' => 'Informasi akun pengguna',
    ])

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit Profil</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-success-soft"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="profile-card">
        <div class="profile-head">
            <img src="{{ $user->foto ? asset('storage/profile_photos/'.$user->foto) : asset('img/default-profile.png') }}" alt="Profile" class="profile-avatar">
            <div>
                <h3>{{ $user->name }}</h3>
                <span class="role-tag">{{ $user->role ?? 'kasir' }}</span>
            </div>
        </div>
        <div class="detail-row"><span class="key">Nama Pemilik</span><span class="val">{{ $user->nama_pemilik }}</span></div>
        <div class="detail-row"><span class="key">Username</span><span class="val">{{ $user->username }}</span></div>
        <div class="detail-row"><span class="key">Email</span><span class="val">{{ $user->email }}</span></div>
    </div>
@endsection
