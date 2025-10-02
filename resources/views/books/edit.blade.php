<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Buku</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="title" value="Judul" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $book->title)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="author" value="Pengarang" />
                            <x-text-input id="author" name="author" type="text" class="mt-1 block w-full" :value="old('author', $book->author)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('author')" />
                        </div>

                        <div>
                            <x-input-label for="publisher" value="Penerbit" />
                            <x-text-input id="publisher" name="publisher" type="text" class="mt-1 block w-full" :value="old('publisher', $book->publisher)" />
                            <x-input-error class="mt-2" :messages="$errors->get('publisher')" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="publication_year" value="Tahun Terbit" />
                                <x-text-input id="publication_year" name="publication_year" type="number" class="mt-1 block w-full" :value="old('publication_year', $book->publication_year)" />
                                <x-input-error class="mt-2" :messages="$errors->get('publication_year')" />
                            </div>
                            <div>
                                <x-input-label for="isbn" value="ISBN" />
                                <x-text-input id="isbn" name="isbn" type="text" class="mt-1 block w-full" :value="old('isbn', $book->isbn)" />
                                <x-input-error class="mt-2" :messages="$errors->get('isbn')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="stock" value="Jumlah Stok" />
                            <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full" :value="old('stock', $book->stock)" min="0" required />
                            <x-input-error class="mt-2" :messages="$errors->get('stock')" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Deskripsi" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $book->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label value="Sampul Saat Ini" />
                            <div class="mt-2 flex items-center gap-4">
                                @if ($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-24 w-16 object-cover rounded">
                                @else
                                    <div class="h-24 w-16 bg-gray-100 flex items-center justify-center text-gray-400 text-xs">Tidak ada</div>
                                @endif
                                <div>
                                    <input id="cover_image" name="cover_image" type="file" class="block w-full" accept="image/*" />
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti.</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('books.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Batal</a>
                            <x-primary-button>Perbarui</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
