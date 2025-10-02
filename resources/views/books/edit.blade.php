<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-6 px-6 rounded-xl shadow-lg">
            <h2 class="font-bold text-2xl flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Buku
            </h2>
            <p class="text-purple-100 text-sm mt-1">Perbarui informasi buku: {{ $book->title }}</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-8 text-gray-900">
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
                            <x-input-label for="cover_image" value="Sampul Buku (JPG, PNG, max 2MB)" />
                            <div class="mt-2 flex items-start gap-4">
                                <div id="currentImagePreview" class="flex-shrink-0">
                                    @if ($book->cover_image)
                                        <img id="currentImage" src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-32 w-24 object-cover rounded-lg shadow-md border-2 border-gray-200">
                                    @else
                                        <div id="currentImage" class="h-32 w-24 bg-gradient-to-br from-gray-100 to-gray-200 flex flex-col items-center justify-center text-gray-400 text-xs rounded-lg shadow-md border-2 border-gray-200">
                                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Tidak ada</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input id="cover_image" name="cover_image" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" onchange="previewEditImage(event)" />
                                    <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG, GIF (Maksimal 2MB)</p>
                                    <p class="text-xs text-gray-600 mt-1 font-semibold">Kosongkan jika tidak ingin mengganti sampul.</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('books.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 font-semibold">Batal</a>
                            <x-primary-button>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Perbarui Buku
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewEditImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('currentImage');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('bg-gradient-to-br', 'from-gray-100', 'to-gray-200', 'flex', 'flex-col', 'items-center', 'justify-center');
                    preview.classList.add('object-cover');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
    @endpush
</x-app-layout>
