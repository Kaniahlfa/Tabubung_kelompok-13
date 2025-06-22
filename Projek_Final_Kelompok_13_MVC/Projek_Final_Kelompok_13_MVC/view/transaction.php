<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <title>Transaction</title>
</head>
<body>
<div class="container mt-4 mb-5">
  <h3 class="text-center">Ringkasan Transaksi</h3>

  <?php
    $totalIncome = 0;
    $totalSpending = 0;
    foreach ($data as $item) {
      if ($item['tipe'] === 'Income') {
        $totalIncome += $item['jumlah'];
      } else {
        $totalSpending += $item['jumlah'];
      }
    }
    $saldoAkhir = $totalIncome - $totalSpending;
  ?>

  <div class="row text-center mt-4">
    <div class="col-md-4">
      <div class="card border-success">
        <div class="card-header bg-success text-white">Total Income</div>
        <div class="card-body">
          <h5 class="card-title">Rp <?= number_format($totalIncome, 0, ',', '.') ?></h5>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-danger">
        <div class="card-header bg-danger text-white">Total Spending</div>
        <div class="card-body">
          <h5 class="card-title">Rp <?= number_format($totalSpending, 0, ',', '.') ?></h5>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-primary">
        <div class="card-header bg-primary text-white">Saldo Saat Ini</div>
        <div class="card-body">
          <h5 class="card-title">Rp <?= number_format($saldoAkhir, 0, ',', '.') ?></h5>
        </div>
      </div>
    </div>
  </div>

  <h5 class="mt-5">Riwayat Transaksi</h5>
  <?php foreach ($data as $item): ?>
    <div class="card my-2">
      <div class="card-body">
        <strong><?= $item['tanggal'] ?></strong><br>
        Kategori: <?= $item['kategori'] ?><br>
        Metode: <?= $item['metode'] ?><br>
        Jumlah: Rp <?= number_format($item['jumlah'], 0, ',', '.') ?><br>
        Tipe: <?= $item['tipe'] ?>
      </div>
    </div>
  <?php endforeach; ?>

  <div class="text-center mt-4">
    <a href="index.php?c=Income&m=input" class="btn btn-success px-4">Income</a>
    <a href="index.php?c=Spending&m=input" class="btn btn-danger px-4">Spending</a>
  </div>

  <div class="text-center mt-3">
    <form action="index.php?c=Transaction&m=deleteAll" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua data?')">
      <button type="submit" class="btn btn-outline-danger">Hapus Semua Data</button>
    </form>
  </div>
</div>
</body>
</html>
