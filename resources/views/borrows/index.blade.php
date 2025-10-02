<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Peminjaman</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Permintaan Aktif</h3>
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
                @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-500">
                        <thead class="text-xs uppercase bg-gray-50 text-gray-700">
                            <tr>
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
                                            <form action="{{ route('borrows.approve', $request) }}" method="POST" class="flex flex-col sm:flex-row gap-2 mb-2">
                                                @csrf
                                                <input type="date" name="due_date" class="border-gray-300 rounded-md shadow-sm text-sm" required>
                                                <button type="submit" class="px-3 py-2 bg-green-600 text-white text-xs font-semibold rounded-md hover:bg-green-500">Setujui</button>
                                            </form>
                                            <form action="{{ route('borrows.reject', $request) }}" method="POST" onsubmit="return confirm('Tolak permintaan ini?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-2 bg-red-600 text-white text-xs font-semibold rounded-md hover:bg-red-500">Tolak</button>
                                            </form>
                                        @elseif ($request->status === \App\Models\BorrowRequest::STATUS_RETURN_REQUESTED)
                                            <form action="{{ route('borrows.confirm-return', $request) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian buku?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-500">Konfirmasi Pengembalian</button>
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

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Peminjaman</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-500">
                        <thead class="text-xs uppercase bg-gray-50 text-gray-700">
                            <tr>
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
</x-app-layout>
