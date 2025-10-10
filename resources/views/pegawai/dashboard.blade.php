<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-8 px-6 rounded-xl shadow-lg">
            <h2 class="font-bold text-3xl mb-2">
                📚 Dashboard Pegawai
            </h2>
            <p class="text-purple-100 text-sm">Kelola buku dan permintaan peminjaman</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Buku</p>
                                <p class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">{{ $stats['total_books'] }}</p>
                            </div>
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Permintaan Pending</p>
                                <p class="text-4xl font-bold bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-transparent">{{ $stats['pending_requests'] }}</p>
                            </div>
                            <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Menunggu Pengembalian</p>
                                <p class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent">{{ $stats['return_requested'] }}</p>
                            </div>
                            <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-lg sm:rounded-xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Permintaan Terbaru
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-500">
                        <thead class="text-xs uppercase bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700">
                            <tr class="border-b-2 border-purple-600">
                                <th class="px-4 py-3">Buku</th>
                                <th class="px-4 py-3">Pemohon</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentRequests as $request)
                                <tr class="border-b">
                                    <td class="px-4 py-3">{{ $request->book->title }}</td>
                                    <td class="px-4 py-3">
                                        {{ $request->user ? $request->user->name : $request->guest_name }}
                                        @if($request->is_guest)
                                            <span class="text-xs text-yellow-600">(Tamu)</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3"><x-status-badge :status="$request->status" /></td>
                                    <td class="px-4 py-3">{{ $request->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-400">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
