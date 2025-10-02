<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Buku</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="title" value="Judul" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="author" value="Pengarang" />
                            <x-text-input id="author" name="author" type="text" class="mt-1 block w-full" :value="old('author')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('author')" />
                        </div>

                        <div>
                            <x-input-label for="publisher" value="Penerbit" />
                            <x-text-input id="publisher" name="publisher" type="text" class="mt-1 block w-full" :value="old('publisher')" />
                            <x-input-error class="mt-2" :messages="$errors->get('publisher')" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="publication_year" value="Tahun Terbit" />
                                <x-text-input id="publication_year" name="publication_year" type="number" class="mt-1 block w-full" :value="old('publication_year')" />
                                <x-input-error class="mt-2" :messages="$errors->get('publication_year')" />
                            </div>
                            <div>
                                <x-input-label for="isbn" value="ISBN" />
                                <x-text-input id="isbn" name="isbn" type="text" class="mt-1 block w-full" :value="old('isbn')" />
                                <x-input-error class="mt-2" :messages="$errors->get('isbn')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="stock" value="Jumlah Stok" />
                            <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full" :value="old('stock', 1)" min="0" required />
                            <x-input-error class="mt-2" :messages="$errors->get('stock')" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Deskripsi" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="cover_image" value="Sampul Buku" />
                            <input id="cover_image" name="cover_image" type="file" class="mt-1 block w-full" accept="image/*" />
                            <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('books.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Batal</a>
                            <x-primary-button>Simpan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
