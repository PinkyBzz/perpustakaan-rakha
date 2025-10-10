<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-8 -mt-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="font-bold text-3xl text-white leading-tight mb-2">Halo, {{ Auth::user()->name }}! 👋</h2>
                        <p class="text-indigo-100 text-sm">Selamat datang kembali di Perpustakaan Digital</p>
                    </div>
                    <a href="{{ route('books.catalog') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-indigo-50 transition-all shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Jelajahi Katalog
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Peminjaman Anda
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-500">
                        <thead class="text-xs uppercase bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border-b-2 border-indigo-600">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Buku</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 font-semibold">Jatuh Tempo</th>
                                <th class="px-4 py-3 font-semibold">Kode</th>
                                <th class="px-4 py-3 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activeBorrows as $borrow)
                                <tr class="border-b">
                                    <td class="px-4 py-3">{{ $borrow->book->title }}</td>
                                    <td class="px-4 py-3"><x-status-badge :status="$borrow->status" /></td>
                                    <td class="px-4 py-3">
                                        {{ optional($borrow->due_date)->translatedFormat('d F Y') ?? 'Menunggu konfirmasi' }}
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs">{{ $borrow->borrow_code ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        @if ($borrow->status === \App\Models\BorrowRequest::STATUS_APPROVED)
                                            <div class="flex flex-col gap-2">
                                                <button onclick="showQRCode('{{ $borrow->borrow_code }}', '{{ $borrow->book->title }}', '{{ optional($borrow->due_date)->translatedFormat('d F Y') }}')" class="px-3 py-1.5 text-xs font-semibold text-white bg-purple-600 rounded-lg hover:bg-purple-500 transition-colors flex items-center justify-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                                    </svg>
                                                    Tampilkan QR Code
                                                </button>
                                                <form action="{{ route('borrow.request-return', $borrow) }}" method="POST">
                                                    @csrf
                                                    <button class="w-full px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 transition-colors flex items-center justify-center gap-1" type="submit">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                        </svg>
                                                        Minta Pengembalian
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif ($borrow->status === \App\Models\BorrowRequest::STATUS_RETURN_REQUESTED)
                                            <span class="text-xs text-gray-500 italic">Menunggu verifikasi petugas</span>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-400">Belum ada riwayat peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Reservasi Antrian --}}
            @php($myReservations = \App\Models\BookReservation::with('book')->where('user_id', auth()->id())->whereIn('status', ['waiting', 'notified'])->orderBy('created_at')->get())
            @if($myReservations->count() > 0)
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl shadow-md p-6 border border-yellow-200">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Antrian Reservasi Buku
                </h3>
                <div class="space-y-3">
                    @foreach($myReservations as $reservation)
                        @php($queuePos = \App\Models\BookReservation::where('book_id', $reservation->book_id)->where('status', 'waiting')->where('created_at', '<', $reservation->created_at)->count() + 1)
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-yellow-100">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $reservation->book->title }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $reservation->book->author }}</p>
                                    @if($reservation->status === 'waiting')
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                                Antrian ke-{{ $queuePos }}
                                            </span>
                                            <span class="text-xs text-gray-500">Sejak {{ $reservation->created_at->diffForHumans() }}</span>
                                        </div>
                                    @elseif($reservation->status === 'notified')
                                        <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded">
                                            <p class="text-sm text-green-800 font-semibold">✅ Buku Tersedia!</p>
                                            <p class="text-xs text-green-700 mt-1">
                                                Berlaku sampai {{ \Carbon\Carbon::parse($reservation->expires_at)->format('d M Y H:i') }}
                                            </p>
                                            <a href="{{ route('books.show', $reservation->book) }}" class="inline-block mt-2 text-sm text-green-700 hover:text-green-900 underline font-semibold">
                                                Pinjam Sekarang →
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <form action="{{ route('reservations.cancel', $reservation) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Batalkan reservasi?')" class="text-sm text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                    Rekomendasi Buku Untuk Anda
                </h3>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($availableBooks as $book)
                        <div class="bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden flex flex-col group">
                            <div class="relative overflow-hidden">
                                @if ($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-48 w-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="h-48 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-400">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5 flex-1 flex flex-col">
                                <h4 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">{{ $book->title }}</h4>
                                <p class="text-sm text-indigo-600 font-medium mb-2">{{ $book->author }}</p>
                                <p class="text-sm text-gray-600 flex-1">{{ \Illuminate\Support\Str::limit($book->description, 120) }}</p>
                                <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                                    <span>⭐ {{ number_format($book->ratings_avg_rating ?? 0, 1) }} ({{ $book->ratings_count }})</span>
                                    <span>Stok: {{ $book->stock }}</span>
                                </div>
                                <div class="mt-4 flex items-center justify-between gap-2">
                                    <form action="{{ route('borrow.store') }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg font-semibold text-sm transition-all {{ $book->stock > 0 ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:shadow-lg hover:-translate-y-0.5' : 'bg-gray-200 text-gray-500 cursor-not-allowed' }}" {{ $book->stock > 0 ? '' : 'disabled' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Pinjam
                                        </button>
                                    </form>
                                    <a href="{{ route('books.show', $book) }}" class="text-xs text-gray-500 hover:text-gray-700">Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div id="qrModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all" onclick="event.stopPropagation()">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        QR Code Peminjaman
                    </h3>
                    <button onclick="closeQRModal()" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 mb-4">
                    <div class="flex justify-center mb-4">
                        <div id="qrcode" class="bg-white p-4 rounded-lg shadow-lg"></div>
                    </div>
                    <div class="text-center space-y-2">
                        <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                            <p class="text-xs text-gray-500 mb-1">Kode Peminjaman</p>
                            <p id="borrowCode" class="text-xl font-bold text-purple-600 font-mono"></p>
                        </div>
                        <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                            <p class="text-xs text-gray-500 mb-1">Buku</p>
                            <p id="bookTitle" class="text-sm font-semibold text-gray-800"></p>
                        </div>
                        <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                            <p class="text-xs text-gray-500 mb-1">Tenggat Pengembalian</p>
                            <p id="dueDate" class="text-sm font-semibold text-red-600"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <p class="text-sm text-yellow-800">
                        <strong>📱 Cara Penggunaan:</strong><br>
                        Tunjukkan QR Code ini ke petugas perpustakaan untuk verifikasi peminjaman buku Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- QRCode.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
    <script>
        let qrcodeInstance = null;

        function showQRCode(borrowCode, bookTitle, dueDate) {
            // Update modal content
            document.getElementById('borrowCode').textContent = borrowCode;
            document.getElementById('bookTitle').textContent = bookTitle;
            document.getElementById('dueDate').textContent = dueDate;

            // Clear previous QR code
            const qrcodeDiv = document.getElementById('qrcode');
            qrcodeDiv.innerHTML = '';

            // Generate new QR code with borrow information
            const qrData = JSON.stringify({
                code: borrowCode,
                book: bookTitle,
                due_date: dueDate,
                type: 'borrow_verification'
            });

            qrcodeInstance = new QRCode(qrcodeDiv, {
                text: qrData,
                width: 200,
                height: 200,
                colorDark: "#7c3aed",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            // Show modal
            document.getElementById('qrModal').classList.remove('hidden');
        }

        function closeQRModal() {
            document.getElementById('qrModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('qrModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeQRModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeQRModal();
            }
        });
    </script>
    @endpush
</x-app-layout>
