<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
exigerAdmin();
$pdo = getPDO();

if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $id = (int)$_GET['supprimer'];
    if ($id !== idConnecte()) {
        $pdo->prepare("DELETE FROM utilisateurs WHERE id=:id AND role='client'")->execute([':id'=>$id]);
        flashMessage('Utilisateur supprimé.','succes');
    }
    rediriger('utilisateurs.php');
}

$search = nettoyer($_GET['search'] ?? '');
$params = [];
$where  = "WHERE 1=1";
if ($search) { $where .= " AND (prenom LIKE :s OR nom LIKE :s OR email LIKE :s)"; $params[':s'] = '%'.$search.'%'; }

$stmt = $pdo->prepare("SELECT id,prenom,nom,email,telephone,role,created_at FROM utilisateurs $where ORDER BY created_at DESC");
$stmt->execute($params);
$utilisateurs = $stmt->fetchAll();

$titrePage = 'Utilisateurs'; $adminPage = 'utilisateurs';
require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="admin-content">
<div class="admin-page-title"><h1>Utilisateurs</h1><p><?= count($utilisateurs) ?> compte(s)</p></div>
<div class="admin-toolbar">
    <input type="text" class="admin-search" placeholder="Rechercher nom, email..."
           value="<?= htmlspecialchars($search) ?>"
           oninput="rechercherUser(this.value)">
</div>
<div class="admin-table-card">
    <table class="admin-table">
        <thead><tr><th>Nom</th><th>Email</th><th>Téléphone</th><th>Rôle</th><th>Inscrit le</th><th>Action</th></tr></thead>
        <tbody>
        <?php if (empty($utilisateurs)): ?><tr><td colspan="6" class="td-empty">Aucun utilisateur</td></tr>
        <?php else: foreach ($utilisateurs as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?></td>
            <td style="font-size:11px"><?= htmlspecialchars($u['email']) ?></td>
            <td style="font-size:11px"><?= htmlspecialchars($u['telephone']?:'—') ?></td>
            <td><span class="badge-statut <?= $u['role']==='admin'?'statut-confirmé':'statut-en-attente' ?>"><?= $u['role'] ?></span></td>
            <td><?= date('d/m/Y',strtotime($u['created_at'])) ?></td>
            <td>
                <?php if ($u['role'] !== 'admin' && $u['id'] !== idConnecte()): ?>
                <a href="utilisateurs.php?supprimer=<?= $u['id'] ?>" class="btn-admin-danger" onclick="return confirm('Supprimer ce compte ?')">Supprimer</a>
                <?php else: ?>
                <span style="font-size:11px;color:var(--muted)">Protégé</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
</div>
<script>
let t;
function rechercherUser(v){clearTimeout(t);t=setTimeout(()=>{const p=new URLSearchParams(window.location.search);v?p.set('search',v):p.delete('search');window.location.search=p.toString();},500);}
</script>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
