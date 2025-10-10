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
            @if(auth()->user() && in_array(auth()->user()->role, ['admin','pegawai']))
            <div class="bg-white shadow-xl sm:rounded-xl p-6 border border-indigo-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Peminjaman (Tamu / User)
                </h3>
                <form method="POST" action="{{ route('borrows.staff-create') }}" class="grid gap-4 md:grid-cols-4" onsubmit="return confirm('Buat peminjaman langsung? Stok buku akan berkurang.');">
                    @csrf
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Buku</label>
                        <select name="book_id" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">-- Pilih Buku --</option>
                            @foreach(\App\Models\Book::orderBy('title')->limit(200)->get() as $b)
                                <option value="{{ $b->id }}">{{ $b->title }} (Stok: {{ $b->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tenggat</label>
                        <input type="date" name="due_date" class="w-full border-gray-300 rounded-md shadow-sm text-sm" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    <div class="md:col-span-4 pt-2">
                        <div class="flex items-center gap-4 text-sm">
                            <label class="font-semibold">Mode:</label>
                            <label class="inline-flex items-center gap-1"><input type="radio" name="mode" value="existing" checked onchange="toggleGuest(false)"> <span>User Terdaftar</span></label>
                            <label class="inline-flex items-center gap-1"><input type="radio" name="mode" value="guest" onchange="toggleGuest(true)"> <span>Tamu (Tanpa Akun)</span></label>
                        </div>
                    </div>
                    <div id="existing-user-wrapper" class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">User Terdaftar</label>
                        <select name="existing_user_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">-- Pilih User --</option>
                            @foreach(\App\Models\User::where('role','user')->orderBy('name')->limit(300)->get() as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="guest-fields" class="md:col-span-4 hidden border rounded-md p-4 bg-gray-50">
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Tamu</label>
                                <input type="text" name="guest_name" class="w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Nama lengkap">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Kontak (Email / Telp)</label>
                                <input type="text" name="guest_contact" class="w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Email atau No. HP">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Identifier (NIS/NIK)</label>
                                <input type="text" name="guest_identifier" class="w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Opsional">
                            </div>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-2">Data tamu hanya tersimpan di record peminjaman.</p>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Catatan</label>
                        <input type="text" name="notes" class="w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Catatan opsional">
                    </div>
                    <div class="md:col-span-4 flex justify-end">
                        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-500 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Simpan & Setujui Langsung
                        </button>
                    </div>
                </form>
            </div>
            @endif
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
                                    <td class="px-4 py-3 actions-cell force-visible">
                                        <div class="font-semibold text-gray-800">{{ $request->user ? $request->user->name : $request->guest_name }}</div>
                                        <p class="text-xs text-gray-500">{{ $request->user ? $request->user->email : ($request->guest_contact ?? 'Tamu') }}</p>
                                        @if($request->is_guest)
                                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Tamu</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-800">{{ $request->book->title }}</div>
                                        <p class="text-xs text-gray-500">ISBN: {{ $request->book->isbn ?? '—' }}</p>
                                    </td>
                                    <td class="px-4 py-3"><x-status-badge :status="$request->status" /></td>
                                    <td class="px-4 py-3 text-sm">{{ $request->notes ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        @if ($request->status === \App\Models\BorrowRequest::STATUS_PENDING)
                                            <div class="space-y-2" style="opacity: 1 !important; visibility: visible !important;">
                                                <form action="{{ route('borrows.approve', $request) }}" method="POST" class="space-y-2" onsubmit="return validateApprove(this)" style="opacity: 1 !important; visibility: visible !important;">
                                                    @csrf
                                                    <div style="opacity: 1 !important; visibility: visible !important;">
                                                        <label class="block text-xs text-gray-600 mb-1" style="opacity: 1 !important; visibility: visible !important;">Tenggat Pengembalian:</label>
                                                        <input type="date" name="due_date" class="border-gray-300 rounded-md shadow-sm text-sm w-full" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required style="opacity: 1 !important; visibility: visible !important; display: block !important;">
                                                    </div>
                                                    <button type="submit" class="w-full px-3 py-2 bg-green-600 text-white text-xs font-semibold rounded-md hover:bg-green-500 transition-colors flex items-center justify-center gap-1" style="opacity: 1 !important; visibility: visible !important; display: flex !important; animation: none !important;">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('borrows.reject', $request) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak permintaan ini?');" style="opacity: 1 !important; visibility: visible !important;">
                                                    @csrf
                                                    <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white text-xs font-semibold rounded-md hover:bg-red-500 transition-colors flex items-center justify-center gap-1" style="opacity: 1 !important; visibility: visible !important; display: flex !important; animation: none !important;">
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
                                    <td class="px-4 py-3">
                                        <div class="font-semibold">{{ $item->user ? $item->user->name : $item->guest_name }}</div>
                                        @if($item->is_guest)
                                            <span class="text-xs text-yellow-600">(Tamu)</span>
                                        @endif
                                    </td>
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

        // Page ready handlers (no auto-dismiss to avoid side effects)
        document.addEventListener('DOMContentLoaded', function() {
            window.toggleGuest = function(isGuest) {
                const guestBox = document.getElementById('guest-fields');
                const existingWrapper = document.getElementById('existing-user-wrapper');
                const existingSelect = existingWrapper.querySelector('select');
                
                if (isGuest) {
                    guestBox.classList.remove('hidden');
                    existingWrapper.classList.add('opacity-50');
                    existingSelect.disabled = true;
                    existingSelect.value = ''; // Clear value when disabled
                    // Make guest fields required
                    document.querySelector('input[name="guest_name"]').required = true;
                } else {
                    guestBox.classList.add('hidden');
                    existingWrapper.classList.remove('opacity-50');
                    existingSelect.disabled = false;
                    // Make guest fields not required
                    document.querySelector('input[name="guest_name"]').required = false;
                }
            };

            // DO NOT auto-dismiss alerts anymore
            // Keep success/error alerts visible to avoid accidentally removing siblings

            // CRITICAL: Force buttons to stay visible at all times
            const forceButtonVisibility = () => {
                // Approve/Reject buttons
                const buttons = document.querySelectorAll('td button[type="submit"], form button[type="submit"]');
                buttons.forEach(button => {
                    button.style.opacity = '1';
                    button.style.visibility = 'visible';
                    button.style.display = button.classList.contains('flex') ? 'flex' : 'block';
                    button.style.animation = 'none';
                });

                // Ensure forms and inputs stay visible
                const forms = document.querySelectorAll('td form');
                forms.forEach(form => {
                    form.style.opacity = '1';
                    form.style.visibility = 'visible';
                    form.style.animation = 'none';
                });

                const inputs = document.querySelectorAll('td input, td select, td textarea');
                inputs.forEach(input => {
                    input.style.opacity = '1';
                    input.style.visibility = 'visible';
                    input.style.display = 'block';
                    input.style.animation = 'none';
                });
            };

            // Run immediately
            forceButtonVisibility();

            // MutationObserver: re-apply visibility if DOM changes
            const tbody = document.querySelector('table tbody');
            if (tbody) {
                const observer = new MutationObserver((mutations) => {
                    let needEnforce = false;
                    for (const m of mutations) {
                        if (m.type === 'childList' || m.type === 'attributes') {
                            needEnforce = true; break;
                        }
                    }
                    if (needEnforce) {
                        forceButtonVisibility();
                        // Strip potentially harmful classes
                        document.querySelectorAll('.actions-cell, .actions-cell *').forEach(el => {
                            el.classList.remove('hidden', 'invisible');
                            const cs = window.getComputedStyle(el);
                            if (cs.display === 'none' || cs.opacity === '0' || cs.visibility === 'hidden') {
                                el.style.display = el.tagName.toLowerCase() === 'button' ? 'flex' : 'block';
                                el.style.opacity = '1';
                                el.style.visibility = 'visible';
                            }
                        });
                    }
                });
                observer.observe(tbody, { childList: true, subtree: true, attributes: true, attributeFilter: ['class', 'style'] });
                console.log('Button protection observer attached');
            }

            // Fallback interval – lighter frequency
            setInterval(forceButtonVisibility, 500);

            console.log('Button protection active – approve/reject buttons locked visible');
        });
    </script>
    @endpush
</x-app-layout>
