// assets/JS/main.js

document.addEventListener('DOMContentLoaded', () => {
    initApp();
});

// Constante pour l'URL de base des scripts d'API
const API_BASE = 'api';

// Fonction d'initialisation de l'application
async function initApp() {
    const session = await checkSession();
    updateNavbar(session);
    
    // Router simple basé sur le nom du fichier HTML
    const path = window.location.pathname;
    const page = path.substring(path.lastIndexOf('/') + 1);

    if (page === 'login.html') {
        if (session.logged_in) {
            window.location.href = 'index.html';
        } else {
            initLoginPage();
        }
    } else if (page === 'signup.html') {
        if (session.logged_in) {
            window.location.href = 'index.html';
        } else {
            initSignupPage();
        }
    } else if (page === 'forgot_password.html') {
        if (session.logged_in) {
            window.location.href = 'index.html';
        } else {
            initForgotPasswordPage();
        }
    } else if (page === 'profil.html') {
        if (!session.logged_in) {
            window.location.href = 'login.html';
        } else {
            initProfilePage(session.user);
        }
    } else if (page === 'explore.html') {
        if (!session.logged_in) {
            alert('Veuillez vous connecter pour voir les détails de cette destination.');
            window.location.href = 'login.html';
        } else {
            initExplorePage();
        }
    } else if (page === 'admin.html') {
        if (!session.logged_in || !(session.user.role === 'admin' || session.user.role === 'superadmin')) {
            alert("Accès interdit : réservé aux administrateurs.");
            window.location.href = 'index.html';
        } else {
            initAdminPage(session.user);
        }
    } else if (page === 'index.html' || page === '') {
        initIndexPage(session.logged_in);
    }
}

// 1. Vérification de la Session
async function checkSession() {
    try {
        const response = await fetch(`${API_BASE}/auth.php?action=status`);
        if (response.ok) {
            return await response.json();
        }
    } catch (e) {
        console.error('Erreur session :', e);
    }
    return { logged_in: false };
}

// 2. Mise à jour de la Navbar
function updateNavbar(session) {
    const navContainer = document.getElementById('x');
    if (!navContainer) return;

    // Vider et recréer les éléments de navigation dynamiques
    let navHTML = `
        <ul class="navbar-nav gap-2 gap-md-4 align-items-center justify-content-center flex-column flex-md-row w-100 my-2 my-md-0">
            <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
            <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
    `;

    if (session.logged_in) {
        navHTML += `
            <li class="nav-item"><a class="nav-link" href="profil.html">Profil</a></li>
        `;
        if (session.user.role === 'admin' || session.user.role === 'superadmin') {
            navHTML += `
                <li class="nav-item"><a class="nav-link text-warning fw-bold" href="admin.html">Admin</a></li>
            `;
        }
        navHTML += `
            <li class="nav-item">
                <button class="nav-link btn btn-outline-danger btn-sm text-white px-3" onclick="handleLogout()">Logout</button>
            </li>
            <li class="nav-item">
                <form id="SRCH" class="d-flex" role="search" onsubmit="handleSearch(event)">
                    <input class="form-control me-2" type="search" placeholder="Search a tag..." aria-label="Search" id="search-input">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </li>
            <li class="nav-item navbar-text text-white ms-0 ms-md-3">
                Bienvenue, <strong>${escapeHTML(session.user.prenom)}</strong>
            </li>
        `;
    } else {
        navHTML += `
            <li class="nav-item">
                <a class="nav-link btn btn-outline-info btn-sm text-white px-3" href="login.html">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn btn-outline-success btn-sm text-white px-3" href="signup.html">Sign_Up</a>
            </li>
        `;
    }

    navHTML += `</ul>`;
    navContainer.innerHTML = navHTML;
}

// 3. Déconnexion
async function handleLogout() {
    if (confirm('Voulez-vous vraiment vous déconnecter ?')) {
        try {
            const response = await fetch(`${API_BASE}/auth.php?action=logout`);
            const result = await response.json();
            if (result.success) {
                window.location.href = 'index.html';
            }
        } catch (e) {
            console.error('Erreur déconnexion :', e);
        }
    }
}

// 4. Recherche globale (ville, tag, lieu)
function handleSearch(event) {
    event.preventDefault();
    const query = document.getElementById('search-input').value.trim();
    if (query) {
        window.location.href = `explore.html?search=${encodeURIComponent(query)}`;
    }
}

