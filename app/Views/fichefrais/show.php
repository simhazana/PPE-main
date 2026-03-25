<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'Fiche Frais') ?></title>
    <style>
        body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif;margin:24px;}
        .card{border:1px solid #ddd;padding:16px;border-radius:8px;max-width:400px;}
        a.button{display:inline-block;margin-top:12px;padding:6px 10px;border:1px solid #ccc;border-radius:6px;text-decoration:none;}
        .flash{color:#b30000;margin-bottom:10px;}
    </style>
</head>
<body>
    <h1>Détail de la fiche frais</h1>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (!empty($ficheFrais)): ?>
        <div class="card">
            <p><strong>IDVisiteur:</strong> <?= htmlspecialchars($ficheFrais['idVisiteur']) ?></p>
            <p><strong>Mois :</strong> <?= htmlspecialchars($ficheFrais['mois']) ?></p>
            <p><strong>nbrJustificatifs :</strong> <?= htmlspecialchars($ficheFrais['nbrJustificatifs']) ?></p>
            <p><strong>montantValide :</strong> <?= htmlspecialchars($ficheFrais['montantValide']) ?></p>
            <p><strong>dateModif :</strong> <?= htmlspecialchars($ficheFrais['dateModif']) ?></p>
            <p><strong>idLigneFraisHorsForfait :</strong> <?= htmlspecialchars($ficheFrais['idLigneFraisHorsForfait']) ?></p>
            <p><strong>idEtat :</strong> <?= htmlspecialchars($ficheFrais['idEtat']) ?></p>
         
        </div>
        <a class="button" href="../fichefrais">⬅ Retour à la liste</a>
    <?php else: ?>
        <p>Fiche Frais introuvable.</p>
        <a class="button" href="../fichefrais">Retour à la liste</a>
    <?php endif; ?>
</body>
</html>
