<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pengguna
            </h2>
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                Edit User
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <div class="flex items-center gap-6">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="h-24 w-24 rounded-full object-cover">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h3>
                            <p class="text-gray-500">{{ $user->email }}</p>
                            <p class="text-sm text-gray-400 capitalize">Role: {{ $user->role }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-xs text-gray-500 uppercase">Bergabung</p>
                            <p class="text-sm text-gray-800">{{ $user->created_at->translatedFormat('d F Y H:i') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-xs text-gray-500 uppercase">Terakhir Diperbarui</p>
                            <p class="text-sm text-gray-800">{{ $user->updated_at->translatedFormat('d F Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">Hapus</button>
                        </form>

                        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
