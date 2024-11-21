<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/produk', 'Produk::index');
$routes->post('/produk/simpan', 'Produk::simpan_produk');
$routes->get('/produk/tampil', 'Produk::tampil_produk');
$routes->get('produk/get/(:num)', 'Produk::get/$1');
$routes->post('produk/update', 'Produk::update');
$routes->post('produk/hapus/(:num)', 'Produk::hapus/$1');

// Perbaiki rute pelanggan
$routes->get('pelanggan', 'PelangganController::index'); // Menampilkan halaman pelanggan
$routes->post('pelanggan/simpan', 'PelangganController::simpanPelanggan'); // Menyimpan pelanggan
$routes->get('pelanggan/tampil', 'PelangganController::tampilPelanggan'); // Menampilkan data pelanggan
$routes->get('pelanggan/(:num)', 'PelangganController::get/$1'); // Mendapatkan data pelanggan berdasarkan ID
$routes->post('pelanggan/update', 'PelangganController::update'); // Memperbarui pelanggan
$routes->delete('pelanggan/hapus/(:num)', 'PelangganController::hapus/$1'); // Menghapus pelanggan berdasarkan ID
