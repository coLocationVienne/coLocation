<?php
require_once "../includes/header.php";

if (empty($_SESSION['isLoggedin'])) {
    header("Location: connexion.php");
    exit();
}

$conversations = $messageDAO->getUserConversations($_SESSION['user_id']);
?>

<div class="container mt-5 pt-5">
    <h2 class="mb-4">Mes Messages</h2>
    
    <div class="row">

        <div class="col-md-4">
            <div class="list-group">
                <?php if (empty($conversations)): ?>
                    <p class="text-muted">Aucune conversation pour le moment.</p>
                <?php else: ?>
                    <?php foreach ($conversations as $conv): ?>
                        <a href="?annonce_id=<?php echo $conv['id_annonce']; ?>&with_user=<?php echo $conv['id_utilisateur'] == $_SESSION['user_id'] ? $conv['id_utilisateur_1'] : $conv['id_utilisateur']; ?>" 
                           class="list-group-item list-group-item-action <?php echo (isset($_GET['annonce_id']) && $_GET['annonce_id'] == $conv['id_annonce'] && (isset($_GET['with_user']) && ($_GET['with_user'] == $conv['id_utilisateur'] || $_GET['with_user'] == $conv['id_utilisateur_1']))) ? 'active' : ''; ?>">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo htmlspecialchars($conv['prenom'] . ' ' . $conv['nom']); ?></h6>
                                <small><?php echo date('d/m H:i', strtotime($conv['date_'])); ?></small>
                            </div>
                            <p class="mb-1 text-truncate"><strong><?php echo htmlspecialchars($conv['annonce_titre']); ?></strong></p>
                            <small class="<?php echo (isset($_GET['annonce_id']) && $_GET['annonce_id'] == $conv['id_annonce']) ? 'text-white' : 'text-muted'; ?> text-truncate d-block">
                                <?php echo htmlspecialchars($conv['contenu']); ?>
                            </small>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Chat Window -->
        <div class="col-md-8">
            <?php if (isset($_GET['annonce_id']) && isset($_GET['with_user'])): 
                $chat = $messageDAO->getConversation($_SESSION['user_id'], $_GET['with_user'], $_GET['annonce_id']);
                $currentAnnonce = $annonceDAO->getById((int)$_GET['annonce_id']);
                $otherUser = $userDAO->getById((int)$_GET['with_user']);
            ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-user-circle me-2"></i>
                            <strong><?php echo htmlspecialchars($otherUser->getPrenom() . ' ' . $otherUser->getNom()); ?></strong>
                            <br>
                            <small>
                                À propos de : 
                                <a href="voir_annonce.php?id=<?php echo $currentAnnonce->getId(); ?>" class="text-white text-decoration-underline">
                                    <?php echo htmlspecialchars($currentAnnonce->getTitre()); ?>
                                <sup><i class="fas fa-external-link-alt fa-xs"></i></sup>
                                </a>
                            </small>
                        </div>
                    </div>
                    <div class="card-body" style="height: 450px; overflow-y: auto; background-color: #f0f2f5;">
                        <?php if (empty($chat)): ?>
                            <div class="text-center mt-5 text-muted">
                                <p>Commencez la conversation pour cette annonce.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($chat as $msg): ?>
                                <div class="mb-3 <?php echo $msg->getSenderId() == $_SESSION['user_id'] ? 'text-end' : ''; ?>">
                                    <div class="d-inline-block p-2 px-3 rounded-pill <?php echo $msg->getSenderId() == $_SESSION['user_id'] ? 'bg-primary text-white' : 'bg-white border'; ?>" style="max-width: 80%; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                                        <?php echo htmlspecialchars($msg->getContenu()); ?>
                                        <div style="font-size: 0.65rem;" class="mt-1 <?php echo $msg->getSenderId() == $_SESSION['user_id'] ? 'text-white-50' : 'text-muted'; ?>">
                                            <?php echo date('H:i', strtotime($msg->getDate())); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-white">
                        <form action="be/send_message.php" method="POST">
                            <input type="hidden" name="id_annonce" value="<?php echo $_GET['annonce_id']; ?>">
                            <input type="hidden" name="id_receiver" value="<?php echo $_GET['with_user']; ?>">
                            <div class="input-group">
                                <input type="text" name="message" class="form-control border-0 bg-light" placeholder="Votre message..." required autofocus autocomplete="off">
                                <button class="btn btn-primary px-4" type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center mt-5 py-5 bg-light rounded border">
                    <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                    <h4>Vos conversations</h4>
                    <p class="text-muted">Sélectionnez une discussion à gauche pour voir les messages.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>
