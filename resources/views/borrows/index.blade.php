<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-6 px-6 rounded-xl shadow-lg">
            <h2 class="font-bold text-2xl flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Manajemen Peminjaman
            </h2>
            <p class="text-indigo-100 text-sm mt-1">Kelola persetujuan dan pengembalian buku</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-lg shadow-md flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold">Berhasil!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-lg shadow-md flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold">Gagal!</p>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif
            
            <div class="bg-white shadow-xl sm:rounded-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Permintaan Aktif
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-500">
                        <thead class="text-xs uppercase bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700">
                            <tr class="border-b-2 border-indigo-600">
                                <th class="px-4 py-3">Pemohon</th>
                                <th class="px-4 py-3">Buku</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Catatan</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pending as $request)
                                <tr class="border-b">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-800">{{ $request->user->name }}</div>
                                        <p class="text-xs text-gray-500">{{ $request->user->email }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-800">{{ $request->book->title }}</div>
                                        <p class="text-xs text-gray-500">ISBN: {{ $request->book->isbn ?? '—' }}</p>
                                    </td>
                                    <td class="px-4 py-3"><x-status-badge :status="$request->status" /></td>
                                    <td class="px-4 py-3 text-sm">{{ $request->notes ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        @if ($request->status === \App\Models\BorrowRequest::STATUS_PENDING)
                                            <div class="space-y-2">
                                                <form action="{{ route('borrows.approve', $request) }}" method="POST" class="space-y-2" onsubmit="return validateApprove(this)">
                                                    @csrf
                                                    <div>
                                                        <label class="block text-xs text-gray-600 mb-1">Tenggat Pengembalian:</label>
                                                        <input type="date" name="due_date" class="border-gray-300 rounded-md shadow-sm text-sm w-full" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                                                    </div>
                                                    <button type="submit" class="w-full px-3 py-2 bg-green-600 text-white text-xs font-semibold rounded-md hover:bg-green-500 transition-colors flex items-center justify-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('borrows.reject', $request) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak permintaan ini?');">
                                                    @csrf
                                                    <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white text-xs font-semibold rounded-md hover:bg-red-500 transition-colors flex items-center justify-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif ($request->status === \App\Models\BorrowRequest::STATUS_RETURN_REQUESTED)
                                            <form action="{{ route('borrows.confirm-return', $request) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa buku telah dikembalikan?');">
                                                @csrf
                                                <button type="submit" class="w-full px-3 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-500 transition-colors flex items-center justify-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Konfirmasi Pengembalian
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-400">Tidak ada permintaan aktif.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $pending->links() }}
                </div>
            </div>

            <div class="bg-white shadow-xl sm:rounded-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Riwayat Peminjaman
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-500">
                        <thead class="text-xs uppercase bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700">
                            <tr class="border-b-2 border-purple-600">
                                <th class="px-4 py-3">Pemohon</th>
                                <th class="px-4 py-3">Buku</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Kode</th>
                                <th class="px-4 py-3">Diproses Oleh</th>
                                <th class="px-4 py-3">Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($history as $item)
                                <tr class="border-b">
                                    <td class="px-4 py-3">{{ $item->user->name }}</td>
                                    <td class="px-4 py-3">{{ $item->book->title }}</td>
                                    <td class="px-4 py-3"><x-status-badge :status="$item->status" /></td>
                                    <td class="px-4 py-3 font-mono text-xs">{{ $item->borrow_code ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ optional($item->processor)->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->updated_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center text-gray-400">Belum ada riwayat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function validateApprove(form) {
            const dueDate = form.querySelector('input[name="due_date"]').value;
            
            if (!dueDate) {
                alert('Harap pilih tanggal pengembalian!');
                return false;
            }
            
            const selectedDate = new Date(dueDate);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate <= today) {
                alert('Tanggal pengembalian harus setelah hari ini!');
                return false;
            }
            
            return confirm('Setujui permintaan peminjaman ini dengan tenggat ' + dueDate + '?');
        }

        // Auto dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
    @endpush
</x-app-layout>
