<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <title>
    <?= $title ?>
  </title>
</head>

<body>
  <main>
    <div class="py-5">
      <div class="container">
        <div class="d-flex justify-content-between align-items-baseline flex-wrap">
          <h4>
            <?= $title ?>
          </h4>
          <div class="btn-group">
            <a href="<?= base_url('product') ?>" class="btn btn-dark">Kembali</a>
          </div>
        </div>
        <hr>

        <div class="card">
          <div class="card-body">
            <form action="" method="POST">
              <div class="mb-3">
                <label for="id" class="form-label">Id Produk</label>
                <input type="text" class="form-control" name="id" id="id" autofocus>
                <div class="fst-italic text-danger">
                  <?= form_error('id'); ?>
                </div>
              </div>
              <div class="mb-3">
                <label for="nama" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" name="nama" id="nama">
                <div class="fst-italic text-danger">
                  <?= form_error('nama'); ?>
                </div>
              </div>
              <div class=" mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="text" class="form-control" name="harga" id="harga">
                <div class="fst-italic text-danger">
                  <?= form_error('harga'); ?>
                </div>
              </div>
              <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select name="kategori" id="kategori" class="form-control">
                  <option value="" selected disabled>Pilih Kategori</option>
                  <?php foreach ($kategori as $kategori) : ?>
                    <option value="<?= $kategori['id_kategori'] ?>"><?= $kategori['nama_kategori'] ?></option>
                  <?php endforeach ?>
                </select>
                <div class="fst-italic text-danger">
                  <?= form_error('kategori'); ?>
                </div>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control">
                  <option value="" selected disabled>Pilih Status</option>
                  <?php foreach ($status as $status) : ?>
                    <option value="<?= $status['id_status'] ?>"><?= $status['nama_status'] ?></option>
                  <?php endforeach ?>
                </select>
                <div class="fst-italic text-danger">
                  <?= form_error('status'); ?>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Simpan Data</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


</body>

</html>