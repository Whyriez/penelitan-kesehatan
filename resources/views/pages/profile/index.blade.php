@extends('layouts.layout')
@section('title', 'Profil Saya')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Banner Sukses Profil --}}
            @if (session('success_profile'))
                <div class="info-banner rounded-lg p-4 fade-in bg-green-100 border border-green-300 text-green-800">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm">
                            <span class="font-medium">Sukses!</span> {{ session('success_profile') }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- Banner Error Validasi Profil (Bukan Password) --}}
            @if ($errors->any() && !$errors->has('current_password') && !$errors->has('password'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg relative fade-in"
                     role="alert">
                    <strong class="font-bold">Oops! Ada kesalahan:</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            @if (!in_array($error, $errors->get('current_password') ?: []) && !in_array($error, $errors->get('password') ?: []))
                                <li>{{ $error }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- Form Informasi Profil --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 fade-in">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-medium text-gray-900">
                        Informasi Profil
                    </h2>
                </div>
                <div class="p-6">
                    <form id="profile-form" action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama
                                    Lengkap</label>
                                <input type="text" id="name" name="name"
                                       value="{{ old('name', $user->name) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm @error('name') border-red-500 @enderror"/>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email" name="email"
                                       value="{{ old('email', $user->email) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm @error('email') border-red-500 @enderror"/>
                            </div>

                            <div>
                                <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-2">Nomor
                                    Telepon</label>
                                <input type="text" id="nomor_telepon" name="nomor_telepon"
                                       value="{{ old('nomor_telepon', $user->nomor_telepon) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm @error('nomor_telepon') border-red-500 @enderror"/>
                            </div>

                            <div>
                                <label for="institusi" class="block text-sm font-medium text-gray-700 mb-2">Institusi /
                                    Afiliasi</label>
                                <input type="text" id="institusi" name="institusi"
                                       value="{{ old('institusi', $user->institusi) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm @error('institusi') border-red-500 @enderror"/>
                            </div>

                            <div>
                                <label for="nomor_identitas" class="block text-sm font-medium text-gray-700 mb-2">Nomor
                                    Identitas (Opsional)</label>
                                <input type="text" id="nomor_identitas" name="nomor_identitas"
                                       value="{{ old('nomor_identitas', $user->nomor_identitas) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm @error('nomor_identitas') border-red-500 @enderror"/>
                            </div>

                            <div>
                                <label for="gelar_jabatan" class="block text-sm font-medium text-gray-700 mb-2">Gelar /
                                    Jabatan (Opsional)</label>
                                <input type="text" id="gelar_jabatan" name="gelar_jabatan"
                                       value="{{ old('gelar_jabatan', $user->gelar_jabatan) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm @error('gelar_jabatan') border-red-500 @enderror"/>
                            </div>

                            <div class="md:col-span-2">
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Departemen
                                    (Opsional)</label>
                                <input type="text" id="department" name="department"
                                       value="{{ old('department', $user->department) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm @error('department') border-red-500 @enderror"/>
                            </div>

                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap
                                    (Opsional)</label>
                                <textarea id="alamat" name="alamat" rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm @error('alamat') border-red-500 @enderror">{{ old('alamat', $user->alamat) }}</textarea>
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                <input type="text" id="role" name="role" value="{{ ucfirst($user->role) }}" readonly
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm text-gray-600"/>
                            </div>

                            <div>
                                <label for="join-date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                    Bergabung</label>
                                <input type="text" id="join-date" name="join-date"
                                       value="{{ $user->created_at?->format('d F Y') ?? 'N/A' }}" readonly
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm text-gray-600"/>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ url()->previous() }}"
                               class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                                Kembali
                            </a>
                            <button type="submit" id="save-btn"
                                    class="px-4 py-2 bg-sky-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 fade-in">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-medium text-gray-900">
                        Keamanan Akun
                    </h2>
                </div>
                <div class="p-6">
                    @if (session('success_password'))
                        <div
                            class="info-banner rounded-lg p-4 mb-4 fade-in bg-green-100 border border-green-300 text-green-800">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor"
                                     viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm"><span class="font-medium">Sukses!</span>
                                    {{ session('success_password') }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($errors->has('current_password') || $errors->has('password'))
                        <div
                            class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg relative fade-in mb-4"
                            role="alert">
                            <strong class="font-bold">Oops! Gagal mengubah password:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Kata Sandi</h3>
                                <p class="text-sm text-gray-500">Ganti kata sandi Anda secara berkala</p>
                            </div>
                            <button type="button" id="change-password-btn" data-modal-toggle="password-modal"
                                    class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                                Ubah Kata Sandi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="modal-container" class="hidden">
        <div id="password-modal"
             class="dokumen-modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-1/2 lg:w-1/3">
                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="flex justify-between items-center p-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Ubah Kata Sandi</h3>
                        <button type="button" data-modal-close="password-modal"
                                class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Kata
                                Sandi Saat Ini</label>
                            <input type="password" id="current_password" name="current_password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi
                                Baru</label>
                            <input type="password" id="password" name="password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>
                        <div>
                            <label for="password_confirmation"
                                   class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi
                                Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t text-right space-x-3">
                        <button type="button" data-modal-close="password-modal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-sky-600 text-white font-medium rounded-md hover:bg-sky-700">
                            Simpan Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .info-banner.bg-green-100 {
            background-color: #D1FAE5;
            border-color: #6EE7B7;
            color: #065F46;
        }

        .info-banner.bg-green-100 svg {
            color: #059669;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const modalContainer = document.getElementById('modal-container');
            if (modalContainer) {
                const modals = modalContainer.querySelectorAll('.dokumen-modal');
                modals.forEach(modal => {
                    document.body.appendChild(modal);
                });
                modalContainer.remove();
            }

            const modalToggleButtons = document.querySelectorAll('[data-modal-toggle]');
            modalToggleButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetModalId = button.getAttribute('data-modal-toggle');
                    const modal = document.getElementById(targetModalId);
                    if (modal) {
                        modal.classList.remove('hidden');
                    }
                });
            });

            const modalCloseButtons = document.querySelectorAll('[data-modal-close]');
            modalCloseButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetModalId = button.getAttribute('data-modal-close');
                    const modal = document.getElementById(targetModalId);
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                });
            });

            @if ($errors->has('current_password') || $errors->has('password'))
            document.getElementById('password-modal').classList.remove('hidden');
            @endif
        });
    </script>
@endsection
