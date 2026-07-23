# coLocation Project

A web application designed to facilitate roommate searching and room listings in the Vienne region. The project has recently been migrated to a modern **MVC (Model-View-Controller)** architecture to improve maintainability and scalability.

## 🚀 Architecture Overview

The project follows a custom MVC pattern with a clear separation of concerns:

-   **Models (`/app/Models`)**: Contains entity classes (e.g., `User`, `Annonce`, `Message`) and their corresponding **DAO (Data Access Object)** classes which handle all database interactions using PDO.
-   **Views (`/app/Views`)**: PHP templates for the user interface, organized by module (e.g., `auth`, `annonce`, `home`, `partials`).
-   **Controllers (`/app/Controllers`)**: Logic handlers that process user requests, interact with DAOs, and render the appropriate views.
-   **Core (`/app/Core`)**: Essential system components including the `Autoloader`, `Database` singleton, base `Controller`, and `Config` helper.

## 📂 Project Structure

```text
coLocation/
├── app/
│   ├── Controllers/    # Request handlers (AuthController, AnnonceController, etc.)
│   ├── Core/           # System core (Autoloader, Database, Config)
│   ├── Models/         # Data entities and DAOs (User, Annonce, Message)
│   └── Views/          # UI templates (auth, annonce, home, partials)
├── assets/             # Static files (CSS, JS, images)
├── pages/              # Legacy procedural pages (being migrated)
├── photos/             # Uploaded announcement photos
├── SQL/                # Database schema and seed data
├── index.php           # Front Controller (entry point)
├── init.php            # System initialization and DAO instantiation
└── .htaccess           # URL rewriting for MVC routing
```

## 🛠 Technologies Used

-   **Backend**: PHP 8.x
-   **Database**: MySQL (MariaDB) with PDO
-   **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5, FontAwesome 6
-   **Architecture**: Custom MVC with Front Controller pattern
-   **Server**: Optimized for XAMPP/Apache

## 🚦 Getting Started

### Prerequisites
-   XAMPP or any WAMP/LAMP stack with PHP 8.0+ and MySQL.

### Installation
1.  Clone or move the project to your `htdocs` folder.
2.  Import the database schema from `SQL/colocation.sql` into your MySQL server.
3.  Ensure the `RewriteBase` in `.htaccess` matches your project folder name (default is `/coLocation/`).
4.  Update database credentials in `app/Core/Database.php` if necessary.

## 🔗 Key MVC Routes

The application uses a front controller (`index.php`) to route requests. Common routes include:

-   **Home**: `/coLocation/home`
-   **Authentication**:
    -   Login: `/coLocation/auth/login`
    -   Register: `/coLocation/auth/register`
    -   Logout: `/coLocation/auth/logout`
-   **Announcements**:
    -   Listings: `/coLocation/home`
    -   Create: `/coLocation/annonce/create`
    -   Show: `/coLocation/annonce/show?id={id}`
    -   Edit: `/coLocation/annonce/edit?id={id}`
-   **User Profile**:
    -   View/Edit: `/coLocation/pages/profile_utilisateur.php` (Legacy integration)

## 📝 Recent Updates

-   **MVC Migration**: Transitioned core authentication and announcement features to the MVC structure.
-   **Routing**: Implemented a central routing system via `index.php` and `.htaccess`.
-   **DAO Pattern**: Standardized database access through abstract and concrete DAO classes.
-   **Bug Fixes**: Resolved issues with photo uploads and legacy page redirects.

---
*Developed as part of the coLocation platform.*
