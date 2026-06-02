# 🌅 DAWN&SEA — Tourist Recommendation Website in Algeria

Welcome to **DAWN&SEA**, a web platform designed to help users discover the most beautiful tourist destinations across Algeria.

---

## 📚 Documentation

The project documentation is available in two languages :

- 🇫🇷 **Version Française**  
  👉 [Lire le fichier README_fr.md](README_fr.md)

- 🇬🇧 **English Version**  
  👉 [Read the README_en.md file](README_en.md)

---

## 🏗️ Architectural Refactoring (June 2026)

The project has been refactored from a traditional, coupled PHP template architecture to a modern, decoupled **Frontend (Static HTML/JS) + Backend (PHP JSON API)** structure:

1. **Decoupled Frontend**:
   * All pages are now `.html` files (`index.html`, `login.html`, `signup.html`, `profil.html`, `about.html`, `explore.html`) loaded directly by the browser.
   * Asynchronous AJAX requests (`fetch()`) are used to load dynamic data and submit forms without page reloads.
   * Navbar state (Login/Sign Up vs Profile/Logout) is managed dynamically by [main.js](assets/JS/main.js) based on the session status.
   * Dynamic Routing Guards protect the profile and explore views, redirecting guests to the login page when necessary.

2. **Clean Backend API**:
   * A single database connection module is located at [db.php](PHP/db.php).
   * Backend scripts in `/PHP` have been converted into JSON endpoints returning API responses.
   * All separate redundant files for cities have been removed and replaced by a single API endpoint [destinations.php](PHP/destinations.php) combined with [explore.html](explore.html).

---

## 👨‍💻 Project Team

- **Benyakoub Mohammed Ryad**  
- **Addou Houssem Eddine Abdel Ilah**

🎓 Supervised by **Mr. Zennaki Mahmoud**

---

## 📎 Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript (ES6 fetch API), Bootstrap 5
- **Backend API**: PHP (Pure PDO)
- **Database**: MySQL (BLOB to Base64 mapping for images)
- **Environment**: XAMPP / Laragon
- **Version Control**: Git & GitHub

---

Thank you for visiting this project repository! 🌍✨
