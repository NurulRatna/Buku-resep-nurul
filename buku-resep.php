<?php 

include '../koneksi.php';

$cek_data = mysqli_query($koneksi, "SELECT id FROM resep LIMIT 1");
if (mysqli_num_rows($cek_data) == 0) {
    $resep_bawaan = [
        ["Cumi Sambal Pete", "Makanan Utama", "500g cumi segar, 2 papan pete kupas, 8 bawang merah, 4 bawang putih, 10 cabai merah keriting, garam, gula.", "1. Tumis bumbu halus.\n2. Masukkan pete.\n3. Masukkan cumi, masak cepat 3-5 menit dengan api besar."],
        ["Ayam Balado", "Makanan Utama", "1/2 kg ayam goreng setengah kering, 10 cabai merah keriting, 6 bawang merah, 2 bawang putih, 1 tomat merah.", "1. Ulek kasar bumbu balado.\n2. Tumis bumbu kasar hingga matang berminyak.\n3. Masukkan ayam goreng, aduk rata."],
        ["Ikan Kuah Kuning", "Makanan Utama", "500g ikan tongkol/kakap, 1 jeruk nipis, kemangi, 5 bawang merah, 3 bawang putih, 3cm kunyit, serai.", "1. Lumuri ikan dengan jeruk nipis.\n2. Tumis bumbu halus, serai, salam. Tuang air sampai mendidih.\n3. Masukkan ikan, masak hingga matang."],
        ["Nasi Goreng Kampung", "Makanan Utama", "2 piring nasi putih, 3 bawang putih, 5 bawang merah, 2 butir telur, kecap manis, garam, penyedap.", "1. Tumis bumbu halus dan telur orak-arik.\n2. Masukkan nasi dan kecap manis.\n3. Aduk rata hingga matang."],
        ["Kentang Keju Bite", "Cemilan", "10 lembar roti tawar tipis, 10 sosis, 5 lembar keju slice, 1 butir telur kocok, tepung roti.", "1. Gulung sosis dan keju di dalam roti tawar.\n2. Celup telur, balur tepung roti.\n3. Goreng hingga kuning keemasan."],
        ["Sosial Telur Gulung", "Cemilan", "3 butir telur ayam, 50 ml air putih, 1/2 sdt kaldu bubuk, sejumput merica, tusuk sate.", "1. Kocok telur, air, dan bumbu sampai encer.\n2. Tuang 2 sdm adonan ke minyak panas dari jarak tinggi.\n3. Gulung segera dengan tusuk sate."],
        ["Bola Bola Kentang", "Cemilan", "500g kentang haluskan, 50g keju parut, seledri, 1 butir telur kocok, tepung panir.", "1. Campur kentang, keju, seledri, bumbu. Bentuk bola.\n2. Celup telur, gulingkan ke tepung panir.\n3. Goreng hingga krispi."],
        ["Perkedel Kentang", "Cemilan", "500g kentang goreng haluskan, 2 sdm bawang goreng, seledri, 1 butir telur kocok pelapis.", "1. Campur kentang dengan bawang goreng and seledri.\n2. Bentuk bulat pipih.\n3. Celup kocokan telur, goreng dengan api sedang."],
        ["Jus Buah Naga", "Minuman", "1 buah naga merah, 2 sdm gula, 2 sdm susu kental manis, air dingin, es batu.", "1. Blender semua bahan dengan kecepatan tinggi sampai lembut.\n2. Tuang ke dalam gelas saji."],
        ["Jus Alpukat", "Minuman", "1 buah alpukat mentega, 2 sdm gula, 100ml air es, es batu, susu kental manis cokelat.", "1. Blender alpukat, gula, air es sampai kental.\n2. Hias dinding gelas dengan SKM cokelat.\n3. Tuang jus ke gelas."],
        ["Jus Mangga", "Minuman", "1 buah mangga manis, 2 sdm gula, 1 sdm susu kental manis putih, air, es batu.", "1. Blender daging buah mangga bersama gula, susu, air, es batu.\n2. Proses hingga lembut dan sajikan."]
    ];

    foreach ($resep_bawaan as $r) {
        $j = mysqli_real_escape_string($koneksi, $r[0]);
        $k = mysqli_real_escape_string($koneksi, $r[1]);
        $b = mysqli_real_escape_string($koneksi, $r[2]);
        $l = mysqli_real_escape_string($koneksi, $r[3]);
        mysqli_query($koneksi, "INSERT INTO resep (judul, kategori, bahan, langkah) VALUES ('$j', '$k', '$b', '$l')");
    }
    header("Location: buku-resep.php");
    exit;
}

