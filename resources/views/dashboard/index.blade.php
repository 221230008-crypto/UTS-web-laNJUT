@extends('layouts.app')

@section('content')
<div x-data="fireApp()" x-init="initData()" class="min-h-screen py-6 px-4 sm:px-6 lg:px-8" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    
    <!-- Header -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="bg-gradient-to-r from-red-700 to-orange-600 rounded-2xl shadow-xl p-6 text-white">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 p-4 rounded-full backdrop-blur-sm">
                        <i class="fas fa-fire-extinguisher text-4xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Siaga<span class="text-yellow-300">Api</span></h1>
                        <p class="text-red-100 mt-1">Sistem Informasi Relawan Pemadam Kebakaran dengan Lokasi Real</p>
                    </div>
                </div>
                
                <div class="flex gap-3 items-center">
                    <div x-show="!isLoggedIn" class="flex gap-2">
                        <input type="password" x-model="loginPassword" placeholder="Password Admin" 
                               class="px-4 py-2 rounded-lg bg-white/20 text-white placeholder-white/70 border border-white/30 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        <button @click="adminLogin()" class="bg-yellow-500 hover:bg-yellow-600 px-5 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-lock"></i> Login Admin
                        </button>
                    </div>
                    <div x-show="isLoggedIn" class="flex items-center gap-3">
                        <div class="bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm">
                            <i class="fas fa-user-shield mr-2"></i>
                            <span class="font-bold">Admin</span>
                        </div>
                        <button @click="adminLogout()" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu User (Pelapor) -->
    <div class="max-w-7xl mx-auto mb-6">
        <div class="bg-gray-800/50 rounded-xl p-4 backdrop-blur-sm border border-gray-700">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 p-2 rounded-full">
                        <i class="fas fa-map-marked-alt text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-bold">Mode Pelapor Masyarakat</h2>
                        <p class="text-gray-400 text-sm">Tap pada peta untuk menentukan lokasi kebakaran secara real</p>
                    </div>
                </div>
                <button @click="openUserReportModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> Laporkan Kebakaran
                </button>
            </div>
        </div>
    </div>

    <!-- Admin Dashboard -->
    <div x-show="isLoggedIn" class="max-w-7xl mx-auto mb-8" x-cloak>
        <!-- Tab Navigation -->
        <div class="flex flex-wrap gap-2 border-b border-gray-700">
            <button @click="activeTab = 'incidents'" :class="activeTab === 'incidents' ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700'" class="px-6 py-3 rounded-t-lg font-semibold transition flex items-center gap-2">
                <i class="fas fa-fire"></i> Semua Laporan 
                <span x-show="unreadCount > 0" class="ml-2 bg-yellow-500 text-black text-xs rounded-full px-2 py-0.5" x-text="unreadCount"></span>
            </button>
            <button @click="activeTab = 'volunteers'" :class="activeTab === 'volunteers' ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700'" class="px-6 py-3 rounded-t-lg font-semibold transition flex items-center gap-2">
                <i class="fas fa-users"></i> Manajemen Relawan
            </button>
            <button @click="activeTab = 'equipment'" :class="activeTab === 'equipment' ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700'" class="px-6 py-3 rounded-t-lg font-semibold transition flex items-center gap-2">
                <i class="fas fa-boxes"></i> Inventaris Alat
            </button>
        </div>

        <!-- Tombol Tambah Admin -->
        <div class="mt-6 flex justify-end">
            <button x-show="activeTab === 'incidents'" @click="openIncidentModal()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Tambah Laporan
            </button>
            <button x-show="activeTab === 'volunteers'" @click="openVolunteerModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition flex items-center gap-2">
                <i class="fas fa-user-plus"></i> Tambah Relawan
            </button>
            <button x-show="activeTab === 'equipment'" @click="openEquipmentModal()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Tambah Alat
            </button>
        </div>

        <!-- Daftar Laporan -->
        <div x-show="activeTab === 'incidents'" class="mt-6">
            <div x-show="allIncidents.length === 0" class="text-center py-16 bg-gray-800/50 rounded-2xl">
                <i class="fas fa-fire-extinguisher text-6xl text-gray-600 mb-4"></i>
                <p class="text-gray-400">Belum ada laporan kejadian kebakaran.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="incident in allIncidents" :key="incident.id">
                    <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-700 transition hover:scale-105">
                        <div class="bg-gradient-to-r from-red-600 to-orange-500 p-3">
                            <div class="flex justify-between items-center text-white">
                                <span class="text-xs font-semibold" x-text="incident.formatted_date || incident.formattedDate"></span>
                                <span :class="incident.status === 'Selesai' ? 'bg-green-500' : (incident.status === 'Dalam Penanganan' ? 'bg-yellow-500' : 'bg-red-500')" class="px-2 py-1 rounded text-xs font-bold">
                                    <span x-text="incident.status"></span>
                                </span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="text-xl font-bold text-white mb-2" x-text="incident.location"></h3>
                            <p class="text-gray-300 text-sm mb-3"><i class="fas fa-map-marker-alt text-red-400 mr-1"></i> <span x-text="incident.address"></span></p>
                            <p x-show="incident.lat && incident.lng" class="text-gray-500 text-xs mb-2">
                                <i class="fas fa-globe"></i> Koordinat: <span x-text="parseFloat(incident.lat).toFixed(6)"></span>, <span x-text="parseFloat(incident.lng).toFixed(6)"></span>
                            </p>
                            <p class="text-gray-400 text-sm mb-4" x-text="incident.description"></p>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500"><i class="fas fa-user"></i> Pelapor: <span class="text-gray-300" x-text="incident.reporter"></span></span>
                                <span class="text-orange-400"><i class="fas fa-fire"></i> Skala: <span x-text="incident.scale"></span></span>
                            </div>
                            <button x-show="incident.lat && incident.lng" @click="openInMaps(incident.lat, incident.lng)" class="mt-3 bg-blue-600/50 hover:bg-blue-600 text-white text-sm py-2 px-3 rounded-lg w-full transition flex items-center justify-center gap-2">
                                <i class="fas fa-directions"></i> Navigasi ke Lokasi
                            </button>
                        </div>
                        <div class="p-4 bg-gray-900 flex justify-end gap-2">
                            <button @click="editIncident(incident)" class="text-yellow-500 hover:text-yellow-400 transition px-3 py-1 rounded-lg text-sm font-medium">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button @click="confirmDelete('incident', incident.id)" class="text-red-500 hover:text-red-400 transition px-3 py-1 rounded-lg text-sm font-medium">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Volunteer section -->
        <div x-show="activeTab === 'volunteers'" class="mt-6">
            <div x-show="volunteers.length === 0" class="text-center py-16 bg-gray-800/50 rounded-2xl">
                <p class="text-gray-400">Belum ada data relawan.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <template x-for="volunteer in volunteers" :key="volunteer.id">
                    <div class="bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-700 transition hover:scale-105">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-blue-600 w-12 h-12 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-bold text-lg" x-text="volunteer.name"></h3>
                                <p class="text-gray-400 text-xs" x-text="volunteer.role"></p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p class="text-gray-300"><i class="fas fa-phone text-green-400 w-5"></i> <span x-text="volunteer.phone"></span></p>
                            <p class="text-gray-300"><i class="fas fa-tint text-red-400 w-5"></i> Gol. Darah: <span x-text="volunteer.blood_type || volunteer.bloodType"></span></p>
                            <p class="text-gray-300"><i class="fas fa-calendar w-5"></i> Bergabung: <span x-text="volunteer.join_date || volunteer.joinDate"></span></p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-700 flex justify-end gap-2">
                            <button @click="editVolunteer(volunteer)" class="text-yellow-500 hover:text-yellow-400 text-sm">Edit</button>
                            <button @click="confirmDelete('volunteer', volunteer.id)" class="text-red-500 hover:text-red-400 text-sm">Hapus</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Equipment section -->
        <div x-show="activeTab === 'equipment'" class="mt-6">
            <div x-show="equipment.length === 0" class="text-center py-16 bg-gray-800/50 rounded-2xl">
                <p class="text-gray-400">Belum ada data inventaris alat.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <template x-for="item in equipment" :key="item.id">
                    <div class="bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-700 transition hover:scale-105">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div :class="item.category === 'APAR' ? 'bg-red-600' : (item.category === 'Kendaraan' ? 'bg-blue-600' : 'bg-green-600')" class="w-10 h-10 rounded-lg flex items-center justify-center">
                                    <i :class="item.category === 'APAR' ? 'fas fa-fire-extinguisher' : (item.category === 'Kendaraan' ? 'fas fa-truck' : 'fas fa-helmet-safety')" class="text-white"></i>
                                </div>
                                <h3 class="text-white font-bold" x-text="item.name"></h3>
                            </div>
                            <span :class="item.status === 'Baik' ? 'bg-green-500' : (item.status === 'Perbaikan' ? 'bg-yellow-500' : 'bg-red-500')" class="px-2 py-1 rounded text-xs font-bold text-white">
                                <span x-text="item.status"></span>
                            </span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p class="text-gray-300"><i class="fas fa-tag w-5"></i> Kategori: <span x-text="item.category"></span></p>
                            <p class="text-gray-300"><i class="fas fa-hashtag w-5"></i> Jumlah: <span x-text="item.quantity" class="font-bold text-white"></span> unit</p>
                            <p class="text-gray-300"><i class="fas fa-calendar w-5"></i> Terakhir servis: <span x-text="item.last_service || item.lastService"></span></p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-700 flex justify-end gap-2">
                            <button @click="editEquipment(item)" class="text-yellow-500 hover:text-yellow-400 text-sm">Edit</button>
                            <button @click="confirmDelete('equipment', item.id)" class="text-red-500 hover:text-red-400 text-sm">Hapus</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- MODAL LAPORAN USER (PELAPOR) -->
    <div x-show="isUserReportModalOpen" x-cloak class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto" style="display: none;" @click.away="closeUserReportModal()">
        <div class="bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full mx-4 border border-gray-700 my-8" @click.stop>
            <div class="flex justify-between items-center p-6 border-b border-gray-700">
                <h2 class="text-2xl font-bold text-white"><i class="fas fa-map-marked-alt text-red-500 mr-2"></i>Laporkan Kejadian Kebakaran</h2>
                <button @click="closeUserReportModal()" class="text-gray-400 hover:text-white text-3xl">&times;</button>
            </div>
            
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-gray-300 font-semibold mb-2">
                        <i class="fas fa-hand-pointer text-yellow-400"></i> Tap pada peta untuk menentukan lokasi kebakaran
                    </label>
                    <div id="userMap" style="height: 400px; width: 100%; border-radius: 12px;"></div>
                    <p class="text-gray-500 text-xs mt-2">
                        <i class="fas fa-info-circle"></i> Klik/tap pada peta untuk menandai lokasi kebakaran
                    </p>
                </div>
                
                <form @submit.prevent="submitUserReport()" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-300 font-semibold mb-2">Latitude</label>
                            <input type="text" x-model="userReportForm.lat" readonly class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-gray-300 font-semibold mb-2">Longitude</label>
                            <input type="text" x-model="userReportForm.lng" readonly class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-gray-400 cursor-not-allowed">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Alamat Lengkap (Otomatis)</label>
                        <input type="text" x-model="userReportForm.address" readonly class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-gray-300">
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Nama Lokasi *</label>
                        <input type="text" x-model="userReportForm.location" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white" placeholder="Contoh: Pasar Minggu, Perumahan Asri">
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Deskripsi Kejadian *</label>
                        <textarea x-model="userReportForm.description" rows="3" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white" placeholder="Ceritakan detail kejadian kebakaran..."></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-300 font-semibold mb-2">Nama Pelapor *</label>
                            <input type="text" x-model="userReportForm.reporter" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white">
                        </div>
                        <div>
                            <label class="block text-gray-300 font-semibold mb-2">Skala Kebakaran</label>
                            <select x-model="userReportForm.scale" class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white">
                                <option>Kecil</option>
                                <option>Sedang</option>
                                <option>Besar</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-3 text-sm text-yellow-300">
                        <i class="fas fa-info-circle"></i> Lokasi akan langsung terlihat oleh tim pemadam kebakaran
                    </div>
                    
                    <div class="flex gap-3 pt-3">
                        <button type="button" @click="closeUserReportModal()" class="flex-1 px-4 py-3 border border-gray-600 rounded-xl text-gray-300 font-medium hover:bg-gray-700 transition">Batal</button>
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-lg">
                            <i class="fas fa-paper-plane"></i> Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL ADMIN INCIDENT -->
    <div x-show="isModalOpen && modalType === 'incident'" x-cloak class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto" style="display: none;" @click.away="closeModal()">
        <div class="bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full mx-4 border border-gray-700 my-8" @click.stop>
            <div class="flex justify-between items-center p-6 border-b border-gray-700">
                <h2 class="text-2xl font-bold text-white" x-text="modalTitle"></h2>
                <button @click="closeModal()" class="text-gray-400 hover:text-white text-3xl">&times;</button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-gray-300 font-semibold mb-2">Pilih Lokasi di Peta</label>
                    <div id="adminMap" style="height: 400px; width: 100%; border-radius: 12px;"></div>
                </div>
                <form @submit.prevent="submitForm()" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-gray-300 font-semibold mb-2">Latitude</label><input type="text" x-model="formData.lat" readonly class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-gray-400"></div>
                        <div><label class="block text-gray-300 font-semibold mb-2">Longitude</label><input type="text" x-model="formData.lng" readonly class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-gray-400"></div>
                    </div>
                    <div><label class="block text-gray-300 font-semibold mb-2">Alamat</label><input type="text" x-model="formData.address" class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-gray-300"></div>
                    <div><label class="block text-gray-300 font-semibold mb-2">Nama Lokasi</label><input type="text" x-model="formData.location" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"></div>
                    <div><label class="block text-gray-300 font-semibold mb-2">Deskripsi</label><textarea x-model="formData.description" rows="3" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"></textarea></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-gray-300 font-semibold mb-2">Pelapor</label><input type="text" x-model="formData.reporter" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"></div>
                        <div><label class="block text-gray-300 font-semibold mb-2">Skala</label><select x-model="formData.scale" class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"><option>Kecil</option><option>Sedang</option><option>Besar</option></select></div>
                    </div>
                    <div><label class="block text-gray-300 font-semibold mb-2">Status</label><select x-model="formData.status" class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"><option>Laporan Baru</option><option>Dalam Penanganan</option><option>Selesai</option></select></div>
                    <div class="flex gap-3">
                        <button type="button" @click="closeModal()" class="flex-1 px-4 py-3 border border-gray-600 rounded-xl text-gray-300 hover:bg-gray-700">Batal</button>
                        <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl" x-text="modalButtonText"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL VOLUNTEER -->
    <div x-show="isModalOpen && modalType === 'volunteer'" x-cloak class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4" style="display: none;" @click.away="closeModal()">
        <div class="bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full border border-gray-700">
            <div class="flex justify-between items-center p-6 border-b border-gray-700">
                <h2 class="text-2xl font-bold text-white" x-text="modalTitle"></h2>
                <button @click="closeModal()" class="text-gray-400 hover:text-white text-3xl">&times;</button>
            </div>
            <form @submit.prevent="submitForm()" class="p-6 space-y-4">
                <div><label class="block text-gray-300 font-semibold mb-2">Nama Lengkap</label><input type="text" x-model="formData.name" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"></div>
                <div><label class="block text-gray-300 font-semibold mb-2">Peran/Jabatan</label><input type="text" x-model="formData.role" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"></div>
                <div><label class="block text-gray-300 font-semibold mb-2">Nomor Telepon</label><input type="tel" x-model="formData.phone" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"></div>
                <div><label class="block text-gray-300 font-semibold mb-2">Golongan Darah</label><select x-model="formData.blood_type" class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"><option>A</option><option>B</option><option>AB</option><option>O</option></select></div>
                <div><label class="block text-gray-300 font-semibold mb-2">Tanggal Bergabung</label><input type="date" x-model="formData.join_date" class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"></div>
                <div class="flex gap-3">
                    <button type="button" @click="closeModal()" class="flex-1 px-4 py-3 border border-gray-600 rounded-xl text-gray-300 hover:bg-gray-700">Batal</button>
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl" x-text="modalButtonText"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EQUIPMENT -->
    <div x-show="isModalOpen && modalType === 'equipment'" x-cloak class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4" style="display: none;" @click.away="closeModal()">
        <div class="bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full border border-gray-700">
            <div class="flex justify-between items-center p-6 border-b border-gray-700">
                <h2 class="text-2xl font-bold text-white" x-text="modalTitle"></h2>
                <button @click="closeModal()" class="text-gray-400 hover:text-white text-3xl">&times;</button>
            </div>
            <form @submit.prevent="submitForm()" class="p-6 space-y-4">
                <div><label class="block text-gray-300 font-semibold mb-2">Nama Alat</label><input type="text" x-model="formData.name" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"></div>
                <div><label class="block text-gray-300 font-semibold mb-2">Kategori</label><select x-model="formData.category" class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"><option>APAR</option><option>Kendaraan</option><option>APD</option></select></div>
                <div><label class="block text-gray-300 font-semibold mb-2">Jumlah</label><input type="number" x-model="formData.quantity" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"></div>
                <div><label class="block text-gray-300 font-semibold mb-2">Status</label><select x-model="formData.status" class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"><option>Baik</option><option>Perbaikan</option><option>Rusak</option></select></div>
                <div><label class="block text-gray-300 font-semibold mb-2">Tanggal Servis</label><input type="date" x-model="formData.last_service" class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white"></div>
                <div class="flex gap-3">
                    <button type="button" @click="closeModal()" class="flex-1 px-4 py-3 border border-gray-600 rounded-xl text-gray-300 hover:bg-gray-700">Batal</button>
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl" x-text="modalButtonText"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS -->
    <div x-show="isDeleteConfirmOpen" x-cloak class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4" style="display: none;">
        <div class="bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 text-center border border-gray-700">
            <div class="text-red-500 text-5xl mb-4"><i class="fas fa-exclamation-triangle"></i></div>
            <h3 class="text-xl font-bold text-white mb-2">Hapus Data?</h3>
            <p class="text-gray-400 mb-6">Data yang dihapus tidak dapat dikembalikan.</p>
            <div class="flex gap-3">
                <button @click="cancelDelete()" class="flex-1 px-4 py-2 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700">Batal</button>
                <button @click="executeDelete()" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2 rounded-lg">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <!-- Toast Notifikasi -->
    <div x-show="toastMessage" x-transition.duration.300ms class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-6 py-3 rounded-full shadow-lg z-50 text-sm font-medium border border-red-500" style="display: none;" x-text="toastMessage"></div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .leaflet-container { border-radius: 12px; border: 2px solid #ef4444; z-index: 10; }
