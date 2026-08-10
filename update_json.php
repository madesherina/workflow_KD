<?php
$new_translations = [
    "Creator Dashboard" => "Dasbor Pembuat",
    "My Content" => "Konten Saya",
    "Revision Notes" => "Catatan Revisi",
    "Published Content" => "Konten Dipublikasikan",
    "Super Admin Dashboard" => "Dasbor Super Admin",
    "User Management" => "Manajemen Pengguna",
    "Verifier Dashboard" => "Dasbor Verifikator",
    "Publisher Dashboard" => "Dasbor Penerbit",
    "Content Management" => "Manajemen Konten",
    "Verification" => "Verifikasi",
    "Review Queue" => "Antrean Tinjauan",
    "Approved Content" => "Konten Disetujui",
    "Rejected Content" => "Konten Ditolak",
    "Publishing" => "Penerbitan",
    "Publish Queue" => "Antrean Publikasi",
    "Scheduled Content" => "Konten Terjadwal",
    "History Logs" => "Log Riwayat",
    "Activity Logs" => "Log Aktivitas",
    "Account" => "Akun",
    "My Profile" => "Profil Saya",
    "System" => "Sistem",
    "Dashboard" => "Dasbor",
    "Account Settings" => "Pengaturan Akun",
    "Change Password" => "Ubah Kata Sandi",
    "Notifications" => "Notifikasi",
    "Mark all as read" => "Tandai semua sudah dibaca",
    "Content Approved" => "Konten Disetujui",
    "Your content \"Summer Campaign\" has been approved." => "Konten Anda \"Kampanye Musim Panas\" telah disetujui.",
    "2 minutes ago" => "2 menit yang lalu",
    "Content Rejected" => "Konten Ditolak",
    "\"Product Launch Video\" needs revision." => "\"Video Peluncuran Produk\" perlu direvisi.",
    "1 hour ago" => "1 jam yang lalu",
    "Super Admin Portal" => "Portal Super Admin",
    "Monitor your content workflow, manage user access, and customize system settings." => "Pantau alur kerja konten, kelola akses pengguna, dan sesuaikan pengaturan sistem.",
    "Total Users" => "Total Pengguna",
    "Active Sessions" => "Sesi Aktif",
    "Pending Reviews" => "Menunggu Tinjauan",
    "Ready to Publish" => "Siap Dipublikasikan",
    "System Health" => "Kesehatan Sistem",
    "Recent Workflow History" => "Riwayat Alur Kerja Terbaru",
    "View Full Logs" => "Lihat Log Penuh",
    "created" => "dibuat",
    "changed status of" => "mengubah status dari",
    "Deleted Content" => "Konten Dihapus",
    "No history log found" => "Tidak ada log riwayat ditemukan",
    "System operations will be logged here in chronological order." => "Operasi sistem akan dicatat di sini secara berurutan.",
    "Quick Actions" => "Tindakan Cepat",
    "Manage Users" => "Kelola Pengguna",
    "Register roles, verify or block users" => "Daftarkan peran, verifikasi atau blokir pengguna",
    "Configure storage, workflow and security" => "Konfigurasi penyimpanan, alur kerja dan keamanan",
    "Monitor Activity Logs" => "Pantau Log Aktivitas",
    "Audit system and publish event traces" => "Sistem audit dan jejak publikasi"
];

$path = __DIR__ . '/lang/id.json';
if (file_exists($path)) {
    $data = json_decode(file_get_contents($path), true);
} else {
    $data = [];
}

foreach ($new_translations as $k => $v) {
    if (!isset($data[$k])) {
        $data[$k] = $v;
    }
}

file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Translations updated.\n";
