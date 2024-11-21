<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan</title>
    <link rel="stylesheet" href="<?= base_url('asset/bootstrap-5.0.2-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('asset/fontawesome-free-6.6.0-web/fontawesome-free-6.6.0-web/css/all.min.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <h3 class="text-center">Data Pelanggan</h3>
                <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#modalTambahPelanggan">
                    <i class="fa-solid fa-user-plus"></i> Tambah Data
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="container mt-5">
                    <table class="table table-bordered" id="pelangganTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pelanggan</th>
                                <th>Alamat</th>
                                <th>No. Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalTambahPelanggan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalTambahPelangganLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="modalTambahPelangganLabel">Tambah Pelanggan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formPelanggan">
                            <div class="mb-3">
                                <label for="namaPelanggan" class="form-label">Nama Pelanggan</label>
                                <input type="text" class="form-control" id="namaPelanggan" name="namaPelanggan" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamatPelanggan" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamatPelanggan" name="alamat" required>
                            </div>
                            <div class="mb-3">
                                <label for="teleponPelanggan" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" id="teleponPelanggan" name="telepon" required>
                            </div>
                            <button type="button" id="simpanPelanggan" class="btn btn-primary float-end" data-action="add">Simpan</button>
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
            function tampilPelanggan() {
                $.ajax({
                    url: '<?= base_url('pelanggan/tampil') ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function (hasil) {
                        if (hasil.status === 'success') {
                            var pelangganTable = $('#pelangganTable tbody');
                            pelangganTable.empty();
                            var no = 1;
                            hasil.pelanggan.forEach(function (item) {
                                pelangganTable.append(`
                                    <tr>
                                        <td>${no++}</td>
                                        <td>${item.nama_pelanggan}</td>
                                        <td>${item.alamat}</td>
                                        <td>${item.no_tlpn}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm editPelanggan" data-id="${item.id_pelanggan}"><i class="fa-solid fa-pencil"></i> Edit</button>
                                            <button class="btn btn-danger btn-sm hapusPelanggan" data-id="${item.id_pelanggan}"><i class="fa-solid fa-trash-can"></i> Hapus</button>
                                        </td>
                                    </tr>
                                `);
                            });
                        } else {
                            Swal.fire("Error", "Gagal mengambil data pelanggan", "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Terjadi kesalahan saat mengambil data", "error");
                    }
                });
            }

            $(document).ready(function () {
                tampilPelanggan();

                $("#simpanPelanggan").on("click", function () {
                    var action = $(this).data("action");
                    var url = action === "update" ? "<?= base_url('pelanggan/update') ?>" : "<?= base_url('pelanggan/simpan') ?>";

                    var formData = {
                        nama_pelanggan: $("#namaPelanggan").val(),
                        alamat: $("#alamatPelanggan").val(),
                        no_tlpn: $("#teleponPelanggan").val()
                    };

                    if (action === "update") {
                        formData.id_pelanggan = $(this).data("id");
                    }

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function (hasil) {
                            if (hasil.status === 'success') {
                                $("#modalTambahPelanggan").modal('hide');
                                $("#formPelanggan")[0].reset();
                                $("#simpanPelanggan").data("action", "add").removeData("id");
                                tampilPelanggan();
                                Swal.fire("Success", "Data pelanggan berhasil disimpan", "success");
                            } else {
                                Swal.fire("Error", "Gagal menyimpan data pelanggan", "error");
                            }
                        },
                        error: function () {
                            Swal.fire("Error", "Terjadi kesalahan saat menyimpan data", "error");
                        }
                    });
                });

                $(document).on("click", ".editPelanggan", function () {
                    let pelangganId = $(this).data("id");

                    $.ajax({
                        url: `<?= base_url('pelanggan/get') ?>/${pelangganId}`,
                        type: "GET",
                        dataType: "json",
                        success: function (hasil) {
                            if (hasil.status === "success") {
                                $("#namaPelanggan").val(hasil.pelanggan.nama_pelanggan);
                                $("#alamatPelanggan").val(hasil.pelanggan.alamat);
                                $("#teleponPelanggan").val(hasil.pelanggan.no_tlpn);
                                $("#modalTambahPelanggan").modal("show");
                                $("#simpanPelanggan").data("action", "update").data("id", pelangganId);
                            } else {
                                Swal.fire("Error", "Gagal mengambil data pelanggan", "error");
                            }
                        },
                        error: function () {
                            Swal.fire("Error", "Terjadi kesalahan saat mengambil data", "error");
                        }
                    });
                });

                $(document).on("click", ".hapusPelanggan", function () {
                    var pelangganId = $(this).data("id");

                    Swal.fire({
                        title: "Are you sure?",
                        text: "Data pelanggan ini akan dihapus!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, hapus!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `<?= base_url('pelanggan/hapus') ?>/${pelangganId}`,
                                type: "POST",
                                dataType: "json",
                                success: function (hasil) {
                                    if (hasil.status === "success") {
                                        tampilPelanggan();
                                        Swal.fire("Deleted!", "Data pelanggan berhasil dihapus", "success");
                                    } else {
                                        Swal.fire("Error", "Gagal menghapus data pelanggan", "error");
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
