<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Spending</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4 mb-5">
  <h3 class="text-center mb-3">Input Pengeluaran</h3>
  <a href="index.php?c=Transaction&m=index" class="btn btn-outline-secondary mb-3">&larr; Transaction</a>
  <form action="index.php?c=Spending&m=save" method="POST" id="spendingForm">
    <div class="mb-3">
      <label>Tanggal</label>
      <input type="date" name="tanggal" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Jumlah</label>
      <input type="text" name="jumlah" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Kategori</label>
      <input type="text" name="kategori" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Metode</label>
      <input type="text" name="metode" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Catatan</label>
      <textarea name="catatan" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-danger">Simpan</button>
  </form>
</div>

<script>
  const form = document.getElementById("spendingForm");
  const jumlahInput = form.querySelector("input[name='jumlah']");

  form.addEventListener("submit", function () {
    jumlahInput.value = jumlahInput.value.replace(/\./g, '');
  });

  jumlahInput.addEventListener("input", function () {
    let val = this.value.replace(/\D/g, '');
    this.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  });
</script>
</body>
</html>
