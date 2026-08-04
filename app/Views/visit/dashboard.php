<?php 
use App\Core\Config;
include __DIR__ . "/../partials/header.php"; 
?>

<div class="container mt-5 pt-5">
    <h2 class="mb-4">Mes Rendez-vous de Visite</h2>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs mb-4" id="visitTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tenant-tab" data-bs-toggle="tab" data-bs-target="#tenant" type="button" role="tab">Mes Demandes (Locataire)</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="owner-tab" data-bs-toggle="tab" data-bs-target="#owner" type="button" role="tab">Mes Annonces (Propriétaire)</button>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content" id="visitTabsContent">
        <!-- Tenant Tab -->
        <div class="tab-pane fade show active" id="tenant" role="tabpanel">
            <?php if (empty($visitsAsTenant)): ?>
                <div class="alert alert-info">Vous n'avez pas encore fait de demande de visite.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Annonce</th>
                                <th>Date & Heure</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($visitsAsTenant as $v): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo Config::url('annonce/show?id=' . $v['id_annonce']); ?>" class="text-decoration-none fw-bold">
                                            <?php echo htmlspecialchars($v['annonce_titre']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($v['date_visite'])); ?><br>
                                        <small class="text-muted"><?php echo $v['heure_debut']; ?> - <?php echo $v['heure_fin']; ?></small>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill <?php 
                                            echo match($v['statut']) {
                                                'en_attente' => 'bg-warning text-dark',
                                                'confirme' => 'bg-success',
                                                'refuse' => 'bg-danger',
                                                'annule' => 'bg-secondary',
                                                default => 'bg-info'
                                            };
                                        ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $v['statut'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($v['statut'] === 'confirme' || $v['statut'] === 'en_attente'): ?>
                                            <form action="<?php echo Config::url('visit/cancel'); ?>" method="POST" onsubmit="return confirm('Annuler ce rendez-vous ?');">
                                                <input type="hidden" name="id_visite" value="<?php echo $v['id_visite']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Annuler</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Owner Tab -->
        <div class="tab-pane fade" id="owner" role="tabpanel">
            <?php if (empty($visitsAsOwner)): ?>
                <div class="alert alert-info">Aucune demande de visite pour vos annonces.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Locataire</th>
                                <th>Annonce</th>
                                <th>Date & Heure</th>
                                <th>Statut</th>
                                <th>Décision</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($visitsAsOwner as $v): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($v['tenant_prenom'] . ' ' . $v['tenant_nom']); ?></td>
                                    <td>
                                        <a href="<?php echo Config::url('annonce/show?id=' . $v['id_annonce']); ?>" class="text-decoration-none fw-bold">
                                            <?php echo htmlspecialchars($v['annonce_titre']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($v['date_visite'])); ?><br>
                                        <small class="text-muted"><?php echo $v['heure_debut']; ?> - <?php echo $v['heure_fin']; ?></small>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill <?php 
                                            echo match($v['statut']) {
                                                'en_attente' => 'bg-warning text-dark',
                                                'confirme' => 'bg-success',
                                                'refuse' => 'bg-danger',
                                                'annule' => 'bg-secondary',
                                                default => 'bg-info'
                                            };
                                        ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $v['statut'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($v['statut'] === 'en_attente'): ?>
                                            <div class="btn-group btn-group-sm">
                                                <form action="<?php echo Config::url('visit/respond'); ?>" method="POST" class="me-1">
                                                    <input type="hidden" name="id_visite" value="<?php echo $v['id_visite']; ?>">
                                                    <input type="hidden" name="action" value="confirme">
                                                    <button type="submit" class="btn btn-success">Accepter</button>
                                                </form>
                                                <form action="<?php echo Config::url('visit/respond'); ?>" method="POST">
                                                    <input type="hidden" name="id_visite" value="<?php echo $v['id_visite']; ?>">
                                                    <input type="hidden" name="action" value="refuse">
                                                    <button type="submit" class="btn btn-danger">Refuser</button>
                                                </form>
                                            </div>
                                        <?php elseif ($v['statut'] === 'confirme'): ?>
                                            <form action="<?php echo Config::url('visit/cancel'); ?>" method="POST" onsubmit="return confirm('Annuler ce rendez-vous ?');">
                                                <input type="hidden" name="id_visite" value="<?php echo $v['id_visite']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Annuler</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../partials/footer.php"; ?>
