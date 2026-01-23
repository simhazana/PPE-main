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
        .actions a{margin-right:6px;}
        .actions form { display:inline; margin:0; }
        .actions button { border:1px solid #ccc; border-radius:6px; padding:4px 8px; background:#fff; cursor:pointer; }

    </style>
</head>
<body>
    <div class="topbar">
        <h1 style="margin:0;">Liste des Visiteurs</h1>
        <a class="button" href="/dashboard">Dashboard</a>
        <a class="button" href="/logout">Se déconnecter</a>
        <a class="button" href="/fraisForfait">Frais forfait</a>
    </div>

    <a class="button" href="./visiteur/create">➕ Ajouter un état</a>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (empty($visiteur)): ?>
        <p>Aucun visiteur trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Adresse</th>
                    <th>Ville</th>
                    <th>Cp</th>
                    <th>Date d'embauche</th>
                    <th>Login</th>
                    <th>Mdp</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($visiteur as $vis): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$vis['id']) ?></td>
                        <td><?= htmlspecialchars((string)$vis['Nom']) ?></td> <!--a changer sur tt la liste-->
                        <td><?= htmlspecialchars((string)$vis['Prenom']) ?></td>
                        <td><?= htmlspecialchars((string)$vis['Adresse']) ?></td>
                        <td><?= htmlspecialchars((string)$vis['Ville']) ?></td>
                        <td><?= htmlspecialchars((string)$vis['Cp']) ?></td>
                        <td><?= htmlspecialchars((string)$vis['Date_embauche']) ?></td>
                        <td><?= htmlspecialchars((string)$vis['Login']) ?></td>
                        <td><?= htmlspecialchars((string)$vis['Mdp']) ?></td>
                        <td class="actions">
                            <a href="./visiteur/<?= urlencode($vis['id']) ?>">Voir</a>
                            <a href="./visiteur/<?= urlencode($vis['id']) ?>/edit">Modifier</a>
                             <form action="./visiteur/<?= urlencode($vis['id']) ?>/delete"
                             method="post"
                             style="display:inline"
                             onsubmit="return confirm('Supprimer ce visiteur ? Cette action est définitive.');">
                                <button type="submit">Supprimer</button>
                            </form>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
