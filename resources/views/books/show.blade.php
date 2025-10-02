<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-6 px-6 rounded-xl shadow-lg">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-indigo-100 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <h2 class="font-bold text-2xl">{{ $book->title }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">
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

            <div class="bg-white shadow-xl sm:rounded-xl p-8">
                <div class="flex flex-col md:flex-row gap-8">
                    <div class="md:w-1/3">
                        @if ($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full rounded-xl shadow-lg hover:shadow-2xl transition-shadow">
                        @else
                            <div class="w-full h-80 bg-gradient-to-br from-gray-100 to-gray-200 flex flex-col items-center justify-center text-gray-400 rounded-xl shadow-lg">
                                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <p>Tidak ada sampul</p>
                            </div>
                        @endif
                    </div>
                    <div class="md:w-2/3 space-y-5">
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $book->title }}</h3>
                            <p class="text-base text-indigo-600 mt-1">Oleh {{ $book->author }}</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-4 text-sm">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-50 text-yellow-700 rounded-full border border-yellow-200">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                {{ number_format($averageRating ?? 0, 1) }} ({{ $book->ratings->count() }} ulasan)
                            </span>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full border {{ $book->stock > 0 ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Stok: {{ $book->stock }}
                            </span>
                            @if ($book->isbn)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-50 text-gray-700 rounded-full border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                    ISBN: {{ $book->isbn }}
                                </span>
                            @endif
                        </div>

                        <div class="bg-gradient-to-br from-gray-50 to-indigo-50 rounded-xl p-4 border border-indigo-100">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $book->description }}</p>
                        </div>

                        @php($canBorrow = auth()->user()->role === \App\Models\User::ROLE_USER)

                        @if ($canBorrow)
                            <form action="{{ route('borrow.store') }}" method="POST" class="flex items-center gap-3 flex-wrap">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <div>
                                    <x-input-label for="due_date" value="Tanggal pengembalian (opsional)" />
                                    <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block" />
                                </div>
                                <div class="mt-6">
                                    @if ($book->stock > 0)
                                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Pinjam Buku
                                        </button>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 text-gray-500 font-semibold rounded-xl">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Stok Habis
                                        </span>
                                    @endif
                                </div>
                            </form>
                        @else
                            <div class="inline-flex items-center px-4 py-2 bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm rounded">
                                Hanya akun siswa yang dapat meminjam buku.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-xl sm:rounded-xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    Ulasan & Rating
                </h3>
                @if ($canBorrow)
                    <form action="{{ route('books.ratings.store', $book) }}" method="POST" class="space-y-4 mb-8 p-6 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border border-indigo-100">
                        @csrf
                        <div>
                            <x-input-label for="rating" value="Rating" />
                            <select id="rating" name="rating" class="mt-1 block w-32 border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Pilih</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" @selected(optional($userRating)->rating == $i)>{{ $i }}</option>
                                @endfor
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('rating')" />
                        </div>
                        <div>
                            <x-input-label for="comment" value="Komentar" />
                            <textarea id="comment" name="comment" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('comment', optional($userRating)->comment) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Ulasan
                        </button>
                    </form>
                @else
                    <p class="text-sm text-gray-500 mb-6">Login sebagai siswa untuk memberikan rating dan ulasan.</p>
                @endif

                <div class="space-y-4">
                    @forelse ($book->ratings as $rating)
                        <div class="border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-shadow bg-gradient-to-br from-white to-gray-50">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr($rating->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $rating->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $rating->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 px-3 py-1 bg-yellow-50 text-yellow-700 rounded-full border border-yellow-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="font-semibold">{{ $rating->rating }}</span>
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed">{{ $rating->comment }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">Belum ada ulasan untuk buku ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