// 5. Page de Connexion
function initLoginPage() {
    const form = document.getElementById('LOGIN');
    if (!form) return;

    // Afficher les erreurs d'authentification externes (ex: retour Google OAuth)
    const urlParams = new URLSearchParams(window.location.search);
    const errorParam = urlParams.get('error');
    if (errorParam) {
        showToast(errorParam, 'danger');
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(`${API_BASE}/auth.php?action=login`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Erreur serveur (${response.status}) : ${errorText}`);
            }

            const result = await response.json();

            if (result.success) {
                window.location.href = 'index.html';
            } else {
                showToast(result.message, 'danger');
            }
        } catch (error) {
            showToast('Une erreur est survenue lors de la connexion : ' + error.message, 'danger');
        }
    });
}

// 6. Page d'Inscription
function initSignupPage() {
    const form = document.getElementById('SIGN-UP');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        if (data.password !== data.password_confirmation) {
            showToast('Les mots de passe ne correspondent pas.', 'danger');
            return;
        }

        try {
            const response = await fetch(`${API_BASE}/auth.php?action=signup`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Erreur serveur (${response.status}) : ${errorText}`);
            }

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.href = 'index.html';
            } else {
                showToast(result.message, 'danger');
            }
        } catch (error) {
            showToast("Une erreur est survenue lors de l'inscription : " + error.message, 'danger');
        }
    });
}

// 7. Page de Profil
async function initProfilePage(user) {
    const form = document.getElementById('SIGN-UP'); // Note: ID dans le profil original
    if (!form) return;

    // Charger les infos actuelles du profil
    try {
        const response = await fetch(`${API_BASE}/profile_action.php`);
        const result = await response.json();

        if (result.success) {
            const data = result.data;
            document.getElementById('nom').value = data.nom;
            document.getElementById('prenom').value = data.prenom;
            document.getElementById('email').value = data.email;
            document.getElementById('num_tel').value = data.num_de_telephone;
            document.getElementById('pays_naissance').value = data.pays_naissance;
            document.getElementById('date_naissance').value = data.date_naissance;
        } else {
            showToast(result.message, 'danger');
        }
    } catch (e) {
        showToast("Impossible de charger les données du profil.", 'danger');
    }

    // Gestion de la modification
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(`${API_BASE}/profile_action.php?action=update`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
                // Rafraîchir l'initialisation pour mettre à jour la navbar (nom)
                const updatedSession = await checkSession();
                updateNavbar(updatedSession);
            } else {
                showToast(result.message, 'danger');
            }
        } catch (err) {
            showToast("Erreur lors de la modification du profil.", 'danger');
        }
    });

    // Gestion de la suppression du compte
    const deleteBtn = document.getElementById('delete-profile-btn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', async () => {
            if (confirm('Êtes-vous sûr de vouloir supprimer définitivement votre compte ? Cette action est irréversible.')) {
                try {
                    const response = await fetch(`${API_BASE}/profile_action.php?action=delete`, {
                        method: 'POST'
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert(result.message);
                        window.location.href = 'index.html';
                    } else {
                        showToast(result.message, 'danger');
                    }
                } catch (e) {
                    showToast('Erreur lors de la suppression du compte.', 'danger');
                }
            }
        });
    }
}

// 8. Page d'Accueil
function initIndexPage(loggedIn) {
    // Si l'utilisateur n'est pas connecté, réécrire tous les liens "See Details" pour aller vers la connexion
    if (!loggedIn) {
        const detailButtons = document.querySelectorAll('.btn-danger');
        detailButtons.forEach(btn => {
            const href = btn.getAttribute('href');
            // Si le bouton mène vers explore.html
            if (href && href.startsWith('explore.html')) {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    alert('Veuillez vous connecter pour voir les détails de cette destination.');
                    window.location.href = 'login.html';
                });
            }
        });
    }
}

