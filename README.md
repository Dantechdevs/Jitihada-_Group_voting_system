# 🗳️ Jitihada Group Voting System

A web‑based voting system built with **PHP**, **Tailwind CSS**, and **Bootstrap** for managing members and tracking voting results, with secure admin access and CSV export functionality.

This repository includes:
✔ Admin login with hashed passwords  
✔ Member registration & voting page  
✔ Admin‑only results view  
✔ Export results to CSV  
✔ Secure session protection  

---

## 📌 Features

- 🚫 Admin‑only access to results and exports  
- 📊 Voting statistics (total, voted, pending)  
- 👤 Member management (add/edit/track)  
- ⬇ Export voting data to CSV  
- 🔐 Secure login with hashed passwords  
- 📁 Clean and responsive UI

---

## 🚀 Getting Started

### 📦 Requirements

- PHP 8+  
- MySQL / MariaDB  
- Apache (e.g., XAMPP, WAMP, LAMP)  
- Enabled `curl` PHP extension  

---

## 🛠️ Installation

1. **Clone the repository**

```bash
git clone https://github.com/Dantechdevs/Jitihada-_Group_voting_system.git
cd Jitihada-_Group_voting_system

Setup database

Create a database (e.g., jitihada_db) then run:
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    reg_no VARCHAR(50) NOT NULL,
    assigned_number INT DEFAULT NULL,
    has_voted TINYINT(1) DEFAULT 0
);

Configure database connection

Update config/db.php with your DB credentials:

$host = "localhost";
$db   = "jitihada_db";
$user = "root";
$pass = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $user, $pass, $options);


Create an admin user

Create api/add_admin.php, set your desired username & password, then load it once in the browser to insert the admin account. Delete it afterward for security.

👤 Admin Login

Navigate to admin_login.php

Log in using the admin credentials you created

You will be redirected to the results page

📄 Export Data

From the results page, admins can export voting data to CSV using the Export CSV button.

🔐 Security

Passwords are hashed using password_hash()

All admin pages check session role before allowing access

Sensitive scripts (like adding admins) should be removed after use

🧪 Screenshots
dashboard
<img width="955" height="467" alt="Image" src="https://github.com/user-attachments/assets/56f2a7e2-436f-4c41-aa9c-da5899b919be" />

Member registration
<img width="958" height="469" alt="Image" src="https://github.com/user-attachments/assets/e9e38afb-0af5-4f42-96e0-67788adcf258" />

Vote
<img width="960" height="484" alt="Image" src="https://github.com/user-attachments/assets/d1bc6648-3f79-416c-ade9-15f543938826" />

Members
<img width="960" height="470" alt="Image" src="https://github.com/user-attachments/assets/a8dbe10c-9753-4cca-8fdf-73ec94750925" />

Login to view results
<img width="959" height="473" alt="Image" src="https://github.com/user-attachments/assets/c2a5253f-b978-44b1-9a9b-d9edd62fe1c2" />

Results
<img width="957" height="479" alt="Image" src="https://github.com/user-attachments/assets/2dfa2b73-6396-475a-8123-78ebded54624" />


Add screenshots here if available (recommended to boost repo clarity).

📫 Support

If you need help or want to contribute, feel free to open an issue or pull request!
📄 License MIT