<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Budget</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        body {
            max-width: 480px;
            margin: 0 auto;
            background-color: #f8f9fa;
        }
        
        .header {
            padding: 15px 20px;
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
        }
        
        .budget-form {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .form-item {
            background-color: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .icon-container {
            width: 40px;
            height: 40px;
            background-color: #6c757d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 15px;
        }
        
        .form-content {
            flex: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .currency-input {
            background-color: #f1f1f1;
            border: none;
            padding: 2px 8px;
            border-radius: 4px;
            width: 40px;
            text-align: center;
            margin-right: 10px;
        }
        
        .amount-input {
            border: none;
            font-size: 1.1rem;
            width: 100%;
            outline: none;
        }
        
        .save-btn {
            background-color: #e9ecef;
            color: #212529;
            border: none;
            border-radius: 8px;
            padding: 15px;
            width: 100%;
            font-weight: bold;
            margin-top: 20px;
        }
        
        .toggle-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .toggle-text {
            flex: 1;
        }
        
        .form-check-input {
            width: 40px;
            height: 22px;
        }
    </style>
</head>

<body data-base-url="<?php echo BASE_URL; ?>">
    <div class="container-fluid p-0">
        <div class="header">
            <a href="<?php echo BASE_URL; ?>budget/index" class="text-decoration-none text-dark"><i class="bi bi-arrow-left"></i> Batal</a>
            <h5 class="mb-0"><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Tambah Budget'; ?></h5>
            <div style="width: 40px;"></div>
        </div>
        <div class="container mt-1">
            <div class="budget-form">
                <div class="form-item">
                    <div class="icon-container"><i class="bi bi-tag-fill"></i></div>
                    <div class="form-content">
                        <a href="<?php echo BASE_URL; ?>budget/selectCategory" class="text-decoration-none text-dark w-100 d-flex justify-content-between align-items-center">
                            <span id="selectedCategoryText" class="text-muted">Pilih Kategori</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
                <div class="form-item">
                    <div class="icon-container"><i class="bi bi-cash-coin"></i></div>
                    <div class="amount-input-container">
                        <span class="currency-input">IDR</span>
                        <input type="text" class="amount-input" id="budgetAmount" placeholder="0" inputmode="numeric">
                    </div>
                </div>
                <div class="form-item">
                    <div class="icon-container"><i class="bi bi-calendar-event-fill"></i></div>
                    <input type="text" id="selectedPeriodText" placeholder="Periode (misal: Juni 2025)" class="form-control form-control-sm inline-input">
                </div>
                <div class="form-item">
                    <div class="icon-container"><i class="bi bi-wallet-fill"></i></div>
                    <input type="text" id="selectedAccountText" placeholder="Akun (misal: Dompet Utama)" class="form-control form-control-sm inline-input">
                </div>
            </div>
            <div class="toggle-container">
                <div class="toggle-text"><p class="mb-0 fw-bold">Ulangi budget ini</p><small class="text-muted">Budget akan diperbarui otomatis</small></div>
                <div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="repeatToggle" style="transform: scale(1.3);"></div>
            </div>
            <div class="px-3"><button class="save-btn" id="saveBudgetButton">Simpan</button></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>view/js/model.js"></script>
    <script src="<?php echo BASE_URL; ?>view/js/controller.js"></script>
</body>
</html>