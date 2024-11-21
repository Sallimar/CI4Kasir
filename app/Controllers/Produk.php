<?php

namespace App\Controllers; // Mendeklarasikan namespace untuk controller ini

use App\Controllers\BaseController; // Mengimpor BaseController dari namespace App\Controllers
use CodeIgniter\HTTP\ResponseInterface; // Mengimpor ResponseInterface untuk mengatur respons
use App\Models\ProdukModel; // Mengimpor ProdukModel dari namespace App\Models untuk mengakses data produk

class Produk extends BaseController // Deklarasi class Produk yang mewarisi BaseController
{
    protected $produkmodel; // Mendefinisikan properti untuk model produk

    public function __construct()
    {
        // Menginisialisasi objek ProdukModel untuk mengakses tabel produk di database
        $this->produkmodel = new ProdukModel();
    }

    public function index()
    {
        // Mengembalikan view dengan nama 'v_produk' saat fungsi index dipanggil
        return view('v_produk');
    }

    public function simpan_produk()
    {
        // Fungsi untuk menyimpan data produk, dipanggil dari AJAX

        // Memanggil layanan validasi CodeIgniter
        $validation = \Config\Services::validation();

        // Menentukan aturan validasi untuk input dari AJAX
        $validation->setRules([
            'nama_produk' => 'required', // Nama produk wajib diisi
            'harga' => 'required|decimal', // Harga wajib diisi dan harus desimal
            'stok' => 'required|integer',  // Stok wajib diisi dan harus integer
        ]);

        // Mengecek apakah validasi gagal dengan input request saat ini
        if (!$validation->withRequest($this->request)->run()) {
            // Jika validasi gagal, kembalikan respons JSON dengan status error dan rincian error
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors(),
            ]);
        }

        // Jika validasi berhasil, data disiapkan untuk disimpan
        $data = [
            'nama_produk' => $this->request->getVar('nama_produk'), // Mengambil nama produk dari request
            'harga_produk' => $this->request->getVar('harga'), // Mengambil harga dari request
            'stok' => $this->request->getVar('stok'), // Mengambil stok dari request
        ];

        // Menyimpan data produk ke dalam database menggunakan ProdukModel
        $this->produkmodel->save($data);

        // Mengembalikan respons JSON yang menunjukkan data berhasil disimpan
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data produk berhasil disimpan', // Pesan keberhasilan
        ]);
    }

    public function tampil_produk()
    {
        // Fungsi untuk menampilkan data produk dalam bentuk JSON

        // Mengambil semua data produk dari database
        $produk = $this->produkmodel->findAll();

        // Mengembalikan data produk dalam bentuk JSON untuk ditampilkan di frontend
        return $this->response->setJSON([
            'status' => 'success', // Status respons
            'produk' => $produk // Data produk dari database
        ]);
    }

    public function get($id)
    {
        // Fungsi untuk mengambil satu data produk berdasarkan ID

        // Mencari produk berdasarkan ID yang diberikan
        $produk = $this->produkmodel->find($id);

        // Jika produk ditemukan, kirim respons JSON dengan status sukses dan data produk
        if ($produk) {
            return $this->response->setJSON([
                'status' => 'success',
                'produk' => $produk,
            ]);
        } else {
            // Jika produk tidak ditemukan, kirim respons JSON dengan status error
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan',
            ]);
        }
    }

    public function update()
    {
        // Fungsi untuk memperbarui data produk berdasarkan ID

        $id = $this->request->getVar('produk_id'); // Mengambil ID produk dari request

        // Validasi input dari AJAX
        $validation = \Config\Services::validation();

        // Menentukan aturan validasi untuk input
        $validation->setRules([
            'nama_produk' => 'required',
            'harga' => 'required|decimal',
            'stok' => 'required|integer',
        ]);

        // Jika validasi gagal, kembalikan respons JSON dengan status error dan rincian error
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors(),
            ]);
        }

        // Jika validasi berhasil, data disiapkan untuk diupdate
        $data = [
            'nama_produk' => $this->request->getVar('nama_produk'),
            'harga_produk' => $this->request->getVar('harga'),
            'stok' => $this->request->getVar('stok'),
        ];

        // Memperbarui data produk berdasarkan ID menggunakan ProdukModel
        $this->produkmodel->update($id, $data);

        // Mengembalikan respons JSON yang menunjukkan data berhasil diupdate
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data produk berhasil diupdate',
        ]);
    }

    public function hapus($id = null)
    {
        // Fungsi untuk menghapus data produk berdasarkan ID

        // Memastikan ID produk telah diberikan
        if ($id === null) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID produk tidak ditemukan',
            ]);
        }

        // Periksa apakah produk dengan ID tersebut ada
        $produk = $this->produkmodel->find($id);

        if ($produk) {
            // Hapus produk menggunakan soft delete
            $this->produkmodel->delete($id);

            // Kirim respons sukses dalam format JSON
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Produk berhasil dihapus',
            ]);
        } else {
            // Produk tidak ditemukan
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan',
            ]);
        }
    }

}
