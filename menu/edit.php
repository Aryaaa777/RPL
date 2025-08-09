<?php
    session_start();
    require_once "../assets/API/config.php";

    // Cek login
    if (! isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit();
    }

    if (! isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: menu.php");
        exit();
    }

    $id = intval($_GET['id']);

    // Ambil data menu
    $qMenu = $conn->query("SELECT * FROM menu WHERE id_menu = $id");
    if ($qMenu->num_rows == 0) {
        header("Location: menu.php");
        exit();
    }
    $menu = $qMenu->fetch_assoc();

    if (isset($_POST['update'])) {
        $nama     = htmlspecialchars($_POST['nama_menu']);
        $kategori = htmlspecialchars($_POST['kategori']);
        $harga    = intval($_POST['harga']);

        $update = $conn->query("UPDATE menu SET nama_menu='$nama', kategori='$kategori', harga=$harga WHERE id_menu=$id");
        if ($update) {
            header("Location: menu.php");
            exit();
        } else {
            $error = "Gagal mengupdate menu!";
        }
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu - SI-RERE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .content {
            padding: 20px;
            color: white;
        }
        .card {
            background: #1e1e1e;
            border-radius: 12px;
            padding: 20px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .card h2 {
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: bold;
        }
        label {
            font-size: 14px;
            font-weight: 600;
            margin-top: 10px;
            display: block;
        }
        input, select {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #555;
            background: #2a2a2a;
            color: white;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 10px 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
        }
        .btn-save {
            background: #3b82f6;
            color: white;
        }
        .btn-save:hover {
            background: #2563eb;
        }
        .btn-back {
            background: #6b7280;
            color: white;
            margin-left: 10px;
        }
        .btn-back:hover {
            background: #4b5563;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="card">
            <h2><i class="fas fa-edit"></i> Edit Menu</h2>
            <?php if (! empty($error)): ?>
                <p style="color: red;"><?php echo $error;?></p>
            <?php endif; ?>
            <form method="post">
                <label>Nama Menu</label>
                <input type="text" name="nama_menu" value="<?php echo htmlspecialchars($menu['nama_menu']);?>" required>

                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="Makanan" <?php echo ($menu['kategori'] == 'Makanan') ? 'selected' : '';?>>Makanan</option>
                    <option value="Minuman" <?php echo ($menu['kategori'] == 'Minuman') ? 'selected' : '';?>>Minuman</option>
                </select>

                <label>Harga</label>
                <input type="number" name="harga" value="<?php echo $menu['harga'];?>" required>

                <br><br>
                <button type="submit" name="update" class="btn btn-save"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="menu.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
            </form>
        </div>
    </div>
</body>
</html>
