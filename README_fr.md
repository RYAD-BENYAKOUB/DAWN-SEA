# 🌅 DAWN&SEA — Site Web de Recommandation Touristique en Algérie

## 🧩 Description du projet
**DAWN&SEA** est un site web de **recommandation touristique en Algérie**, développé dans le cadre du **projet de fin d’étude** pour l’obtention du **diplôme de Licence en Ingénierie des Systèmes d’Information et Logiciels**.  

Ce site permet aux utilisateurs de découvrir les différentes **wilayas d’Algérie**, d’en consulter les **informations touristiques**, et d’accéder à des **détails personnalisés** après authentification.

---

## 🏗️ Refonte Architecturale (Juin 2026)

Le projet a été entièrement restructuré pour séparer la partie présentation (Frontend) et la partie logique/données (Backend) :

1. **Frontend (Pages HTML + JS)** :
   * Les pages publiques sont désormais des fichiers HTML statiques (`index.html`, `login.html`, `signup.html`, `profil.html`, `about.html`, `explore.html`) situés à la racine du projet.
   * La logique interactive est centralisée dans le fichier [main.js](assets/JS/main.js), qui effectue des requêtes AJAX asynchrones (`fetch()`) pour récupérer les données et soumettre les formulaires sans rechargement de page.
   * **Gardes de Route** : L'accès aux pages détaillées (`explore.html`) et au profil (`profil.html`) est sécurisé et redirige automatiquement les utilisateurs non connectés vers la page de connexion.
   * **Barre de navigation dynamique** : La navbar s'adapte en temps réel selon l'état de connexion de l'utilisateur (affichage de "Mon Profil" et "Déconnexion" si connecté ; "Login" et "Sign Up" sinon).

2. **Backend (API PHP en JSON)** :
   * Centralisation de la connexion à la base de données dans [db.php](PHP/db.php) à l'aide de PDO.
   * Transformation des scripts PHP en API REST renvoyant uniquement des réponses au format JSON.
   * **Simplification et DRY** : Les fichiers PHP doublons à la racine et sous le dossier `/PHP/` ont été supprimés. Les fichiers individuels par ville (`Oran.php`, `alger.php`, etc.) ont été remplacés par un script générique [destinations.php](PHP/destinations.php) couplé à la page dynamique [explore.html](explore.html).

---

## 👨‍💻 Équipe du projet
- **Benyakoub Mohammed Ryad**  
- **Addou Houssem Eddine Abdel Ilah**

🎓 Encadré par **Mr. Zennaki Mahmoud**

---

## 🏖️ Fonctionnalités principales

* **Page d'accueil (`index.html`)** : Affiche les wilayas avec leurs descriptions de base et redirige vers leurs détails.
* **Page d'exploration dynamique (`explore.html`)** : Affiche les recommandations complètes de la ville sélectionnée ou des tags sélectionnés.
* **Authentification utilisateur** : Formulaires asynchrones pour l'inscription (`signup.html`) et la connexion (`login.html`).
* **Profil utilisateur (`profil.html`)** : Consultation, mise à jour des informations personnelles et suppression sécurisée du compte.
* **Filtrage par Tags** : Navigation fluide via les tags associés aux lieux recommandés (ex: Plage, Historique).

---

## ⚙️ Technologies utilisées

| Type | Technologie |
|------|--------------|
| **Frontend** | HTML5, CSS3, JavaScript (ES6 Fetch), Bootstrap 5 |
| **Backend API** | PHP (Connexion PDO sécurisée) |
| **Base de données** | MySQL |
| **Serveur local** | XAMPP / Laragon |
| **Outils** | VS Code, Git, GitHub |

---