// 9. Page d'Exploration dynamique (Ville, Tag ou Recherche)
async function initExplorePage() {
    const params = new URLSearchParams(window.location.search);
    const ville = params.get('ville');
    const tag = params.get('tag');
    const search = params.get('search');

    const titleContainer = document.getElementById('explore-title');
    const cardsContainer = document.getElementById('explore-cards');

    if (!titleContainer || !cardsContainer) return;

    let apiURL = '';
    if (ville) {
        titleContainer.innerHTML = `Découvrir ${escapeHTML(ville)} 🇩🇿`;
        apiURL = `${API_BASE}/destinations.php?ville=${encodeURIComponent(ville)}`;
    } else if (tag) {
        titleContainer.innerHTML = `Résultats pour le tag : <span class="text-danger fw-bolder">"${escapeHTML(tag)}"</span>`;
        apiURL = `${API_BASE}/destinations.php?tag=${encodeURIComponent(tag)}`;
    } else if (search) {
        titleContainer.innerHTML = `Résultats pour : <span class="text-danger fw-bolder">"${escapeHTML(search)}"</span>`;
        apiURL = `${API_BASE}/destinations.php?search=${encodeURIComponent(search)}`;
    } else {
        titleContainer.innerHTML = `Aucune sélection`;
        cardsContainer.innerHTML = `<p class="text-light">Aucune destination ou thématique n'a été sélectionnée.</p>`;
        return;
    }

    try {
        const response = await fetch(apiURL);
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            let cardsHTML = `<div class="row row-cols-1 row-cols-md-2 g-4">`;

            result.data.forEach(rec => {
                let imgHTML = '';
                if (rec.image) {
                    imgHTML = `<img src="${rec.image}" class="card-img-top" alt="${escapeHTML(rec.lieu_nom)}">`;
                }

                let tagsHTML = '';
                if (rec.tags && rec.tags.length > 0) {
                    rec.tags.forEach(t => {
                        tagsHTML += `
                            <a href="explore.html?tag=${encodeURIComponent(t)}" class="btn btn-danger btn-sm me-1 mt-1">
                                ${escapeHTML(t)}
                            </a>
                        `;
                    });
                }

                cardsHTML += `
                    <div class="col">
                        <div class="card h-100">
                            ${imgHTML}
                            <div class="card-body bg-dark text-light d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title text-warning">${escapeHTML(rec.titre)} - ${escapeHTML(rec.lieu_nom)}</h5>
                                    <p class="card-text text-light">${escapeHTML(rec.description)}</p>
                                    <p class="card-text text-light fs-5">Note : ${escapeHTML(String(rec.note))} ⭐</p>
                                </div>
                                <div class="gap-2 mt-3">
                                    ${tagsHTML}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            cardsHTML += `</div>`;
            cardsContainer.innerHTML = cardsHTML;
        } else {
            cardsContainer.innerHTML = `
                <p class="text-light mt-4">Aucune recommandation trouvée pour cette sélection.</p>
            `;
        }
    } catch (e) {
        cardsContainer.innerHTML = `
            <p class="text-danger mt-4">Erreur lors de la récupération des données touristiques.</p>
        `;
    }
}

// 10. Utilitaires
function escapeHTML(str) {
    if (!str) return '';
    return str.replace(/[&<>'"]/g, 
        tag => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#39;',
            '"': '&quot;'
        }[tag] || tag)
    );
}

function showToast(message, type = 'danger') {
    // Vérifie s'il existe déjà un container d'alertes
    let alertContainer = document.getElementById('alert-container');
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.id = 'alert-container';
        alertContainer.style.position = 'fixed';
        alertContainer.style.top = '20px';
        alertContainer.style.right = '20px';
        alertContainer.style.zIndex = '9999';
        alertContainer.style.maxWidth = '350px';
        document.body.appendChild(alertContainer);
    }

    const alertEl = document.createElement('div');
    alertEl.className = `alert alert-${type} alert-dismissible fade show`;
    alertEl.role = 'alert';
    alertEl.innerHTML = `
        ${escapeHTML(message)}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    alertContainer.appendChild(alertEl);

    // Supprimer l'alerte automatiquement après 5 secondes
    setTimeout(() => {
        alertEl.classList.remove('show');
        setTimeout(() => alertEl.remove(), 150);
    }, 5000);
}

// 11. Dashboard d'Administration
async function initAdminPage(currentUser) {
    // 1. Initialiser le basculement des onglets de navigation
    const tabButtons = document.querySelectorAll('.admin-tab-btn');
    const sections = document.querySelectorAll('.admin-section');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            tabButtons.forEach(b => b.classList.remove('active', 'btn-primary'));
            tabButtons.forEach(b => b.classList.add('btn-outline-light'));
            btn.classList.remove('btn-outline-light');
            btn.classList.add('active', 'btn-primary');

            const targetSectionId = btn.getAttribute('data-target');
            sections.forEach(sec => {
                if (sec.id === targetSectionId) {
                    sec.classList.remove('d-none');
                } else {
                    sec.classList.add('d-none');
                }
            });
        });
    });

    // 2. Charger les données au chargement
    await loadStats();
    await loadDestinations();
    await loadUsers(currentUser);

    // 3. Gestionnaire du formulaire d'ajout de destination
    const addForm = document.getElementById('add-destination-form');
    if (addForm) {
        addForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = addForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Adding...';

            const formData = new FormData(addForm);
            const data = Object.fromEntries(formData.entries());

            const fileInput = document.getElementById('lieu_image');
            let imageBase64 = '';

            const sendPayload = async (imgBase64) => {
                data.lieu_image = imgBase64;
                try {
                    const response = await fetch(`${API_BASE}/admin_action.php?action=add_destination`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });
                    const result = await response.json();

                    if (result.success) {
                        showToast(result.message, 'success');
                        addForm.reset();
                        // Fermer le modal Bootstrap si utilisé
                        const modalEl = document.getElementById('addDestinationModal');
                        if (modalEl) {
                            const modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) modalInstance.hide();
                        }
                        // Recharger les données
                        await loadStats();
                        await loadDestinations();
                    } else {
                        showToast(result.message, 'danger');
                    }
                } catch (err) {
                    showToast("Erreur lors de la création de la destination.", "danger");
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Enregistrer';
                }
            };

            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const reader = new FileReader();
                reader.onload = async () => {
                    await sendPayload(reader.result);
                };
                reader.onerror = () => {
                    showToast("Erreur lors de la lecture de l'image.", "danger");
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Enregistrer';
                };
                reader.readAsDataURL(file);
            } else {
                await sendPayload('');
            }
        });
    }
}

