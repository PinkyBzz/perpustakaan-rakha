<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Pengguna
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" value="Nama" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <x-input-label for="role" value="Role" />
                            <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                                <option value="pegawai" @selected(old('role', $user->role) === 'pegawai')>Pegawai</option>
                                <option value="user" @selected(old('role', $user->role) === 'user')>User</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('role')" />
                        </div>

                        <div>
                            <x-input-label for="password" value="Password (opsional)" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
                        </div>

                        <div>
                            <x-input-label value="Foto Profil Saat Ini" />
                            <div class="mt-2 flex items-center gap-4">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="h-14 w-14 rounded-full object-cover">
                                <div>
                                    <input id="profile_photo" name="profile_photo" type="file" class="block w-full" accept="image/*" />
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti.</p>
                                </div>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Batal</a>
                            <x-primary-button>Perbarui</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
