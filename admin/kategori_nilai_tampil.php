<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kategori Nilai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }
        h3 {
            text-align: center;
            color: #444;
        }
        .container {
            width: 90%;
            margin: 0 auto;
        }
        .button-container {
            text-align: left;
            margin: 20px 0;
        }
        .button-container input {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .button-container input:hover {
            background-color: #45a049;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #228B22;
            color: white;
        }
        table tr:hover {
            background-color: #f5f5f5;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: #0066cc;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h3>DATA Kategori Nilai</h3>
    <div class="container">
        <div class="button-container">
            <a href="index.php?page=kategori_nilai_tambah"><input type="submit" name="input" value="TAMBAH DATA"></a>
        </div>
        <div class="table-container">
            <table>
                <tr>
                    <th width="5%">NO.</th>
                    <th width="10%">Kategori Nilai</th>
                    <th width="10%" colspan="2">AKSI</th>
                </tr>
                <?php
                    include "../config/koneksi.php";
                    $no = 1;
                    $tampil_kategori_nilai = mysqli_query($koneksi, "SELECT * FROM kategori_nilai ORDER BY id_kategori_nilai");
                    while ($hasil = mysqli_fetch_array($tampil_kategori_nilai)) {
                        echo "<tr>";
                        echo "<td align='center'>$no</td>";
                        echo "<td>$hasil[kategori_nilai]</td>";
                        echo "<td align='center'><a href='index.php?page=kategori_nilai_tambah&id_kategori_nilai=$hasil[id_kategori_nilai]'>EDIT</a></td>";
                        echo "<td align='center'><a href='#' onclick=\"if (confirm('Apakah anda yakin data dihapus ?')) {window.location.href='kategori_nilai_proses.php?status=hapus&id_kategori_nilai=$hasil[id_kategori_nilai]';}\">HAPUS</a></td>";
                        echo "</tr>";
                        $no++;
                    }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