// Charger les statistiques globales
async function loadStats() {
    try {
        const response = await fetch(`${API_BASE}/admin_action.php?action=stats`);
        const result = await response.json();
        if (result.success) {
            document.getElementById('stat-users').innerText = result.stats.total_users;
            document.getElementById('stat-admins').innerText = result.stats.total_admins;
            document.getElementById('stat-destinations').innerText = result.stats.total_recs;
        }
    } catch (e) {
        console.error("Erreur stats :", e);
    }
}

// Charger et lister les destinations (lieux et recommandations)
async function loadDestinations() {
    const tbody = document.getElementById('destinations-tbody');
    if (!tbody) return;

    try {
        const response = await fetch(`${API_BASE}/admin_action.php?action=get_destinations`);
        const result = await response.json();

        if (result.success) {
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-light">Aucune destination enregistrée.</td></tr>';
                return;
            }

            let html = '';
            result.data.forEach(item => {
                html += `
                    <tr>
                        <td class="text-warning fw-bold">${item.id}</td>
                        <td class="text-light fw-bold">${escapeHTML(item.lieu_nom)}</td>
                        <td class="text-light">${escapeHTML(item.address)}</td>
                        <td class="text-light fw-bold">${escapeHTML(item.titre)}</td>
                        <td class="text-warning">${escapeHTML(String(item.note_generale))} ⭐</td>
                        <td>
                            <button class="btn btn-sm btn-danger" onclick="deleteDestination(${item.id})">
                                Supprimer
                            </button>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        } else {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">${escapeHTML(result.message)}</td></tr>`;
        }
    } catch (err) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Erreur de chargement.</td></tr>';
    }
}

// Supprimer une destination
async function deleteDestination(recId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette recommandation de destination ? S'il s'agit de l'unique recommandation pour ce lieu, le lieu sera également supprimé.")) {
        try {
            const response = await fetch(`${API_BASE}/admin_action.php?action=delete_destination`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ rec_id: recId })
            });
            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
                await loadStats();
                await loadDestinations();
            } else {
                showToast(result.message, 'danger');
            }
        } catch (e) {
            showToast("Erreur lors de la suppression de la destination.", "danger");
        }
    }
}

