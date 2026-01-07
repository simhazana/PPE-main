<?php $e = fn($s)=>htmlspecialchars((string)$s, ENT_QUOTES,'UTF-8'); ?>
<h2>Tableau de bord</h2>
<p>Bienvenue, <?= $e($username) ?>.</p>
<p><a href="logout">Se déconnecter</a></p>
