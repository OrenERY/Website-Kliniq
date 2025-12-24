@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-100">
        <div class="p-6 bg-blue-600 text-white">
            <h2 class="text-2xl font-bold flex items-center">
                <i data-feather="user-plus" class="mr-2"></i> Pendaftaran Pasien Baru
            </h2>
            <p class="mt-1 text-blue-100">Silakan isi formulir di bawah ini untuk mengambil nomor antrian.</p>
        </div>
        
        <div class="p-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('pendaftaran.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK (Nomor Induk Kependudukan)</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-feather="credit-card" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text" name="nik" id="nik" required maxlength="16" minlength="16" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 border" placeholder="16 digit NIK">
                    </div>
                </div>

                <div>
                    <label for="nama_pasien" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-feather="user" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text" name="nama_pasien" id="nama_pasien" required class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 border" placeholder="Nama sesuai KTP">
                    </div>
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Domisili</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-start pt-2 pointer-events-none">
                            <i data-feather="map-pin" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <textarea name="alamat" id="alamat" rows="3" required class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 border" placeholder="Alamat lengkap"></textarea>
                    </div>
                </div>

                <div>
                    <label for="poli_tujuan" class="block text-sm font-medium text-gray-700 mb-1">Poli Tujuan</label>
                    <select name="poli_tujuan" id="poli_tujuan" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md border">
                        <option value="" disabled selected>Pilih Poli...</option>
                        <option value="Umum">Poli Umum</option>
                        <option value="Gigi">Poli Gigi</option>
                        <option value="Anak">Poli Anak</option>
                        <option value="Penyakit Dalam">Poli Penyakit Dalam</option>
                    </select>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <i data-feather="check-circle" class="mr-2 -ml-1 h-5 w-5"></i>
                        Ambil Nomor Antrian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