// Charger et lister les utilisateurs et rôles
async function loadUsers(currentUser) {
    const tbody = document.getElementById('users-tbody');
    if (!tbody) return;

    try {
        const response = await fetch(`${API_BASE}/admin_action.php?action=get_users`);
        const result = await response.json();

        if (result.success) {
            let html = '';
            result.data.forEach(user => {
                const isSuperAdmin = user.role === 'superadmin' || user.email === 'ryadbenyakoub@gmail.com';
                const isCurrentUser = user.email === currentUser.email;

                // Génération du sélecteur de rôle (réservé uniquement au superadmin et bloqué sur sa propre ligne)
                let roleControl = '';
                if (currentUser.role === 'superadmin') {
                    if (isSuperAdmin) {
                        roleControl = `<span class="badge bg-danger px-3 py-2">Superadmin</span>`;
                    } else {
                        roleControl = `
                            <select class="form-select form-select-sm bg-dark text-light border-secondary w-auto d-inline-block"
                                    onchange="changeUserRole(${user.id}, this.value)">
                                <option value="user" ${user.role === 'user' ? 'selected' : ''}>Utilisateur</option>
                                <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                            </select>
                        `;
                    }
                } else {
                    // Les admins voient le rôle en texte sans contrôle
                    let badgeClass = 'bg-secondary';
                    if (user.role === 'admin') badgeClass = 'bg-info';
                    if (user.role === 'superadmin') badgeClass = 'bg-danger';
                    roleControl = `<span class="badge ${badgeClass} px-3 py-2">${escapeHTML(user.role)}</span>`;
                }

                // Actions : supprimer l'utilisateur (bloqué pour soi-même et pour le superadmin)
                let actionBtn = '';
                if (isSuperAdmin || isCurrentUser) {
                    actionBtn = `<span class="text-muted italic">Aucune</span>`;
                } else {
                    actionBtn = `
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(${user.id})">
                            Supprimer
                        </button>
                    `;
                }

                html += `
                    <tr>
                        <td class="text-warning">${user.id}</td>
                        <td class="text-light fw-bold">${escapeHTML(user.nom)} ${escapeHTML(user.prenom)}</td>
                        <td class="text-light">${escapeHTML(user.email)}</td>
                        <td class="text-light">${escapeHTML(user.num_de_telephone || 'Non spécifié')}</td>
                        <td class="text-light">${escapeHTML(user.pays_de_naissance || 'Non spécifié')}</td>
                        <td>${roleControl}</td>
                        <td>${actionBtn}</td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        } else {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">${escapeHTML(result.message)}</td></tr>`;
        }
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Erreur de chargement des utilisateurs.</td></tr>';
    }
}

// Modifier le rôle d'un utilisateur (Action Superadmin uniquement)
async function changeUserRole(userId, newRole) {
    try {
        const response = await fetch(`${API_BASE}/admin_action.php?action=update_role`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId, role: newRole })
        });
        const result = await response.json();

        if (result.success) {
            showToast(result.message, 'success');
            await loadStats();
            // Recharger la liste avec les nouvelles infos
            const session = await checkSession();
            await loadUsers(session.user);
        } else {
            showToast(result.message, 'danger');
        }
    } catch (e) {
        showToast("Erreur lors de la modification du rôle.", "danger");
    }
}

// Supprimer un utilisateur
async function deleteUser(userId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur de la plateforme ? Cette action est irréversible.")) {
        try {
            const response = await fetch(`${API_BASE}/admin_action.php?action=delete_user`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId })
            });
            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
                await loadStats();
                const session = await checkSession();
                await loadUsers(session.user);
            } else {
                showToast(result.message, 'danger');
            }
        } catch (e) {
            showToast("Erreur lors de la suppression de l'utilisateur.", "danger");
        }
    }
}

// 12. Page de Récupération de Mot de passe oublié
function initForgotPasswordPage() {
    const form = document.getElementById('FORGOT-PASSWORD');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        if (data.password !== data.password_confirmation) {
            showToast('Les mots de passe ne correspondent pas.', 'danger');
            return;
        }

        if (data.password.length < 6) {
            showToast('Le nouveau mot de passe doit faire au moins 6 caractères.', 'danger');
            return;
        }

        try {
            const response = await fetch(`${API_BASE}/auth.php?action=reset_password`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Erreur serveur (${response.status}) : ${errorText}`);
            }

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.href = 'login.html';
            } else {
                showToast(result.message, 'danger');
            }
        } catch (error) {
            showToast("Une erreur est survenue lors du rétablissement : " + error.message, 'danger');
        }
    });
}

