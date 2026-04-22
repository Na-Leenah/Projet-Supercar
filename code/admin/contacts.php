<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
exigerAdmin();
$pdo = getPDO();

// Marquer comme lu
if (isset($_GET['lire']) && is_numeric($_GET['lire'])) {
    $pdo->prepare("UPDATE contacts SET lu=1 WHERE id=:id")->execute([':id'=>(int)$_GET['lire']]);
    rediriger('contacts.php');
}

// Supprimer
if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $pdo->prepare("DELETE FROM contacts WHERE id=:id")->execute([':id'=>(int)$_GET['supprimer']]);
    flashMessage('Message supprimé.','succes'); rediriger('contacts.php');
}

$filtre = isset($_GET['non_lus']) ? "WHERE lu = 0" : "";
$contacts = $pdo->query("SELECT * FROM contacts $filtre ORDER BY created_at DESC")->fetchAll();

$titrePage = 'Messages contact'; $adminPage = 'contacts';
require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="admin-content">
<div class="admin-page-title" style="display:flex;justify-content:space-between;align-items:center">
    <div><h1>Messages de contact</h1><p><?= count($contacts) ?> message(s)</p></div>
    <a href="contacts.php?non_lus=1" class="btn-admin-ghost">Non lus uniquement</a>
</div>
<div class="admin-table-card">
    <table class="admin-table">
        <thead><tr><th>De</th><th>Email</th><th>Sujet</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
        <?php if (empty($contacts)): ?><tr><td colspan="6" class="td-empty">Aucun message</td></tr>
        <?php else: foreach ($contacts as $c): ?>
        <tr class="<?= !$c['lu']?'tr-unread':'' ?>">
            <td><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></td>
            <td style="font-size:11px"><?= htmlspecialchars($c['email']) ?></td>
            <td><?= htmlspecialchars($c['sujet']?:'—') ?></td>
            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:11px;color:var(--muted)"><?= htmlspecialchars($c['message']) ?></td>
            <td><?= date('d/m/Y',strtotime($c['created_at'])) ?></td>
            <td style="display:flex;gap:6px">
                <?php if (!$c['lu']): ?><a href="contacts.php?lire=<?= $c['id'] ?>" class="btn-admin-edit">✓ Lu</a><?php endif; ?>
                <a href="contacts.php?supprimer=<?= $c['id'] ?>" class="btn-admin-danger" onclick="return confirm('Supprimer ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
</div>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
