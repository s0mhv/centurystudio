<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form PAGE</title>
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
            background-color: #555;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .button-container input:hover {
            background-color: #777;
        }
        .table-container {
            max-height: 600px; /* Adjust height */
            overflow-y: auto;
            position: relative;
            border: 1px solid #ddd;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            min-width: 600px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #555;
            color: white;
            position: sticky; /* Makes the header fixed */
            top: 0; /* Sticks to the top */
            z-index: 1;
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
    <h3>DATA PAGE</h3>
    <div class="container">
        <div class="button-container">
            <a href="index.php?page=page_tambah"><input type="submit" name="input" value="TAMBAH DATA"></a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="5%">NO.</th>
                        <th width="10%">Nama menu</th>
                        <th width="10%">Link</th>
                        <th width="10%" colspan="2">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include "../config/koneksi.php";
                        $no = 1;
                        $tampil_page = mysqli_query($koneksi, "SELECT * FROM page ORDER BY nama_menu ASC");
                        while ($hasil = mysqli_fetch_array($tampil_page)) {
                            echo "<tr>";
                            echo "<td>$no</td>";
                            echo "<td>$hasil[nama_menu]</td>";
                            echo "<td><a href='$hasil[link]'>$hasil[link]</a></td>";
                            echo "<td><a href='index.php?page=page_tambah&id_page=$hasil[id_page]'>EDIT</a></td>";
                            echo "<td><a href='#' onclick=\"if (confirm('Apakah anda yakin data dihapus ?')) {window.location.href='page_proses.php?status=hapus&id_page=$hasil[id_page]';}\">HAPUS</a></td>";
                            echo "</tr>";
                            $no++;
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
