<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Buku
            </h2>
            <a
                href="{{ route('books.create') }}"
                class="px-5 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
                style="display:inline-flex;align-items:center;justify-content:center;background-color:#4f46e5;color:#ffffff;font-weight:600;text-decoration:none;min-width:140px;"
            >
                {{ __('Tambah Buku') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Daftar Buku Perpustakaan</h3>
                            <p class="text-sm text-gray-500">Kelola koleksi buku, ubah detail, atau tambahkan buku baru untuk siswa.</p>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-500">
                            <thead class="text-xs uppercase bg-gray-50 text-gray-700">
                                <tr>
                                    <th class="px-4 py-3">Judul</th>
                                    <th class="px-4 py-3">Pengarang</th>
                                    <th class="px-4 py-3">Stok</th>
                                    <th class="px-4 py-3">Dibuat Oleh</th>
                                    <th class="px-4 py-3">Terakhir Diperbarui</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($books as $book)
                                    <tr class="border-b">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="h-12 w-9 bg-gray-100 rounded overflow-hidden">
                                                    @if ($book->cover_image)
                                                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-800">{{ $book->title }}</div>
                                                    <div class="text-xs text-gray-500">ISBN: {{ $book->isbn ?? '—' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">{{ $book->author }}</td>
                                        <td class="px-4 py-3">{{ $book->stock }}</td>
                                        <td class="px-4 py-3">{{ $book->creator->name ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $book->updated_at->diffForHumans() }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('books.edit', $book) }}" class="text-xs text-blue-600 hover:text-blue-500">Edit</a>
                                                <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Hapus buku ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-red-600 hover:text-red-500">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-gray-400">Belum ada buku.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
