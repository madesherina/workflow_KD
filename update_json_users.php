<?php
$new_translations = [
    "Manage administrator accounts, roles, and system access." => "Kelola akun administrator, peran, dan akses sistem.",
    "System Users" => "Pengguna Sistem",
    "Add New User" => "Tambah Pengguna Baru",
    "Name" => "Nama",
    "Email" => "Email",
    "Role" => "Peran",
    "Status" => "Status",
    "Action" => "Aksi",
    "No Role" => "Tidak Ada Peran",
    "Active" => "Aktif",
    "Are you sure?" => "Apakah Anda yakin?",
    "Add New Administrator" => "Tambah Administrator Baru",
    "Full Name" => "Nama Lengkap",
    "Enter full name" => "Masukkan nama lengkap",
    "Email Address" => "Alamat Email",
    "Password" => "Kata Sandi",
    "Min 8 characters" => "Min 8 karakter",
    "Assign Role" => "Tetapkan Peran",
    "Select a role" => "Pilih peran",
    "Cancel" => "Batal",
    "Create Account" => "Buat Akun",
    "Edit Administrator" => "Edit Administrator",
    "Password (Leave blank to keep current)" => "Kata Sandi (Biarkan kosong untuk mempertahankan yang sekarang)",
    "Save Changes" => "Simpan Perubahan"
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
