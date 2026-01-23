<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'États') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif;margin:24px;}
        table{border-collapse:collapse;width:100%;max-width:900px;}
        th,td{border:1px solid #ddd;padding:8px;text-align:left;}
        th{background:#f6f6f6;}
        .topbar{margin-bottom:16px;display:flex;gap:12px;align-items:center;}
        .flash{color:#b30000;margin:8px 0;}
        a.button{display:inline-block;padding:6px 10px;border:1px solid #ccc;border-radius:6px;text-decoration:none;}
    </style>
</head>
<body>
    <div class="topbar">
        <h1 style="margin:0;">Liste des Frais forfaits</h1>
        <a class="button" href="./dashboard">Dashboard</a>
        <a class="button" href="./logout">Se déconnecter</a>
    </div>

    <a class="button" href="./fraisHorsForfait/create">➕ Ajouter un frais forfait</a>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (empty($fraisHorsForfait)): ?>
        <p>Aucun état trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Libellé</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fraisHorsForfait as $frais): ?>
                    <tr>
                      <td><?= htmlspecialchars((string)$frais['id']) ?></td>
                        <td><?= htmlspecialchars((string)$frais['libelle']) ?></td>
                        <td><?= htmlspecialchars((string)$frais['montant']) ?></td>
                        <td><?= htmlspecialchars((string)$frais['date']) ?></td> 
                                     <td class="actions">
                            <a href="./fraisHorsForfait/<?= urlencode($frais['id']) ?>">Voir</a>
                            <a href="./fraisHorsForfait/<?= urlencode($frais['id']) ?>/edit">Modifier</a>
                             <form action="./fraisHorsForfait/<?= urlencode($frais['id']) ?>/delete"
                             method="post"
                             style="display:inline"
                             onsubmit="return confirm('Supprimer ce frais forfait ? Cette action est définitive.');">
                                <button type="submit">Supprimer

                                </button>
                            </form>
                            
                        </td>   
                      

</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>

                <!--    -->