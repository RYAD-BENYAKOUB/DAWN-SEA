# 🌅 DAWN&SEA — Tourist Recommendation Website in Algeria

## 🧩 Project Description  
**DAWN&SEA** is a **tourist recommendation website in Algeria**, developed as part of the **final year project** for obtaining the **Bachelor’s degree in Information Systems and Software Engineering**.

The website allows users to discover the different **wilayas of Algeria**, explore **touristic information**, and access **personalized details** after authentication.

---

## 🏗️ Architectural Refactoring (June 2026)

The project has been completely restructured to separate the presentation layer (Frontend) from the business/data layer (Backend):

1. **Frontend (HTML & JS Pages)**:
   * Public-facing pages are now static HTML files (`index.html`, `login.html`, `signup.html`, `profil.html`, `about.html`, `explore.html`) located at the root of the project.
   * Interactive logic is centralized in the [main.js](assets/JS/main.js) file, which performs asynchronous AJAX requests (`fetch()`) to fetch data and submit forms without page reloads.
   * **Route Guards**: Access to detailed pages (`explore.html`) and the profile (`profil.html`) is secured, automatically redirecting unauthenticated users to the login page.
   * **Dynamic Navigation Bar**: The navbar adapts in real-time based on the user's connection state (displaying "Mon Profil" and "Logout" if logged in; "Login" and "Sign Up" otherwise).

2. **Backend (PHP JSON API)**:
   * Centralized database connection in [db.php](PHP/db.php) using PDO.
   * Shifted PHP scripts to a REST-like API returning only JSON responses.
   * **Simplification & DRY**: Duplicate PHP files at the root and under `/PHP/` were removed. Individual city files (`Oran.php`, `alger.php`, etc.) were replaced by a single generic script [destinations.php](PHP/destinations.php) coupled with the dynamic page [explore.html](explore.html).

---

## 👨‍💻 Project Team
- **Benyakoub Mohammed Ryad**  
- **Addou Houssem Eddine Abdel Ilah**

🎓 Supervised by **Mr. Zennaki Mahmoud**

---

## 🏖️ Main Features

* **Homepage (`index.html`)**: Lists all wilayas with basic descriptions and redirects to details.
* **Dynamic Explore Page (`explore.html`)**: Displays complete recommendations for a selected city or tag.
* **User Authentication**: Asynchronous forms for registration (`signup.html`) and login (`login.html`).
* **User Profile (`profil.html`)**: View, update personal details, or securely delete the account.
* **Tag Filtering**: Seamless navigation through tags associated with recommended places (e.g., Beach, History).

---

## ⚙️ Technologies Used

| Category | Technology |
|---------|------------|
| **Frontend** | HTML5, CSS3, JavaScript (ES6 Fetch), Bootstrap 5 |
| **Backend API** | PHP (Secure PDO Connection) |
| **Database** | MySQL |
| **Local Server** | XAMPP / Laragon |
| **Tools** | VS Code, Git, GitHub |

---
