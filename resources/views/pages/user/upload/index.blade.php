@extends('layouts.layout')
@section('title', 'Upload Dokumen')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-gray-50 overflow-x-auto">
        <div class="max-w-7xl mx-auto space-y-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg relative fade-in"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg relative fade-in"
                    role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in">
                <div class="p-6 border-b border-gray-200">
                    <h2 id="upload-section-title" class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg> Upload Dokumen Baru
                    </h2>
                </div>

                <form action="{{ route('user.upload.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg space-y-1">
                            <strong class="font-medium">Validasi Gagal:</strong>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama-dokumen" class="block text-sm font-medium text-gray-700 mb-2">Nama Dokumen</label>
                            <input type="text" id="nama-dokumen" name="nama-dokumen" value="{{ old('nama-dokumen') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nama-dokumen') border-red-500 @enderror"
                                placeholder="Masukkan nama dokumen">
                        </div>
                        <div>
                            <label for="tanggal-upload" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Upload</label>
                            <input type="date" id="tanggal-upload" name="tanggal-upload" value="{{ old('tanggal-upload') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('tanggal-upload') border-red-500 @enderror">
                        </div>
                    </div>
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('deskripsi') border-red-500 @enderror"
                            placeholder="Masukkan deskripsi dokumen">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Dokumen</label>
                        <div id="drop-zone"
                            class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors cursor-pointer @error('file-input') border-red-500 @enderror">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                </path>
                            </svg>
                            <p class="text-gray-600 mb-2">Drag &amp; drop file di sini atau <span
                                    class="text-blue-600 font-medium">klik untuk browse</span></p>
                            <p class="text-sm text-gray-500">PDF, DOC, DOCX (Max. 10MB)</p>
                            <input type="file" id="file-input" name="file-input" class="hidden"
                                accept=".pdf,.doc,.docx" required>
                        </div>
                        <div id="file-preview" class="mt-4 hidden">
                            <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <div class="flex-1">
                                    <p id="file-name" class="text-sm font-medium text-gray-900"></p>
                                    <p id="file-size" class="text-xs text-gray-600"></p>
                                </div>
                                <button type="button" id="remove-file" class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" id="submit-btn"
                            class="px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover-glow hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="submit-button-text">Kirim Dokumen</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadForm = document.querySelector('form'); 
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('file-input');
            const filePreview = document.getElementById('file-preview');
            const fileNameEl = document.getElementById('file-name');
            const fileSizeEl = document.getElementById('file-size');
            const removeFileBtn = document.getElementById('remove-file');
            const submitBtn = document.getElementById('submit-btn');
            
            const tanggalInput = document.getElementById('tanggal-upload');

            const today = new Date();
            const todayString = today.toISOString().split('T')[0];

            if (!tanggalInput.value) {
                tanggalInput.value = todayString;
            }
            
            tanggalInput.max = todayString; 
            
            let selectedFile = null;

        
            function formatBytes(bytes, decimals = 2) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }

            function showFilePreview(file) {
                selectedFile = file;
                fileNameEl.textContent = file.name;
                fileSizeEl.textContent = formatBytes(file.size);
                filePreview.classList.remove('hidden');
                dropZone.classList.add('hidden');
            }

            function resetFile() {
                selectedFile = null;
                fileInput.value = ''; 
                filePreview.classList.add('hidden');
                dropZone.classList.remove('hidden');
            }

            dropZone.addEventListener('click', () => {
                fileInput.click();
            });

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-blue-400');
            });
            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-400');
            });
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-400');
                const file = e.dataTransfer.files[0];
                if (file) {
                    fileInput.files = e.dataTransfer.files; 
                    showFilePreview(file);
                }
            });

            fileInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    showFilePreview(file);
                }
            });

            removeFileBtn.addEventListener('click', resetFile);

            uploadForm.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.querySelector('span').textContent = 'Mengupload...';
            });
        });
    </script>

    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hover-glow:hover {
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
        }
    </style>
@endsection