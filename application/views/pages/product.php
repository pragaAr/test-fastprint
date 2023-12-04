<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">

  <title>
    <?= $title ?>
  </title>
</head>

<body>
  <main>
    <div class="py-5">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <h4>
            <?= $title ?>
          </h4>
        </div>
        <hr>

        <div class="card" id="nodata" style="display:none">
          <div class="card-body">
            <div class="d-flex justify-content-center align-items-center flex-column py-3">
              <p class="font-weight-bold">--Belum ada data di database--</p>
              <button class="btn btn-danger" id="btnInitFromApi">Init data dari API</button>
            </div>
          </div>
        </div>

        <?php if ($this->session->flashdata('flash')) { ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>
              <?= $this->session->flashdata('flash'); ?>
            </strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php } ?>

        <div id="content" style="display:none">

          <a href="<?= base_url('product/add') ?>" class="btn btn-primary mb-3">
            Tambah Data
          </a>

          <p class="fst-italic">#Data yang ditampilkan hanya yang status nya "bisa dijual"</p>
          <div class="table-responsive">
            <table class="table table-bordered" id="tableProduct">
              <thead class="text-center align-middle" style="font-size:14px">
                <tr>
                  <th>No.</th>
                  <th>Id Produk</th>
                  <th>Nama Produk</th>
                  <th>Harga</th>
                  <th>Kategori</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody style="font-size:13px">

              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', async function() {
      try {
        const request = await fetch('<?= base_url('product/getProduct') ?>');
        const response = await request.json();

        console.log(response)

        if (response.length == 0) {
          const nodata = document.getElementById('nodata')
          nodata.style.display = "block"
        } else {
          const nodata = document.getElementById('nodata')
          nodata.style.display = "none"

          // ----------------------------------------------

          const tbody = document.querySelector("#tableProduct tbody");
          tbody.innerHTML = "";

          let nomor = 1;
          // Looping data ke table
          response.forEach(function(produk) {
            const row = tbody.insertRow();
            const cellNo = row.insertCell(0);
            const cellId = row.insertCell(1);
            const cellNama = row.insertCell(2);
            const cellHarga = row.insertCell(3);
            const cellKategori = row.insertCell(4);
            const cellStatus = row.insertCell(5);
            const cellAksi = row.insertCell(6);

            cellNo.textContent = nomor++;
            cellNo.classList.add('text-center', 'align-middle');

            cellId.textContent = produk.id_produk;
            cellId.classList.add('text-center', 'align-middle');

            cellNama.textContent = produk.nama_produk;
            cellNama.classList.add('align-middle');

            cellHarga.textContent = produk.harga;
            cellHarga.classList.add('text-end', 'align-middle');

            cellKategori.textContent = produk.nama_kategori;
            cellKategori.classList.add('align-middle');

            cellStatus.textContent = produk.nama_status;
            cellStatus.classList.add('text-center', 'align-middle');

            const actionButtons = document.createElement("div");
            actionButtons.className = "btn-group";

            const editButton = document.createElement("a");
            editButton.className = "btn btn-sm btn-warning btnEdit";
            editButton.textContent = "Edit";
            editButton.href = "<?= base_url('product/edit/') ?>" + produk.id_produk;

            const deleteButton = document.createElement("a");
            deleteButton.className = "btn btn-sm btn-danger btnDelete";
            deleteButton.textContent = "Hapus";
            deleteButton.href = "<?= base_url('product/delete/') ?>" + produk.id_produk;

            actionButtons.appendChild(editButton);
            actionButtons.appendChild(deleteButton);

            cellAksi.appendChild(actionButtons);
          });

          // ----------------------------------------------

          const content = document.getElementById('content')
          content.style.display = "block"
        }

      } catch (error) {
        console.error('Error fetching data:', error);
      }

      const btnInitFromApi = document.getElementById('btnInitFromApi');

      btnInitFromApi.addEventListener('click', function() {
        initDataApi();
      });

      async function initDataApi() {

        try {
          const response = await fetch("<?= base_url('product/initialize') ?>");

          const res = await response.json();

          if (res.status == 200) {
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: 'Init data dari API sukses',
            });

            setTimeout(() => {
              location.reload();

            }, 2000);

          }

        } catch (error) {
          console.error('Error fetching data:', error);
          return [];
        }
      }

      const btnDelete = document.querySelectorAll('.btnDelete');

      btnDelete.forEach(function(button) {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          const href = button.getAttribute('href');

          Swal.fire({
            icon: 'warning',
            title: 'Apakah anda yakin ?',
            text: 'Data akan di hapus',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
          }).then((result) => {
            if (result.value) {
              window.location.href = href;
            }
          });
        });
      });

    });
  </script>
</body>

</html>