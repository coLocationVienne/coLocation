<?php 
require_once "../init.php";

$adminUser = $userDAO->getById((int)$_SESSION['user_id']);

if (($adminUser->getIdRole() !== null) && $adminUser->getIdRole() === 1): ?>
    <section class="admin-section mt-5">
        <div class="admin-header">
            <h2>Gestion des utilisateurs</h2>
            <div class="admin-actions">
                <button class="btn btn-primary" onclick="refreshUserList()">
                    <i class="fa-solid fa-refresh"></i> Rafraîchir
                </button>
            </div>
        </div>

        <div class="admin-filters mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" id="userSearch" class="form-control" placeholder="Rechercher un utilisateur..." onkeyup="filterUsers()">
                </div>
                <div class="col-md-3">
                    <select id="roleFilter" class="form-control" onchange="filterUsers()">
                        <option value="">Tous les rôles</option>
                        <option value="Admin">Admin</option>
                        <option value="Propriétaire">Propriétaire</option>
                        <option value="Locataire">Locataire</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped admin-table" id="userTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date de naissance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            <nav aria-label="User pagination">
                <ul class="pagination justify-content-center" id="paginationControls">
                </ul>
            </nav>
        </div>
    </section>

    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Modifier l'utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form id="editUserForm" action="/coLocation/admin/update-user" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="edit_user_id" name="user_id">
                        
                        <div class="form-group mb-3">
                            <label for="edit_user_role">Rôle</label>
                            <select id="edit_user_role" name="role" class="form-control" required>
                                <option value="3">Locataire</option>
                                <option value="2">Propriétaire</option>
                                <option value="1">Admin</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_user_situation">Situation professionnelle</label>
                            <input type="text" id="edit_user_situation" name="situation" class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_user_garant">Garant</label>
                            <select id="edit_user_garant" name="garant" class="form-control">
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_user_salary">Salaire mensuel net (€)</label>
                            <input type="number" step="0.01" id="edit_user_salary" name="salary" class="form-control">
                        </div>

                        <hr>
                        <h6>Changer le mot de passe</h6>
                        <div class="form-group mb-3">
                            <label for="edit_new_password">Nouveau mot de passe</label>
                            <input type="password" id="edit_new_password" name="new_password" class="form-control" minlength="6">
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_confirm_password">Confirmer le mot de passe</label>
                            <input type="password" id="edit_confirm_password" name="confirm_password" class="form-control" minlength="6">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong id="delete_user_name"></strong> ?</p>
                    <p class="text-danger">Cette action est irréversible !</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form action="/coLocation/admin/delete-user" method="POST">
                        <input type="hidden" id="delete_user_id" name="user_id">
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let totalPages = 1;
        let usersData = [];

        function loadUsers(page = 1) {
            const search = document.getElementById('userSearch')?.value || '';
            const role = document.getElementById('roleFilter')?.value || '';
            
            fetch(`/coLocation/admin/get-users?page=${page}&search=${encodeURIComponent(search)}&role=${encodeURIComponent(role)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        usersData = data.users;
                        totalPages = data.totalPages;
                        currentPage = data.currentPage;
                        renderUsers(usersData);
                        renderPagination();
                    } else {
                        console.error('Error loading users:', data.message);
                        showAlert('Erreur: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Erreur de connexion au serveur', 'error');
                });
        }

        function renderUsers(users) {
            const tbody = document.getElementById('userTableBody');
            if (!users || users.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center">Aucun utilisateur trouvé</td></tr>`;
                return;
            }

            tbody.innerHTML = users.map(user => `
                <tr>
                    <td>${user.id_utilisateur}</td>
                    <td>${escapeHtml(user.nom)}</td>
                    <td>${escapeHtml(user.prenom)}</td>
                    <td>${escapeHtml(user.email)}</td>
                    <td><span class="badge bg-${user.role === 'Admin' ? 'danger' : user.role === 'Propriétaire' ? 'warning' : 'secondary'}">${escapeHtml(user.role || 'Utilisateur')}</span></td>
                    <td>${user.date_naissance ? formatDate(user.date_naissance) : '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editUser(${user.id_utilisateur})">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="confirmDeleteUser(${user.id_utilisateur}, '${escapeHtml(user.prenom)} ${escapeHtml(user.nom)}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function renderPagination() {
            const pagination = document.getElementById('paginationControls');
            if (!pagination) return;

            let html = '';
            
            html += `<li class="page-item ${currentPage <= 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">Précédent</a>
            </li>`;

            for (let i = 1; i <= totalPages; i++) {
                if (i === currentPage) {
                    html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                } else if (i === 1 || i === totalPages || Math.abs(i - currentPage) <= 2) {
                    html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a></li>`;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    html += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
                }
            }

            html += `<li class="page-item ${currentPage >= totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">Suivant</a>
            </li>`;

            pagination.innerHTML = html;
        }

        function changePage(page) {
            if (page < 1 || page > totalPages) return;
            loadUsers(page);
        }

        function filterUsers() {
            loadUsers(1);
        }

        function refreshUserList() {
            loadUsers(currentPage);
            showAlert('Liste des utilisateurs mise à jour', 'success');
        }

        function getRoleIdByName(role) {
            switch (role){
                case 'Admin': return 1;
                case 'Propriétaire': return 2;
                case 'Locataire': return 3;
                default : return 3;
            }
        }

        function editUser(userId) {
            const user = usersData.find(u => u.id_utilisateur == userId);
            if (!user) return;
            
            document.getElementById('edit_user_id').value = user.id_utilisateur;
            document.getElementById('edit_user_role').value = getRoleIdByName(user.role);
            document.getElementById('edit_user_situation').value = user.situation_professionnel || '';
            document.getElementById('edit_user_garant').value = user.garant || 0;
            document.getElementById('edit_user_salary').value = user.salaire_mensuel_net || '';
            document.getElementById('edit_new_password').value = '';
            document.getElementById('edit_confirm_password').value = '';

            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        }

        function confirmDeleteUser(userId, userName) {
            document.getElementById('delete_user_id').value = userId;
            document.getElementById('delete_user_name').textContent = userName;
            new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR');
        }

        function showAlert(message, type = 'success') {
            Swal.fire({
                title: type === 'success' ? 'Succès' : 'Erreur',
                text: message,
                icon: type,
                confirmButtonText: 'OK'
            });
        }

        document.getElementById('editUserForm')?.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('edit_new_password').value;
            const confirmPassword = document.getElementById('edit_confirm_password').value;

            if (newPassword && newPassword !== confirmPassword) {
                e.preventDefault();
                showAlert('Les mots de passe ne correspondent pas', 'error');
                return false;
            }

            if (newPassword && newPassword.length < 6) {
                e.preventDefault();
                showAlert('Le mot de passe doit contenir au moins 6 caractères', 'error');
                return false;
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            loadUsers(1);
        });
    </script>

    <style>
        .admin-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .admin-table { margin-top: 1rem; }
    </style>
<?php endif; ?>