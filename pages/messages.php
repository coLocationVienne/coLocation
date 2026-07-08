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
                           class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo htmlspecialchars($conv['prenom'] . ' ' . $conv['nom']); ?></h6>
                                <small><?php echo $conv['date_']; ?></small>
                            </div>
                            <p class="mb-1 text-truncate"><?php echo htmlspecialchars($conv['annonce_titre']); ?></p>
                            <small class="text-muted"><?php echo htmlspecialchars($conv['contenu']); ?></small>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-8">
            <?php if (isset($_GET['annonce_id']) && isset($_GET['with_user'])): 
                $chat = $messageDAO->getConversation($_SESSION['user_id'], $_GET['with_user'], $_GET['annonce_id']);
            ?>
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Conversation
                    </div>
                    <div class="card-body" style="height: 400px; overflow-y: auto;">
                        <?php foreach ($chat as $msg): ?>
                            <div class="mb-3 <?php echo $msg->getSenderId() == $_SESSION['user_id'] ? 'text-end' : ''; ?>">
                                <div class="d-inline-block p-2 rounded <?php echo $msg->getSenderId() == $_SESSION['user_id'] ? 'bg-primary text-white' : 'bg-light'; ?>" style="max-width: 75%;">
                                    <?php echo htmlspecialchars($msg->getContenu()); ?>
                                    <div style="font-size: 0.7rem;" class="mt-1 opacity-75">
                                        <?php echo $msg->getDate(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="card-footer">
                        <form action="be/send_message.php" method="POST">
                            <input type="hidden" name="id_annonce" value="<?php echo $_GET['annonce_id']; ?>">
                            <input type="hidden" name="id_receiver" value="<?php echo $_GET['with_user']; ?>">
                            <div class="input-group">
                                <input type="text" name="message" class="form-control" placeholder="Votre message..." required>
                                <button class="btn btn-primary" type="submit">Envoyer</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center mt-5">
                    <i class="fas fa-comments fa-4x text-light mb-3"></i>
                    <p class="text-muted">Sélectionnez une conversation pour commencer à discuter.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>
