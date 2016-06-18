<?php
require_once '../class.auchan.php';
$auchan = new auchan();
?>
<h1>Auchan Shops in Poland</h1>
<ul>
    <?php foreach ($auchan->getShops() as $url => $shopName): ?>
        <li>
            <a href="?shopUrl=<?= $url; ?>"><?= $shopName ?></a>
            <?php
            if (isset($_GET['shopUrl']) && $url == $_GET['shopUrl']):

                echo '<p><pre>' . print_r(($price = $auchan->getPrice($url)) ? $price : 'No gas station.', true) . '</pre></p>';

            endif;
            ?>
        </li>
    <?php endforeach; ?>
</ul>
