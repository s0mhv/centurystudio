<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kehadiran dengan Kalender</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
        .ui-datepicker {
            z-index: 1000;
        }
    </style>
</head>
<body>
    <h3>Form Kehadiran dengan Kalender</h3>
    <div class="container">
        <div class="button-container">
            <a href="index.php?page=kehadiran2_tambah"><input type="submit" name="input" value="TAMBAH DATA"></a>
        </div>
        <div class="button-container">
            <label for="datepicker">Pilih Tanggal:</label>
            <input type="text" id="datepicker" name="tanggal" readonly>
        </div>
        <div class="table-container">
            <form id="kehadiranForm">
                <table id="kehadiranTable">
                    <thead>
                        <tr>
                            <th width="15%">Nama</th>
                            <th width="35%">Status Kehadiran</th>
                            <th width="45%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            $("#datepicker").datepicker({
                onSelect: function(dateText) {
                    loadAttendanceData(dateText);
                }
            });

            $(document).on('change', '#kehadiranTable input[type=radio]', function() {
                autoSaveAttendance();
            });

            $(document).on('input', '#kehadiranTable input[type=text]', function() {
                autoSaveAttendance();
            });
        });

        function loadAttendanceData(date) {
            $.ajax({
                url: 'kehadiran2_proses.php',
                type: 'POST',
                data: { action: 'load', tanggal: date },
                success: function(response) {
                    console.log('Data loaded:', response); // Debugging response
                    $('#kehadiranTable tbody').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        function autoSaveAttendance() {
            var formData = $('#kehadiranForm').serialize();

            $.ajax({
                url: 'kehadiran2_proses.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log('Data saved:', response); // Debugging response
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
    </script>
</body>
</html>
