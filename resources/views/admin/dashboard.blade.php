@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-gray-500">Kelola antrian dan penugasan dokter hari ini.</p>
        </div>
        <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-medium">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p class="font-bold">Berhasil!</p>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-700">Daftar Pasien Menunggu</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Antrian</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poli Tujuan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pasiens as $pasien)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-bold text-gray-900">{{ $pasien->nomor_antrian }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $pasien->nama_pasien }}</div>
                            <div class="text-xs text-gray-500">NIK: {{ $pasien->nik }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $pasien->poli_tujuan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $pasien->status === 'menunggu' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $pasien->status === 'dipanggil' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $pasien->status === 'selesai' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($pasien->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pasien->doctor_name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if(!$pasien->doctor_name)
                                <form action="{{ route('admin.assign', $pasien->id) }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="doctor_name" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full sm:w-auto" required>
                                        <option value="" disabled selected>Pilih Dokter</option>
                                        @foreach($doctors[$pasien->poli_tujuan] ?? [] as $doctor)
                                            <option value="{{ $doctor }}">{{ $doctor }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded text-xs transition w-full sm:w-auto text-center">
                                        Assign
                                    </button>
                                </form>
                            @else
                                <span class="text-green-600 flex items-center">
                                    <i data-feather="check" class="w-4 h-4 mr-1"></i> Assigned
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            Belum ada pasien yang mendaftar hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
