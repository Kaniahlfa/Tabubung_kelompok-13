<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Pilih Kategori'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        body { max-width: 480px; margin: 0 auto; background-color: #f8f9fa; }
        .header {
            padding: 15px 20px; background-color: white; display: flex; justify-content: space-between;
            align-items: center; border-bottom: 1px solid #ddd; position: sticky; top: 0; z-index: 1020;
        }
        .category-list-container { 
            padding-top: 75px; /* Sesuaikan dengan tinggi header Anda */
            padding-left: 15px; padding-right: 15px; padding-bottom: 15px; 
        }
        .category-item, .new-category-trigger {
            background-color: white; padding: 15px; margin-bottom: 10px; border-radius: 8px;
            display: flex; align-items: center; text-decoration: none; color: inherit;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: background-color 0.2s ease-in-out; cursor: pointer;
        }
        .category-item:hover, .new-category-trigger:hover { background-color: #e9ecef; }
        .category-icon {
            width: 40px; height: 40px; background-color: #6c757d; border-radius: 50%; display: flex;
            align-items: center; justify-content: center; color: white; margin-right: 15px; flex-shrink: 0;
        }
        .category-name { flex: 1; font-weight: 500; }
        .back-btn {
            display: flex; align-items: center; text-decoration: none;
            color: #212529; font-weight: 500; min-width: 120px; /* Agar judul bisa ditengah */
        }
        .back-btn .bi-chevron-left { font-size: 1.2rem; }
        .header h5 { text-align: center; flex-grow: 1; }
        .header-spacer { min-width: 120px; /* Sama dengan min-width back-btn */ visibility: hidden; }

        #newCategoryInputForm {
            background-color: #f8f9fa; padding: 15px; margin-bottom: 15px; border-radius: 8px;
            /* border: 1px solid #dee2e6; */ /* Opsional border */
        }
        #newCategoryInput { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="header">
            <a href="<?php echo isset($addBudgetPageUrl) ? htmlspecialchars($addBudgetPageUrl) : (defined('BASE_URL') ? BASE_URL.'budget/add' : '#'); ?>" class="back-btn">
                <i class="bi bi-chevron-left me-1"></i>
                <span>Tambah Budget</span>
            </a>
            <h5 class="mb-0"><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Pilih Kategori'; ?></h5>
            <div class="header-spacer"></div>
        </div>

        <div class="category-list-container">
            <div id="newCategoryTrigger" class="new-category-trigger">
                <div class="category-icon"><i class="bi bi-plus-lg"></i></div> <span class="category-name">Ketik Kategori Baru...</span>
                <i class="bi bi-pencil-fill ms-auto text-muted"></i>
            </div>

            <div id="newCategoryInputForm" style="display: none;" class="mt-2">
                <div class="mb-2">
                    <input type="text" id="newCategoryNameInput" class="form-control" placeholder="Masukkan nama kategori baru">
                </div>
                <button id="useNewCategoryButton" class="btn btn-primary btn-sm w-100">Gunakan Kategori Ini</button>
            </div>

            <hr id="categorySeparatorLine" style="display: none;" class="my-3">

            <h6 class="mt-3 mb-2 text-muted" id="existingCategoriesHeader" <?php echo !(isset($existingCategoriesData) && !empty($existingCategoriesData)) ? 'style="display:none;"' : ''; ?>>
                Atau pilih dari yang sudah ada:
            </h6>
            <?php if (isset($existingCategoriesData) && !empty($existingCategoriesData)): ?>
                <?php foreach ($existingCategoriesData as $category): ?>
                    <?php
                        $baseLink = isset($addBudgetPageUrl) ? htmlspecialchars($addBudgetPageUrl) : (defined('BASE_URL') ? BASE_URL.'budget/add' : '#');
                        $categoryId = isset($category['id']) ? htmlspecialchars($category['id']) : '';
                        $categoryName = isset($category['name']) ? urlencode($category['name']) : '';
                        $linkTarget = $baseLink . '?category_id=' . $categoryId . '&category_name=' . $categoryName;
                    ?>
                    <a href="<?php echo $linkTarget; ?>" class="category-item"
                       data-category-id="<?php echo $categoryId; ?>"
                       data-category-name="<?php echo isset($category['name']) ? htmlspecialchars($category['name']) : ''; ?>">
                        <div class="category-icon">
                            <i class="bi <?php echo isset($category['icon']) ? htmlspecialchars($category['icon']) : 'bi-tag-fill'; ?>"></i>
                        </div>
                        <span class="category-name"><?php echo isset($category['name']) ? htmlspecialchars($category['name']) : 'Kategori'; ?></span>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newCategoryTriggerEl = document.getElementById('newCategoryTrigger');
            const newCategoryInputFormEl = document.getElementById('newCategoryInputForm');
            const newCategoryNameInputEl = document.getElementById('newCategoryNameInput');
            const useNewCategoryButtonEl = document.getElementById('useNewCategoryButton');
            const categorySeparatorLineEl = document.getElementById('categorySeparatorLine');
            const addBudgetPageUrlJs = "<?php echo isset($addBudgetPageUrl) ? htmlspecialchars($addBudgetPageUrl) : (defined('BASE_URL') ? BASE_URL.'budget/add' : '#'); ?>";

            if (newCategoryTriggerEl) {
                newCategoryTriggerEl.addEventListener('click', function() {
                    if (newCategoryInputFormEl.style.display === 'none') {
                        newCategoryInputFormEl.style.display = 'block';
                        categorySeparatorLineEl.style.display = 'block'; // Tampilkan pemisah
                        newCategoryNameInputEl.focus();
                    } else {
                        newCategoryInputFormEl.style.display = 'none';
                        categorySeparatorLineEl.style.display = 'none'; // Sembunyikan pemisah
                    }
                });
            }

            if (useNewCategoryButtonEl) {
                useNewCategoryButtonEl.addEventListener('click', function() {
                    const newCategoryName = newCategoryNameInputEl.value.trim();
                    if (newCategoryName) {
                        // ID bisa dikosongkan, atau diberi nilai khusus seperti 'manual_input'
                        // Ini akan dikirim sebagai parameter ke halaman add-budget
                        const targetUrl = addBudgetPageUrlJs +
                                          '?category_id=typed_new' + // ID untuk menandakan ini input manual
                                          '&category_name=' + encodeURIComponent(newCategoryName);
                        window.location.href = targetUrl;
                    } else {
                        alert('Nama kategori baru tidak boleh kosong.');
                        newCategoryNameInputEl.focus();
                    }
                });
            }
            
            // Untuk item kategori yang sudah ada, link href akan langsung bekerja.
            // Jika ingin menangani semuanya dengan JS (misalnya untuk efek transisi halaman atau state management):
            /*
            document.querySelectorAll('.category-item').forEach(item => {
                item.addEventListener('click', function(event) {
                    event.preventDefault(); // Hentikan navigasi default jika ditangani JS
                    const categoryId = this.dataset.categoryId;
                    const categoryName = this.dataset.categoryName;
                    
                    // Simpan ke localStorage jika diperlukan untuk diambil di halaman add-budget
                    // localStorage.setItem('selectedCategoryId', categoryId);
                    // localStorage.setItem('selectedCategoryName', categoryName);
                    
                    // Atau langsung redirect dengan parameter
                    const targetUrl = addBudgetPageUrlJs +
                                      '?category_id=' + encodeURIComponent(categoryId) +
                                      '&category_name=' + encodeURIComponent(categoryName);
                    window.location.href = targetUrl;
                });
            });
            */
        });
    </script>
</body>
</html>