</style>

@push('scripts')
<script>
function fireApp() {
    return {
        // Auth
        isLoggedIn: false,
        loginPassword: '',
        adminPassword: 'damkar2024',
        
        // UI State
        activeTab: 'incidents',
        isModalOpen: false,
        isUserReportModalOpen: false,
        isDeleteConfirmOpen: false,
        modalType: '',
        modalMode: 'create',
        currentDeleteId: null,
        currentDeleteType: null,
        toastMessage: '',
        
        // Data
        allIncidents: [],
        volunteers: [],
        equipment: [],
        
        // Maps instances
        userMap: null,
        userMarker: null,
        
        // Form Data
        formData: {},
        userReportForm: {
            location: '',
            address: '',
            description: '',
            reporter: '',
            scale: 'Kecil',
            lat: '',
            lng: ''
        },
        
        get unreadCount() {
            return this.allIncidents.filter(i => i.status === 'Laporan Baru').length;
        },
        get modalTitle() { 
            return `${this.modalMode === 'create' ? 'Tambah' : 'Edit'} ${this.modalType === 'incident' ? 'Laporan' : (this.modalType === 'volunteer' ? 'Relawan' : 'Alat')}`; 
        },
        get modalButtonText() { 
            return this.modalMode === 'create' ? 'Simpan' : 'Update'; 
        },
        
        async initData() {
            await this.loadIncidents();
            await this.loadVolunteers();
            await this.loadEquipment();
            
            const savedSession = localStorage.getItem('siaga_admin_session');
            if (savedSession === 'true') {
                this.isLoggedIn = true;
            }
        },
        
        async loadIncidents() {
            try {
                const response = await fetch('/api/incidents');
                const data = await response.json();
                this.allIncidents = data;
            } catch (error) {
                console.log('Error loading incidents:', error);
            }
        },
        
        async loadVolunteers() {
            try {
                const response = await fetch('/api/volunteers');
                const data = await response.json();
                this.volunteers = data;
            } catch (error) {
                console.log('Error loading volunteers:', error);
            }
        },
        
        async loadEquipment() {
            try {
                const response = await fetch('/api/equipment');
                const data = await response.json();
                this.equipment = data;
            } catch (error) {
                console.log('Error loading equipment:', error);
            }
        },
        
        async reverseGeocode(lat, lng) {
            try {
                const response = await fetch(`/api/geocode/reverse?lat=${lat}&lng=${lng}`);
                const data = await response.json();
                return data.address || `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            } catch (error) {
                return `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            }
        },
        
        initUserMap() {
            if (this.userMap) return;
            
            const defaultLoc = [-6.200000, 106.816666];
            this.userMap = L.map('userMap').setView(defaultLoc, 13);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(this.userMap);
            
            const redIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            
            this.userMarker = L.marker(defaultLoc, { draggable: true, icon: redIcon }).addTo(this.userMap);
            
            this.userMap.on('click', async (e) => {
                const { lat, lng } = e.latlng;
                this.userMarker.setLatLng([lat, lng]);
                await this.updateUserLocation(lat, lng);
            });
            
            this.userMarker.on('dragend', async (e) => {
                const { lat, lng } = e.target.getLatLng();
                await this.updateUserLocation(lat, lng);
            });
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async (position) => {
                    const { latitude, longitude } = position.coords;
                    this.userMap.setView([latitude, longitude], 15);
                    this.userMarker.setLatLng([latitude, longitude]);
                    await this.updateUserLocation(latitude, longitude);
                });
            }
        },
        
        async updateUserLocation(lat, lng) {
            this.userReportForm.lat = lat;
            this.userReportForm.lng = lng;
            const address = await this.reverseGeocode(lat, lng);
            this.userReportForm.address = address;
        },
        
        adminLogin() {
            if (this.loginPassword === this.adminPassword) {
                this.isLoggedIn = true;
                localStorage.setItem('siaga_admin_session', 'true');
                this.showToast('✅ Login berhasil! Selamat datang Admin Damkar.');
                this.loginPassword = '';
            } else {
                this.showToast('❌ Password salah! Gunakan: damkar2024');
            }
        },
        
        adminLogout() {
            this.isLoggedIn = false;
            localStorage.removeItem('siaga_admin_session');
            this.showToast('🔓 Logout berhasil.');
        },
        
        openUserReportModal() {
            this.userReportForm = { location: '', address: '', description: '', reporter: '', scale: 'Kecil', lat: '', lng: '' };
            this.isUserReportModalOpen = true;
            setTimeout(() => this.initUserMap(), 300);
        },
        
        closeUserReportModal() {
            this.isUserReportModalOpen = false;
            if (this.userMap) {
                this.userMap.remove();
                this.userMap = null;
                this.userMarker = null;
            }
        },
        
        async submitUserReport() {
            if (!this.userReportForm.lat || !this.userReportForm.lng) {
                this.showToast('⚠️ Silakan tentukan lokasi kebakaran dengan menekan peta!');
                return;
            }
            if (!this.userReportForm.reporter) {
                this.showToast('⚠️ Nama pelapor harus diisi!');
                return;
            }
            
            const newReport = {
                location: this.userReportForm.location || 'Lokasi tidak bernama',
                address: this.userReportForm.address,
                description: this.userReportForm.description,
                reporter: this.userReportForm.reporter,
                scale: this.userReportForm.scale,
                status: 'Laporan Baru',
                source: 'Masyarakat',
                lat: parseFloat(this.userReportForm.lat),
                lng: parseFloat(this.userReportForm.lng)
            };
            
            try {
                const response = await fetch('/api/incidents', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(newReport)
                });
                
                if (response.ok) {
                    this.showToast('🔥 Laporan kebakaran berhasil dikirim! Tim akan segera merespon.');
                    this.closeUserReportModal();
                    await this.loadIncidents();
                } else {
                    this.showToast('❌ Gagal mengirim laporan!');
                }
            } catch (error) {
                this.showToast('❌ Error: ' + error.message);
            }
        },
        
        openIncidentModal(incident = null) {
            this.modalType = 'incident';
            this.modalMode = incident ? 'edit' : 'create';
            if (incident) {
                this.formData = { ...incident };
            } else {
                this.formData = { id: null, location: '', address: '', description: '', reporter: '', scale: 'Sedang', status: 'Laporan Baru', source: 'Admin', lat: -6.200000, lng: 106.816666 };
            }
            this.isModalOpen = true;
        },
        
        editIncident(incident) { 
            this.openIncidentModal(incident); 
        },
        
        openVolunteerModal(volunteer = null) {
            this.modalType = 'volunteer';
            this.modalMode = volunteer ? 'edit' : 'create';
            if (volunteer) {
                this.formData = { ...volunteer };
            } else {
                this.formData = { id: null, name: '', role: '', phone: '', blood_type: 'O', join_date: new Date().toISOString().split('T')[0] };
            }
            this.isModalOpen = true;
        },
        
        editVolunteer(volunteer) { 
            this.openVolunteerModal(volunteer); 
        },
        
        openEquipmentModal(equipment = null) {
            this.modalType = 'equipment';
            this.modalMode = equipment ? 'edit' : 'create';
            if (equipment) {
                this.formData = { ...equipment };
            } else {
                this.formData = { id: null, name: '', category: 'APAR', quantity: 1, status: 'Baik', last_service: new Date().toISOString().split('T')[0] };
            }
            this.isModalOpen = true;
        },
        
        editEquipment(equipment) { 
            this.openEquipmentModal(equipment); 
        },
        
        openInMaps(lat, lng) {
            window.open(`https://www.openstreetmap.org/?mlat=${lat}&mlon=${lng}#map=15/${lat}/${lng}`, '_blank');
        },
        
        async submitForm() {
            let url = '';
            let method = '';
            
            if (this.modalType === 'incident') {
                url = this.modalMode === 'create' ? '/api/incidents' : `/api/incidents/${this.formData.id}`;
                method = this.modalMode === 'create' ? 'POST' : 'PUT';
            } else if (this.modalType === 'volunteer') {
                url = this.modalMode === 'create' ? '/api/volunteers' : `/api/volunteers/${this.formData.id}`;
                method = this.modalMode === 'create' ? 'POST' : 'PUT';
            } else {
                url = this.modalMode === 'create' ? '/api/equipment' : `/api/equipment/${this.formData.id}`;
                method = this.modalMode === 'create' ? 'POST' : 'PUT';
            }
            
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.formData)
                });
                
                if (response.ok) {
                    this.showToast(this.modalMode === 'create' ? '✅ Data berhasil ditambahkan!' : '✅ Data berhasil diperbarui!');
                    this.closeModal();
                    await this.loadIncidents();
                    await this.loadVolunteers();
                    await this.loadEquipment();
                }
            } catch (error) {
                this.showToast('❌ Error: ' + error.message);
            }
        },
        
        confirmDelete(type, id) {
            this.currentDeleteType = type;
            this.currentDeleteId = id;
            this.isDeleteConfirmOpen = true;
        },
        
        cancelDelete() { 
            this.isDeleteConfirmOpen = false; 
            this.currentDeleteId = null; 
            this.currentDeleteType = null; 
        },
        
        async executeDelete() {
            let url = '';
            if (this.currentDeleteType === 'incident') {
                url = `/api/incidents/${this.currentDeleteId}`;
            } else if (this.currentDeleteType === 'volunteer') {
                url = `/api/volunteers/${this.currentDeleteId}`;
            } else {
                url = `/api/equipment/${this.currentDeleteId}`;
            }
            
            try {
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    this.showToast('🗑️ Data berhasil dihapus!');
                    await this.loadIncidents();
                    await this.loadVolunteers();
                    await this.loadEquipment();
                }
            } catch (error) {
                this.showToast('❌ Error: ' + error.message);
            }
            
            this.cancelDelete();
        },
        
        closeModal() { 
            this.isModalOpen = false; 
            this.formData = {};
        },
        
        showToast(msg) { 
            this.toastMessage = msg; 
            setTimeout(() => this.toastMessage = '', 3000); 
        }
    }
}

// Initialize app when page loads
document.addEventListener('alpine:init', () => {
    window.fireApp = fireApp;
});
</script>
@endpush
@endsection