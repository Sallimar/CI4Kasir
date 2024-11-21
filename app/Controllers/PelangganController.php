<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use CodeIgniter\Controller;

class PelangganController extends Controller
{
    protected $pelangganModel;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
    }

    public function index()
    {
        return view('v_pelanggan'); // Tampilkan halaman pelanggan
    }

    public function simpanPelanggan()
    {
        // Mendapatkan data yang dikirimkan oleh client
        $data = [
            'nama_pelanggan' => $this->request->getVar('nama_pelanggan'),
            'alamat' => $this->request->getVar('alamat'),
            'telepon' => $this->request->getVar('telepon'),
        ];

        // Simpan data pelanggan
        if ($this->pelangganModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data pelanggan berhasil disimpan',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data pelanggan gagal disimpan',
            ]);
        }
    }

    public function tampilPelanggan()
    {
        // Mengambil semua data pelanggan
        $pelanggan = $this->pelangganModel->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'pelanggan' => $pelanggan,
        ]);
    }

    public function get($id)
    {
        // Mengambil data pelanggan berdasarkan ID
        $pelanggan = $this->pelangganModel->find($id);

        if ($pelanggan) {
            return $this->response->setJSON([
                'status' => 'success',
                'pelanggan' => $pelanggan,
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pelanggan tidak ditemukan',
            ]);
        }
    }

    public function update()
    {
        // Mengambil data dari request
        $id = $this->request->getVar('pelanggan_id');
        $data = [
            'nama_pelanggan' => $this->request->getVar('nama_pelanggan'),
            'alamat' => $this->request->getVar('alamat'),
            'telepon' => $this->request->getVar('telepon'),
        ];

        // Mengupdate data pelanggan berdasarkan ID
        if ($this->pelangganModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data pelanggan berhasil diupdate',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data pelanggan gagal diupdate',
            ]);
        }
    }

    public function hapus($id)
    {
        // Menghapus data pelanggan berdasarkan ID
        if ($this->pelangganModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pelanggan berhasil dihapus',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pelanggan tidak ditemukan',
            ]);
        }
    }
}
