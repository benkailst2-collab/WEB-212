<?php
/**
 * app/Views/client/cinema/react_spa.php
 * View nhúng toàn bộ Single Page Application (React + Vite + Tailwind CSS) của ứng dụng Đặt Vé Xem Phim.
 * 
 * Cách dùng trong Controller (CodeIgniter / Laravel / Native PHP):
 *    return view('client/cinema/react_spa');
 */
$baseAssetsUrl = $baseAssetsUrl ?? '/cinema-spa/assets';
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MovieGo - Đặt Vé Xem Phim Trực Tuyến</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars($baseAssetsUrl) ?>/../favicon.svg" />
    <!-- Compiled Tailwind CSS & App Styles -->
    <link rel="stylesheet" crossorigin href="<?= htmlspecialchars($baseAssetsUrl) ?>/index-CYbFvEMK.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
      body {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
      }
    </style>
  </head>
  <body class="bg-gray-950 text-white min-h-screen">
    <div id="root"></div>
    <!-- Compiled React 19 Bundle (Toàn bộ Client Dark Mode + Admin Light Mode + Routing) -->
    <script type="module" crossorigin src="<?= htmlspecialchars($baseAssetsUrl) ?>/index-Bok_ww5F.js"></script>
  </body>
</html>
