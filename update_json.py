import json
import os

new_translations = {
    "Creator Dashboard": "Dasbor Pembuat",
    "My Content": "Konten Saya",
    "Revision Notes": "Catatan Revisi",
    "Published Content": "Konten Dipublikasikan",
    "Super Admin Dashboard": "Dasbor Super Admin",
    "User Management": "Manajemen Pengguna",
    "Verifier Dashboard": "Dasbor Verifikator",
    "Publisher Dashboard": "Dasbor Penerbit",
    "Content Management": "Manajemen Konten",
    "Verification": "Verifikasi",
    "Review Queue": "Antrean Tinjauan",
    "Approved Content": "Konten Disetujui",
    "Rejected Content": "Konten Ditolak",
    "Publishing": "Penerbitan",
    "Publish Queue": "Antrean Publikasi",
    "Scheduled Content": "Konten Terjadwal",
    "History Logs": "Log Riwayat",
    "Activity Logs": "Log Aktivitas",
    "Account": "Akun",
    "My Profile": "Profil Saya",
    "System": "Sistem",
    "Dashboard": "Dasbor",
    "Account Settings": "Pengaturan Akun",
    "Change Password": "Ubah Kata Sandi",
    "Notifications": "Notifikasi",
    "Mark all as read": "Tandai semua sudah dibaca",
    "Content Approved": "Konten Disetujui",
    "Your content \"Summer Campaign\" has been approved.": "Konten Anda \"Kampanye Musim Panas\" telah disetujui.",
    "2 minutes ago": "2 menit yang lalu",
    "Content Rejected": "Konten Ditolak",
    "\"Product Launch Video\" needs revision.": "\"Video Peluncuran Produk\" perlu direvisi.",
    "1 hour ago": "1 jam yang lalu"
}

path = "c:\\laragon\\www\\workflow_KD\\lang\\id.json"
if os.path.exists(path):
    with open(path, "r", encoding="utf-8") as f:
        data = json.load(f)
else:
    data = {}

for k, v in new_translations.items():
    if k not in data:
        data[k] = v

with open(path, "w", encoding="utf-8") as f:
    json.dump(data, f, indent=4, ensure_ascii=False)
