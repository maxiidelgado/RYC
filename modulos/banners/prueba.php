<?php
require '../../database/db.php';

$query = $pdo->query("SELECT * FROM Banner where link_url = 'maxi' ");
$banners = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="banners">
    <?php foreach ($banners as $banner): ?>
        <div class="banner-item">
            <img src="<?= htmlspecialchars($banner['imagen_url']) ?>" alt="Banner">
        </div>
    <?php endforeach; ?>
</div>
