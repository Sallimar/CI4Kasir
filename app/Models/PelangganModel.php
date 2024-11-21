<?php

namespace App\Models; // Mendeklarasikan namespace untuk model agar dapat digunakan dengan autoloading

use CodeIgniter\Model; // Mengimpor Model utama dari CodeIgniter

class PelangganModel extends Model // Mendefinisikan kelas PelangganModel yang merupakan turunan dari Model
{
    protected $table = 'tb_pelanggan'; // Menentukan nama tabel di database yang digunakan oleh model ini
    protected $primaryKey = 'id_pelanggan'; // Menentukan primary key pada tabel tersebut
    protected $useAutoIncrement = true; // Mengaktifkan auto-increment pada primary key
    protected $returnType = 'array'; // Mengatur format data yang dikembalikan model, dalam bentuk array
    protected $useSoftDeletes = true; // Mengaktifkan soft delete, sehingga data tidak dihapus secara permanen
    protected $protectFields = true; // Melindungi kolom agar hanya kolom yang diizinkan yang dapat diisi
    protected $allowedFields = ['nama_pelanggan', 'alamat', 'no_tlpn']; // Menentukan kolom-kolom yang diizinkan untuk diisi

    // Dates
    protected $useTimestamps = true; // Mengaktifkan otomatisasi timestamp (created_at, updated_at)
    protected $dateFormat = 'datetime'; // Format tanggal yang digunakan adalah datetime
    protected $createdField = 'created_at'; // Nama kolom untuk menyimpan timestamp data saat pertama kali dibuat
    protected $updatedField = 'updated_at'; // Nama kolom untuk menyimpan timestamp data saat terakhir diperbarui
    protected $deletedField = 'deleted_at'; // Nama kolom untuk menyimpan timestamp data yang dihapus secara soft delete
}
