<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbPelanggan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pelanggan' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'nama_pelanggan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false, 
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'no_tlpn' => [
                'type' => 'VARCHAR',
                'constraint' => 15, // Anda bisa sesuaikan sesuai panjang maksimal nomor telepon
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id_pelanggan', true);
        $this->forge->createTable('tb_pelanggan'); // Nama tabel diganti sesuai dengan kebutuhan
    }

    public function down()
    {
        $this->forge->dropTable('tb_pelanggan'); // Menghapus tabel tb_pelanggan jika migrasi di-rollback
    }
}
