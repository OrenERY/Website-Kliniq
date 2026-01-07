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

    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i data-feather="dollar-sign" class="mr-2 text-orange-500"></i> Verifikasi Pembayaran (Pasien Umum)
        </h2>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Daftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poli Tujuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pendingPayments as $pending)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($pending->created_at)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $pending->nama_pasien }}
                            <div class="text-xs text-gray-500">{{ $pending->nik }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pending->poli_tujuan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <form action="{{ route('admin.verify', $pending->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-1 px-3 rounded text-xs transition flex items-center" onclick="return confirm('Konfirmasi pembayaran dan terbitkan antrian?')">
                                    <i data-feather="check-circle" class="w-3 h-3 mr-1"></i> Verifikasi & Terbitkan Antrian
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada antrian pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Active Queues -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i data-feather="list" class="mr-2 text-blue-500"></i> Daftar Antrian Poliklinik
        </h2>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
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
                            @if($pasien->status == 'menunggu')
                            <form action="{{ route('admin.assign', $pasien->id) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PATCH')
                                <select name="dokter" class="text-xs border-gray-300 rounded shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="" disabled selected>Pilih Dokter</option>
                                    @foreach($doctors as $doc)
                                    <option value="{{ $doc }}">{{ $doc }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs transition">
                                    Panggil
                                </button>
                            </form>
                            @elseif($pasien->status == 'dipanggil')
                                <button onclick="openExamModal('{{ $pasien->id }}', '{{ route('admin.store_examination', $pasien->id) }}')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs transition flex items-center">
                                    <i data-feather="clipboard" class="w-3 h-3 mr-1"></i> Input Hasil
                                </button>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Selesai</span>
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
    <!-- Exam Modal -->
    <div id="examModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="examForm" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Input Hasil Pemeriksaan</h3>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Diagnosa</label>
                            <textarea name="diagnosa" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tindakan</label>
                            <textarea name="tindakan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Resep Obat</label>
                            <textarea name="resep_obat" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Catatan Dokter</label>
                            <textarea name="catatan_dokter" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        </div>

                        <input type="hidden" name="id" id="patient_id">
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan & Selesai
                        </button>
                        <button type="button" onclick="closeExamModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openExamModal(id, url) {
            document.getElementById('examForm').action = url;
            document.getElementById('patient_id').value = id;
            document.getElementById('examModal').classList.remove('hidden');
        }

        function closeExamModal() {
            document.getElementById('examModal').classList.add('hidden');
        }
    </script>
@endsection
