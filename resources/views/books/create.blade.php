<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-6 px-6 rounded-xl shadow-lg">
            <h2 class="font-bold text-2xl flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Buku Baru
            </h2>
            <p class="text-indigo-100 text-sm mt-1">Lengkapi informasi buku yang akan ditambahkan</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-8 text-gray-900">
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
                            <x-input-label for="cover_image" value="Sampul Buku (JPG, PNG, max 2MB)" />
                            <div class="mt-2 flex items-start gap-4">
                                <div class="flex-1">
                                    <input id="cover_image" name="cover_image" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" onchange="previewImage(event)" />
                                    <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG, GIF (Maksimal 2MB)</p>
                                </div>
                                <div id="imagePreview" class="hidden">
                                    <img id="preview" src="" alt="Preview" class="h-32 w-24 object-cover rounded-lg shadow-md border-2 border-gray-200">
                                </div>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('books.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 font-semibold">Batal</a>
                            <x-primary-button>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Buku
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
            }
        }
    </script>
    @endpush
</x-app-layout>
