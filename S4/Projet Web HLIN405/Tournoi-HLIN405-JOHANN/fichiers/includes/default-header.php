<header>
    Un header avec une liste OUI
    <ul class="admin-only">
    <?php
    for($i = 1; $i <= 10; ++$i) : ?>
        <li><?php echo $i; ?></li>
    <?php endfor; ?>
    </ul>
</header>