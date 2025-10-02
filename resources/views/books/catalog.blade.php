<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-8 -mt-6 mb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="font-bold text-3xl text-white leading-tight mb-2">📚 Katalog Buku</h2>
                        <p class="text-indigo-100 text-sm">Temukan buku favorit Anda dan mulai membaca</p>
                    </div>
                    <form action="{{ route('books.catalog') }}" method="GET" class="flex items-center gap-2">
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul atau penulis..." class="pl-10 pr-4 py-2.5 bg-white/20 backdrop-blur-sm text-white placeholder-indigo-200 border-2 border-white/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-white focus:bg-white/30 transition-all w-64" />
                            <svg class="absolute left-3 top-3 w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <button type="submit" class="px-5 py-2.5 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-indigo-50 transition-all shadow-lg">Cari</button>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-6">{{ session('error') }}</div>
            @endif
            @php($isStudent = auth()->user()->role === \App\Models\User::ROLE_USER)

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($books as $book)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden flex flex-col group">
                        <div class="relative overflow-hidden">
                            @if ($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-56 w-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            @else
                                <div class="h-56 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-400">
                                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-2 group-hover:text-indigo-600 transition-colors">{{ $book->title }}</h3>
                            <p class="text-sm text-indigo-600 font-medium mb-3 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $book->author }}
                            </p>
                            <p class="text-sm text-gray-600 flex-1 line-clamp-3 mb-4">{{ \Illuminate\Support\Str::limit($book->description, 120) }}</p>
                            <div class="mt-auto">
                                <div class="flex items-center justify-between text-sm mb-4">
                                    <div class="flex items-center gap-1 text-yellow-500">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                        <span class="font-semibold text-gray-700">{{ number_format($book->ratings_avg_rating ?? 0, 1) }}</span>
                                        <span class="text-gray-500">({{ $book->ratings_count }})</span>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $book->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $book->stock > 0 ? "Tersedia: {$book->stock}" : 'Habis' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if ($isStudent)
                                    <form action="{{ route('borrow.store') }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit"
                                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg font-semibold text-sm transition-all duration-200 {{ $book->stock > 0 ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:shadow-lg hover:-translate-y-0.5' : 'bg-gray-200 text-gray-500 cursor-not-allowed' }}"
                                                {{ $book->stock > 0 ? '' : 'disabled' }}>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Pinjam
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('books.show', $book) }}" class="flex items-center gap-1 px-4 py-2.5 text-indigo-600 hover:text-indigo-700 font-semibold text-sm hover:bg-indigo-50 rounded-lg transition-all">
                                    <span>Detail</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Tidak ada buku tersedia.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $books->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
