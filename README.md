# coLocation Project

A standalone web application designed to facilitate roommate searching and room listings. This project is fully containerized using Docker, making it independent of local installations like XAMPP.

## 🚀 Standalone Architecture

The project is built with a modern **MVC (Model-View-Controller)** architecture and is designed to run in a self-contained environment:

-   **App Container**: PHP 8.2 + Apache, pre-configured with all necessary extensions and dependencies.
-   **DB Container**: MySQL 8.0, pre-loaded with the application schema and seed data.
-   **Independence**: No need to install PHP, Apache, or MySQL on your host machine. Docker handles everything.

## 📂 Project Structure

```text
coLocation/
├── app/                # Core logic, Models, Views, Controllers
├── assets/             # Static files (CSS, JS, images)
├── SQL/                # Database schema (colocation.sql)
├── Dockerfile          # App image definition
├── Dockerfile.db       # Database image definition (with auto-import)
├── docker-compose.yml  # Service orchestration
└── index.php           # Entry point
```

## 🐳 Getting Started (Standalone)

### Prerequisites
-   **Docker Desktop** installed and running.

### 1. Setup File Sharing (First Time Only)
To allow Docker to access your project files (for development):
1.  Open **Docker Desktop Settings**.
2.  Navigate to **Resources** -> **File Sharing**.
3.  Add the path to this project folder.
4.  Click **Apply & Restart**.

### 2. Launch the Application
Navigate to the project root and run:
```bash
docker-compose up --build -d
```

### 3. Access the Services
-   **Web App**: [http://localhost](http://localhost)
-   **Database Manager (phpMyAdmin)**: [http://localhost:8081](http://localhost:8081)
    -   *Username*: `root`
    -   *Password*: `root_password`

## 🧪 Testing & CI/CD

### Local Testing
Run the test suite inside the standalone container:
```bash
docker-compose exec app php ./vendor/bin/phpunit
```

### GitHub Actions
Every push to `main` or `develop` triggers an automated build and test cycle via GitHub Actions, ensuring the standalone image remains stable and functional.

## 🛠 Configuration

The application is now environment-aware. Key settings are managed in `docker-compose.yml`:
-   `APP_BASE_URL`: Set to `/` for Docker (root) or `/coLocation` for legacy XAMPP.
-   `MYSQL_HOST`: Set to `db` to connect to the internal Docker database.

---
*Developed as a modern, standalone solution for coLocation.*

<!-- Triggering GitHub Actions for testing -->
