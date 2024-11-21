<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk</title>
    <link rel="stylesheet" href="<?= base_url('asset/bootstrap-5.0.2-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('asset/fontawesome-free-6.6.0-web/fontawesome-free-6.6.0-web/css/all.min.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <h3 class="text-center">Data Produk</h3>
                <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
                    <i class="fa-solid fa-cart-plus"></i> Tambah Data
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="container mt-5">
                    <table class="table table-bordered" id="produkTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalTambahProduk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalTambahProdukLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="modalTambahProdukLabel">Tambah Produk</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formProduk">
                            <div class="mb-3">
                                <label for="namaProduk" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="namaProduk" name="namaProduk" required>
                            </div>
                            <div class="mb-3">
                                <label for="hargaProduk" class="form-label">Harga</label>
                                <input type="number" step="0.01" class="form-control" id="hargaProduk" name="harga" required>
                            </div>
                            <div class="mb-3">
                                <label for="stokProduk" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="stokProduk" name="stok" required>
                            </div>
                            <button type="button" id="simpanProduk" class="btn btn-primary float-end" data-action="add">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="<?= base_url('asset/jquery-3.7.1.min.js') ?>"></script>
        <script src="<?= base_url('asset/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') ?>"></script>
        <script src="<?= base_url('asset/fontawesome-free-6.6.0-web/fontawesome-free-6.6.0-web/js/all.min.js') ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            function tampilProduk() {
                $.ajax({
                    url: '<?= base_url('produk/tampil') ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function (hasil) {
                        if (hasil.status === 'success') {
                            var produkTable = $('#produkTable tbody');
                            produkTable.empty();
                            var no = 1;
                            hasil.produk.forEach(function (item) {
                                produkTable.append(`
                                    <tr>
                                        <td>${no++}</td>
                                        <td>${item.nama_produk}</td>
                                        <td>${item.harga_produk}</td>
                                        <td>${item.stok}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm editProduk" data-id="${item.produk_id}"><i class="fa-solid fa-pencil"></i> Edit</button>
                                            <button class="btn btn-danger btn-sm hapusProduk" data-id="${item.produk_id}"><i class="fa-solid fa-trash-can"></i> Hapus</button>
                                        </td>
                                    </tr>
                                `);
                            });
                        } else {
                            Swal.fire("Error", "Gagal mengambil data produk", "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Terjadi kesalahan saat mengambil data", "error");
                    }
                });
            }

            $(document).ready(function () {
                tampilProduk();

                $("#simpanProduk").on("click", function () {
                    var action = $(this).data("action");
                    var url = action === "update" ? "<?= base_url('produk/update') ?>" : "<?= base_url('produk/simpan') ?>";

                    var formData = {
                        nama_produk: $("#namaProduk").val(),
                        harga: $("#hargaProduk").val(),
                        stok: $("#stokProduk").val()
                    };

                    if (action === "update") {
                        formData.produk_id = $(this).data("id");
                    }

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function (hasil) {
                            if (hasil.status === 'success') {
                                $("#modalTambahProduk").modal('hide');
                                $("#formProduk")[0].reset();
                                $("#simpanProduk").data("action", "add").removeData("id");
                                tampilProduk();
                                Swal.fire("Success", "Product data saved successfully", "success");
                            } else {
                                Swal.fire("Error", "Gagal menyimpan data produk", "error");
                            }
                        },
                        error: function () {
                            Swal.fire("Error", "Terjadi kesalahan saat menyimpan data", "error");
                        }
                    });
                });

                $(document).on("click", ".editProduk", function () {
                    let produkId = $(this).data("id");

                    $.ajax({
                        url: `<?= base_url('produk/get') ?>/${produkId}`,
                        type: "GET",
                        dataType: "json",
                        success: function (hasil) {
                            if (hasil.status === "success") {
                                $("#namaProduk").val(hasil.produk.nama_produk);
                                $("#hargaProduk").val(hasil.produk.harga_produk);
                                $("#stokProduk").val(hasil.produk.stok);
                                $("#modalTambahProduk").modal("show");
                                $("#simpanProduk").data("action", "update").data("id", produkId);
                            } else {
                                Swal.fire("Error", "Gagal mengambil data produk", "error");
                            }
                        },
                        error: function () {
                            Swal.fire("Error", "Terjadi kesalahan saat mengambil data", "error");
                        }
                    });
                });

                $(document).on("click", ".hapusProduk", function () {
                    var produkId = $(this).data("id");

                    Swal.fire({
                        title: "Are you sure?",
                        text: "Data produk ini akan dihapus!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, hapus!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `<?= base_url('produk/hapus') ?>/${produkId}`,
                                type: "POST",
                                dataType: "json",
                                success: function (hasil) {
                                    if (hasil.status === "success") {
                                        tampilProduk();
                                        Swal.fire("Deleted!", "Data produk berhasil dihapus", "success");
                                    } else {
                                        Swal.fire("Error", "Gagal menghapus data produk", "error");
                                    }
                                },
                                error: function () {
                                    Swal.fire("Error", "Terjadi kesalahan saat menghapus data", "error");
                                }
                            });
                        }
                    });
                });
            });
        </script>
</body>

</html>