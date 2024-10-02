<?php
        // Check if page parameter is set
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            
            // Sanitize page input to prevent directory traversal
            $page = preg_replace('/[^a-zA-Z0-9_]/', '', $page);
            
            // Ensure that the page file exists before including it
            $file = $page . ".php";
            if (file_exists($file)) {
                include $file;
            } else {
                echo "Halaman tidak tersedia.";
            }
        } else {
            echo "Selamat datang di halaman data magang.";
        }
        ?>