// --- LOGIKA TAMBAH & UPDATE ---
if (isset($_POST['simpan'])) {
    $id       = isset($_POST['id']) ? trim($_POST['id']) : '';
    $judul    = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $kategori = $_POST['kategori'];
    $bahan    = mysqli_real_escape_string($koneksi, $_POST['bahan']);
    $langkah  = mysqli_real_escape_string($koneksi, $_POST['langkah']);

    if ($id === "" || empty($id)) { 
        $query = "INSERT INTO resep (judul, kategori, bahan, langkah) VALUES ('$judul', '$kategori', '$bahan', '$langkah')";
        mysqli_query($koneksi, $query);
    } 
    else { 
        $id_baku = intval($id);
        $query = "UPDATE resep SET judul='$judul', kategori='$kategori', bahan='$bahan', langkah='$langkah' WHERE id=$id_baku";
        mysqli_query($koneksi, $query);
    }
    header("Location: buku-resep.php");
    exit;
}

// --- LOGIKA HAPUS ---
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM resep WHERE id=$id");
    header("Location: buku-resep.php");
    exit;
}

// --- LOGIKA SEARCH  ---
$keyword = "";
if (isset($_POST['cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['keyword']);
    $sql = mysqli_query($koneksi, "SELECT * FROM resep WHERE judul LIKE '%$keyword%' OR bahan LIKE '%$keyword%' OR kategori LIKE '%$keyword%' ORDER BY id DESC");
} elseif (isset($_GET['klik_cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['klik_cari']);
    $sql = mysqli_query($koneksi, "SELECT * FROM resep WHERE judul LIKE '%$keyword%' OR bahan LIKE '%$keyword%' OR kategori LIKE '%$keyword%' ORDER BY id DESC");
} else {
    $sql = mysqli_query($koneksi, "SELECT * FROM resep ORDER BY id DESC");
}

$jumlah_data = mysqli_num_rows($sql);

// --- EDIT ---
$e_id = ""; $e_judul = ""; $e_kategori = ""; $e_bahan = ""; $e_langkah = "";
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit_data = mysqli_query($koneksi, "SELECT * FROM resep WHERE id=$id");
    if (mysqli_num_rows($edit_data) > 0) {
        $row_e = mysqli_fetch_array($edit_data);
        $e_id       = $row_e['id']; 
        $e_judul    = $row_e['judul']; 
        $e_kategori = $row_e['kategori']; 
        $e_bahan    = $row_e['bahan']; 
        $e_langkah  = $row_e['langkah'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Resep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #e67e22; }
        .btn-primary { background-color: #d35400; border: none; }
        .btn-primary:hover { background-color: #e67e22; }
        .card { border-radius: 15px; border: none; }
        .sticky-form { position: sticky; top: 20px; }
        .quick-tag { text-decoration: none; display: inline-block; margin-right: 4px; margin-bottom: 6px; font-size: 0.85rem; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark shadow-sm mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1"><i class="fas fa-utensils"></i> BUKU RESEP</span>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card p-4 shadow-sm sticky-form">
                <h5><i class="fas fa-plus-circle"></i> <?= ($e_id !== "") ? "Edit Resep" : "Tambah Resep"; ?></h5>
                <hr>
                <form method="POST" action="buku-resep.php">
                    <input type="hidden" name="id" value="<?= $e_id; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Masakan</label>
                        <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($e_judul); ?>" required placeholder="Contoh: Nasi Goreng">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="Makanan Utama" <?= ($e_kategori == "Makanan Utama") ? "selected" : ""; ?>>Makanan Utama</option>
                            <option value="Minuman" <?= ($e_kategori == "Minuman") ? "selected" : ""; ?>>Minuman</option>
                            <option value="Cemilan" <?= ($e_kategori == "Cemilan") ? "selected" : ""; ?>>Cemilan</option>
                        </select>
                        
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Bahan-bahan</label>
                        <textarea name="bahan" class="form-control" rows="3" required placeholder="Gunakan Enter untuk baris baru"><?= htmlspecialchars($e_bahan); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Cara Membuat</label>
                        <textarea name="langkah" class="form-control" rows="4" required placeholder="Langkah 1, 2, 3..."><?= htmlspecialchars($e_langkah); ?></textarea>
                    </div>
                    <button type="submit" name="simpan" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-save"></i> <?= ($e_id !== "") ? "Update Resep" : "Simpan Resep"; ?>
                    </button>
                    <?php if($e_id !== ""): ?>
                        <a href="buku-resep.php" class="btn btn-light w-100 mt-2">Batal</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <form method="POST" class="d-flex mb-2">
                <input type="text" name="keyword" class="form-control me-2 shadow-sm" placeholder="Cari resep atau bahan..." value="<?= htmlspecialchars($keyword); ?>">
                <button type="submit" name="cari" class="btn btn-dark"><i class="fas fa-search"></i></button>
            </form>

            <div class="mb-4 p-2 bg-white rounded shadow-sm">
                <div class="mb-1">
                    <span class="small text-muted fw-bold me-1">Lauk:</span>
                    <a href="buku-resep.php?klik_cari=Cumi" class="badge bg-secondary quick-tag text-white">Cumi Pete</a>
                    <a href="buku-resep.php?klik_cari=Balado" class="badge bg-secondary quick-tag text-white">Ayam Balado</a>
                    <a href="buku-resep.php?klik_cari=ikan" class="badge bg-secondary quick-tag text-white">Ikan Kuah Kuning</a>
                    <a href="buku-resep.php?klik_cari=Nasi" class="badge bg-secondary quick-tag text-white">Nasi Goreng</a>
                </div>
                <div class="mb-1">
                    <span class="small text-muted fw-bold me-1">Cemilan:</span>
                    <a href="buku-resep.php?klik_cari=Gulung" class="badge bg-info text-dark quick-tag">Telur Gulung</a>
                    <a href="buku-resep.php?klik_cari=Bola" class="badge bg-info text-dark quick-tag">Bola Kentang</a>
                    <a href="buku-resep.php?klik_cari=Perkedel" class="badge bg-info text-dark quick-tag">Perkedel</a>
                    <a href="buku-resep.php?klik_cari=Kentang" class="badge bg-info text-dark quick-tag">Kentang Keju</a>
                </div>
                <div>
                    <span class="small text-muted fw-bold me-1">Jus & Filter:</span>
                    <a href="buku-resep.php?klik_cari=Naga" class="badge bg-warning text-dark quick-tag">Jus Naga</a>
                    <a href="buku-resep.php?klik_cari=Alpukat" class="badge bg-warning text-dark quick-tag">Jus Alpukat</a>
                    <a href="buku-resep.php?klik_cari=Mangga" class="badge bg-warning text-dark quick-tag">Jus Mangga</a>
                    <a href="buku-resep.php" class="badge bg-dark quick-tag text-white">🔄 Semua Resep</a>
                </div>
            </div>

            <?php if ($keyword !== ""): ?>
                <div class="mb-3 d-flex justify-content-between align-items-center bg-light p-3 rounded border">
                    <div>
                        <span class="small text-muted">Hasil pencarian untuk: <strong>"<?= htmlspecialchars($keyword); ?>"</strong></span>
                        <span class="badge bg-primary ms-2"><?= $jumlah_data; ?> Ditemukan</span>
                    </div>
                    <a href="buku-resep.php" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-arrow-left"></i> Kembali ke Semua Resep
                    </a>
                </div>
            <?php endif; ?>

            <?php if($jumlah_data == 0): ?>
                <div class="alert alert-warning shadow-sm">
                    <i class="fas fa-exclamation-triangle"></i> Resep <strong>"<?= htmlspecialchars($keyword); ?>"</strong> tidak ditemukan. Kembali ke <a href="buku-resep.php" class="alert-link">Semua Resep</a>.
                </div>
            <?php endif; ?>

            <?php while($row = mysqli_fetch_array($sql)): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge bg-success text-white mb-2"><?= htmlspecialchars($row['kategori']); ?></span>
                                <h3 class="text-primary"><?= htmlspecialchars($row['judul']); ?></h3>
                            </div>
                            <div>
                                <a href="buku-resep.php?edit=<?= $row['id']; ?>" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                <a href="buku-resep.php?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus resep ini?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold"><i class="fas fa-shopping-basket text-success"></i> Bahan:</h6>
                                <p class="small text-muted"><?= nl2br(htmlspecialchars($row['bahan'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold"><i class="fas fa-list-ol text-success"></i> Langkah:</h6>
                                <p class="small text-muted"><?= nl2br(htmlspecialchars($row['langkah'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<footer class="text-center mt-5 p-4 text-muted">
    &copy; <?= date('Y'); ?> Buku Resep - Project UAS Nurul 
</footer>

</body>
</html>