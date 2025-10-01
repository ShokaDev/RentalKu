<?php
include("../../config/koneksi.php");
session_start();

// Pastikan hanya agen
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'agen') {
    header("Location: ../login.php?error=unauthorized");
    exit;
}

// Ambil data agen dari session
$id_pemilik = $_SESSION['user_id'];   // asumsi saat login id_pemilik disimpan di session user_id
$nama_pemilik = $_SESSION['username']; // asumsi nama agen disimpan di session username
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kendaraan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body class="bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 min-h-screen py-8 px-4">

    <!-- Container -->
    <div class="max-w-4xl mx-auto">
        
        <!-- Header Section -->
        <div id="header" class="text-center mb-8 opacity-0">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Tambah Kendaraan Baru</h1>
            <p class="text-gray-600">Lengkapi informasi kendaraan yang akan ditambahkan</p>
        </div>

        <!-- Main Card -->
        <div id="formCard" class="bg-white shadow-xl rounded-3xl overflow-hidden opacity-0">
            
            <!-- Form Container -->
            <div class="p-8 md:p-10">
                <form action="../../php/kendaraan/simpan_kendaraan.php" method="POST" enctype="multipart/form-data">
                    
                    <!-- Agent Info Badge -->
                    <div class="mb-8 p-5 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl border border-emerald-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Agen</label>
                                 <input type="text" value="<?= htmlspecialchars($nama_pemilik) ?>" 
                       class="w-full border border-gray-300 bg-gray-100 px-3 py-2 rounded-lg" disabled>
                                <input type="hidden" name="id_pemilik" value="">
                            </div>
                        </div>
                    </div>

                    <!-- Grid Layout for Form Fields -->
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        
                        <!-- No Plat -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                    </svg>
                                    No Plat <span class="text-red-500">*</span>
                                </span>
                            </label>
                            <input type="text" name="no_plat" placeholder="B 1234 XYZ" 
                                   class="w-full border-2 border-gray-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 px-4 py-3 rounded-xl transition-all duration-200 outline-none group-hover:border-gray-300" required>
                        </div>

                        <!-- Merk -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Merk
                                </span>
                            </label>
                            <input type="text" name="merk" placeholder="Toyota, Honda, Suzuki..." 
                                   class="w-full border-2 border-gray-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 px-4 py-3 rounded-xl transition-all duration-200 outline-none group-hover:border-gray-300">
                        </div>

                        <!-- Tipe -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                                    </svg>
                                    Tipe
                                </span>
                            </label>
                            <input type="text" name="tipe" placeholder="Avanza, Xenia, Ertiga..." 
                                   class="w-full border-2 border-gray-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 px-4 py-3 rounded-xl transition-all duration-200 outline-none group-hover:border-gray-300">
                        </div>

                        <!-- Tahun -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Tahun
                                </span>
                            </label>
                            <input type="number" name="tahun" min="1990" max="2099" placeholder="2023" 
                                   class="w-full border-2 border-gray-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 px-4 py-3 rounded-xl transition-all duration-200 outline-none group-hover:border-gray-300">
                        </div>

                        <!-- Harga Sewa -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                    </svg>
                                    Harga Sewa <span class="text-red-500">*</span>
                                </span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="number" name="harga_sewa" step="0.01" placeholder="350000" 
                                       class="w-full border-2 border-gray-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 pl-12 pr-4 py-3 rounded-xl transition-all duration-200 outline-none group-hover:border-gray-300" required>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Status
                                </span>
                            </label>
                            <select name="status" 
                                    class="w-full border-2 border-gray-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 px-4 py-3 rounded-xl transition-all duration-200 outline-none group-hover:border-gray-300 bg-white cursor-pointer">
                                <option value="tersedia">✓ Tersedia</option>
                                <option value="disewa">⊗ Disewa</option>
                                <option value="perbaikan">🔧 Perbaikan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                </svg>
                                Foto Kendaraan
                            </span>
                        </label>
                        
                        <!-- Upload Area -->
                        <div class="relative border-3 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-emerald-400 transition-all duration-200 bg-gray-50 hover:bg-emerald-50 cursor-pointer" id="uploadArea">
                            <input type="file" name="gambar" id="gambarInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <div id="uploadPlaceholder">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-gray-600 font-medium mb-1">Klik atau drag foto ke sini</p>
                                <p class="text-sm text-gray-500">Format: JPG, PNG (Maks. 2MB)</p>
                            </div>
                            
                            <!-- Preview Container -->
                            <div id="previewContainer" class="hidden">
                                <img id="previewImage" src="" alt="Preview" class="max-w-full h-64 object-contain mx-auto rounded-lg shadow-md">
                                <button type="button" id="removeImage" class="mt-4 text-red-500 hover:text-red-700 font-medium flex items-center gap-2 mx-auto">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Hapus Foto
                                </button>
                            </div>
                        </div>
                        
                        <p id="fileError" class="text-red-500 text-sm mt-2 hidden flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span id="errorText"></span>
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1 active:translate-y-0 flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Simpan Kendaraan
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Footer -->
        <div id="footer" class="text-center mt-8 opacity-0">
            <p class="text-gray-600 text-sm">Pastikan semua data yang dimasukkan sudah benar sebelum menyimpan</p>
        </div>
    </div>

    <script>
        // GSAP Animations
        gsap.to("#header", {duration: 0.8, opacity: 1, y: 0, ease: "power3.out"});
        gsap.to("#formCard", {duration: 1, opacity: 1, y: 0, ease: "power3.out", delay: 0.2});
        gsap.to("#footer", {duration: 0.8, opacity: 1, ease: "power3.out", delay: 0.4});

        // Image Preview & Validation
        const gambarInput = document.getElementById("gambarInput");
        const previewImage = document.getElementById("previewImage");
        const previewContainer = document.getElementById("previewContainer");
        const uploadPlaceholder = document.getElementById("uploadPlaceholder");
        const fileError = document.getElementById("fileError");
        const errorText = document.getElementById("errorText");
        const removeImageBtn = document.getElementById("removeImage");
        const uploadArea = document.getElementById("uploadArea");

        gambarInput.addEventListener("change", function(event) {
            const file = event.target.files[0];

            if (file) {
                const fileSize = file.size / 1024 / 1024;
                const fileType = file.type;

                if (!fileType.match("image.*")) {
                    errorText.textContent = "File harus berupa gambar (JPG/PNG)";
                    fileError.classList.remove("hidden");
                    resetPreview();
                    return;
                }

                if (fileSize > 2) {
                    errorText.textContent = "Ukuran file maksimal 2MB";
                    fileError.classList.remove("hidden");
                    resetPreview();
                    return;
                }

                fileError.classList.add("hidden");
                previewImage.src = URL.createObjectURL(file);
                uploadPlaceholder.classList.add("hidden");
                previewContainer.classList.remove("hidden");
                uploadArea.classList.add("border-emerald-500", "bg-emerald-50");
            }
        });

        removeImageBtn.addEventListener("click", function() {
            resetPreview();
        });

        function resetPreview() {
            gambarInput.value = "";
            previewContainer.classList.add("hidden");
            uploadPlaceholder.classList.remove("hidden");
            uploadArea.classList.remove("border-emerald-500", "bg-emerald-50");
        }

        // Drag and Drop
        uploadArea.addEventListener("dragover", function(e) {
            e.preventDefault();
            uploadArea.classList.add("border-emerald-500", "bg-emerald-50");
        });

        uploadArea.addEventListener("dragleave", function() {
            uploadArea.classList.remove("border-emerald-500", "bg-emerald-50");
        });

        uploadArea.addEventListener("drop", function(e) {
            e.preventDefault();
            uploadArea.classList.remove("border-emerald-500", "bg-emerald-50");
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                gambarInput.files = files;
                gambarInput.dispatchEvent(new Event("change"));
            }
        });
    </script>
</body>
</html>