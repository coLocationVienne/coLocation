# Simplified OOP Folder Structure

If the previous structure felt too complex, we can use a "Middle Ground" approach. This structure keeps your existing pages where they are but moves the "logic" into organized classes. This is much easier to implement step-by-step.

## The Simple Structure

```text
coLocation/
├── assets/             # Your existing CSS, JS, and Images
├── classes/            # ALL your new OOP classes go here
│   ├── Database.php    # The DB connection class
│   ├── User.php        # User-related logic (Login, Register)
│   └── Annonce.php     # Announcement-related logic
├── includes/           # Shared snippets (header.php, footer.php)
├── pages/              # Your existing UI pages (connexion.php, etc.)
│   └── be/             # Your backend handlers (login_process.php, etc.)
├── index.php           # Your landing page
└── init.php            # A new file to load everything automatically
```

## How This Works (The Simple Way)

### 1. The `classes/` Folder
Instead of having SQL queries inside your `pages/be/` scripts, you put them inside methods in these classes.
- **`Database.php`**: Handles the connection.
- **`User.php`**: Contains methods like `login($email, $password)` and `create($data)`.
- **`Annonce.php`**: Contains methods like `create($data)` and `getAll()`.

### 2. The `init.php` File (The "Magic" Link)
Create a file called `init.php` in your root directory. Its only job is to include your classes so you don't have to `require` them manually everywhere.

```php
<?php
// init.php
session_start();

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Annonce.php';

// Create global instances so they are ready to use
$db = (new Database())->getConnection();
$userManager = new User($db);
$annonceManager = new Annonce($db);
```

### 3. How your `login_process.php` changes
Now, your backend scripts become very short and clean:

**Before (Procedural):**
```php
// login_process.php
require "../../includes/dbConnection.php";
$conn = getDbConnection();
$stmt = $conn->prepare("SELECT * FROM utilisateur...");
// ... 30 more lines of logic ...
```

**After (Simple OOP):**
```php
// login_process.php
require "../../init.php"; // This gives you $userManager automatically

$email = $_POST['email'];
$password = $_POST['password'];

if ($userManager->login($email, $password)) {
    header("Location: ../../index.php");
} else {
    header("Location: ../connexion.php?error=1");
}
```

