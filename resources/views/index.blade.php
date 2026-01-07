@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center items-center py-12 md:py-0">
    <div class="text-center mb-8 md:mb-12 px-4">
        <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-4">
            Selamat Datang di <span class="text-blue-600">RS Sehat Selalu</span>
        </h1>
        <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
            Sistem pendaftaran dan antrian modern untuk pelayanan kesehatan yang lebih baik. Silakan pilih akses masuk Anda di bawah ini.
        </p>
    </div>

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-gray-100 relative overflow-hidden">
        <div class="text-center mb-8 relative z-10">
            <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 text-blue-600">
                <i data-feather="user" class="w-8 h-8"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Login Pasien</h2>
            <p class="text-gray-500 mt-2">Masuk menggunakan NIK dan Nama Lengkap.</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 text-sm relative z-10">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('patient.login') }}" method="POST" class="space-y-5 relative z-10">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i data-feather="credit-card" class="w-5 h-5"></i>
                    </span>
                    <input type="text" name="nik" required class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Nomor Induk Kependudukan">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i data-feather="user" class="w-5 h-5"></i>
                    </span>
                    <input type="text" name="nama" required class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Sesuai KTP">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-300 shadow-md flex justify-center items-center">
                Masuk / Login <i data-feather="arrow-right" class="ml-2 w-4 h-4"></i>
            </button>
        </form>

        <div class="mt-6 text-center text-sm relative z-10">
            <p class="text-gray-500">Belum punya akun?</p>
            <a href="{{ route('patient.register') }}" class="text-blue-600 font-semibold hover:underline">Daftar Pasien Baru</a>
        </div>

        <!-- Decorative Background -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-50 rounded-full opacity-50 z-0"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-purple-50 rounded-full opacity-50 z-0"></div>
    </div>

    <!-- Stats Section -->
    <div class="w-full max-w-6xl px-4 mt-16">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">Informasi Antrian Terkini</h2>
            <p class="text-gray-600 mt-2">Pantau kondisi antrian secara real-time</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Menunggu -->
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-yellow-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase">Antrian Menunggu</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $menunggu }}</p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-full text-yellow-500">
                        <i data-feather="clock" class="w-8 h-8"></i>
                    </div>
                </div>
            </div>

            <!-- Sedang Dirawat -->
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase">Sedang Dirawat</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $sedangDirawat }}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-full text-green-500">
                        <i data-feather="activity" class="w-8 h-8"></i>
                    </div>
                </div>
            </div>

            <!-- Total -->
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase">Total Pasien Hari Ini</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $totalPasien }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full text-blue-500">
                        <i data-feather="users" class="w-8 h-8"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Doctor Schedule Section -->
    <div class="w-full max-w-6xl px-4 mt-20 mb-10">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">Jadwal Dokter</h2>
            <p class="text-gray-600 mt-2">Daftar dokter spesialis yang tersedia</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($dokter as $doc)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="h-24 bg-blue-50 flex items-center justify-center">
                    <img src="{{ $doc['foto'] }}" alt="{{ $doc['nama'] }}" class="w-20 h-20 rounded-full border-4 border-white shadow-sm translate-y-8">
                </div>
                <div class="pt-12 pb-6 px-6 text-center">
                    <h3 class="font-bold text-gray-900 text-lg">{{ $doc['nama'] }}</h3>
                    <p class="text-blue-600 text-sm font-medium mb-4">{{ $doc['spesialis'] }}</p>
                    
                    <div class="flex items-center justify-center text-gray-500 text-sm bg-gray-50 py-2 rounded-lg">
                        <i data-feather="calendar" class="w-4 h-4 mr-2"></i>
                        <span>{{ $doc['jadwal'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Footer Note -->
    <div class="mt-16 text-center text-gray-400 text-sm">
        <p>&copy; {{ date('Y') }} Rumah Sakit Sehat Selalu. Sistem Antrian Digital.
            (Protoype)</p>
    </div>
</div>
@endsection
