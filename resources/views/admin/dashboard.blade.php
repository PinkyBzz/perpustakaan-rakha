<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-8 px-6 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-3xl mb-2">
                        🎯 Dashboard Admin
                    </h2>
                    <p class="text-indigo-100 text-sm">Monitor dan kelola sistem perpustakaan</p>
                </div>
                <div class="text-right text-sm text-indigo-100">
                    <p>Terakhir diperbarui:</p>
                    <p class="font-semibold">{{ now()->translatedFormat('d F Y H:i') }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Buku</p>
                                <p class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">{{ $stats['total_books'] }}</p>
                            </div>
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
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
                                <p class="text-sm text-gray-500 mb-1">Total Pengguna</p>
                                <p class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">{{ $stats['total_users'] }}</p>
                            </div>
                            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
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
            </div>

            <div class="bg-white shadow-lg sm:rounded-xl p-6">
                <div class="flex flex-col lg:flex-row gap-6">
                    <div class="lg:w-1/2">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Aktivitas Peminjaman
                        </h3>
                        <canvas id="activityChart"></canvas>
                    </div>
                    <div class="lg:w-1/2">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Permintaan Terbaru
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left text-gray-500">
                                <thead class="text-xs uppercase bg-gray-50 text-gray-700">
                                    <tr>
                                        <th class="px-4 py-3">Kode</th>
                                        <th class="px-4 py-3">Buku</th>
                                        <th class="px-4 py-3">Pemohon</th>
                                        <th class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentRequests as $request)
                                        <tr class="border-b">
                                            <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $request->borrow_code ?? '—' }}</td>
                                            <td class="px-4 py-3">{{ $request->book->title }}</td>
                                            <td class="px-4 py-3">
                                                {{ $request->user ? $request->user->name : $request->guest_name }}
                                                @if($request->is_guest)
                                                    <span class="text-xs text-yellow-600">(Tamu)</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <x-status-badge :status="$request->status" />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-3 text-center text-gray-400">Belum ada permintaan terbaru.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-lg sm:rounded-xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Pengguna Terbaru
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-500">
                        <thead class="text-xs uppercase bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700">
                            <tr class="border-b-2 border-indigo-600">
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3">Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentUsers as $user)
                                <tr class="border-b">
                                    <td class="px-4 py-3">{{ $user->name }}</td>
                                    <td class="px-4 py-3">{{ $user->email }}</td>
                                    <td class="px-4 py-3 capitalize">{{ $user->role }}</td>
                                    <td class="px-4 py-3">{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-400">Belum ada pengguna.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const activityData = @json($activityChart);
            const ctx = document.getElementById('activityChart');

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: Object.keys(activityData).map((status) => status.replace('_', ' ')),
                    datasets: [{
                        data: Object.values(activityData),
                        backgroundColor: [
                            '#60a5fa',
                            '#34d399',
                            '#f87171',
                            '#fbbf24',
                            '#a855f7',
                        ],
                    }],
                },
            });
        </script>
    @endpush
</x-app-layout>
