# 🗳️ Jitihada Group Voting System

![Project Banner](https://raw.githubusercontent.com/Dantechdevs/Jitihada-_Group_voting_system/main/screenshots/dashboard.png)

A **secure, web-based voting system** built with **PHP**, **Tailwind CSS**, and **Bootstrap** for managing members, collecting votes, and tracking results. Ideal for contribution groups, small organizations, or community elections.

---

## 📌 Table of Contents

1. [Badges](#-badges)  
2. [Features](#-features)  
3. [Screenshots](#-screenshots)  
4. [How It Works](#-how-it-works)  
5. [Installation & Setup](#-installation--setup)  
6. [Security](#-security)  
7. [Contributing](#-contributing)  
8. [License](#-license)  

---

## ⚡ Badges

![PHP](https://img.shields.io/badge/PHP-8.0+-blue?logo=php&style=flat-square)  
![MySQL](https://img.shields.io/badge/MySQL-8.0+-blue?logo=mysql&style=flat-square)  
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple?logo=bootstrap&style=flat-square)  
![Tailwind CSS](https://img.shields.io/badge/TailwindCSS-3.0+-38B2AC?style=flat-square)  
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)  
![Last Commit](https://img.shields.io/github/last-commit/Dantechdevs/Jitihada-_Group_voting_system?style=flat-square)  

---

## 🌟 Features

| Feature | Badge |
|---------|-------|
| Secure Admin Login | 🔐 |
| Member Registration & Voting | 👤 |
| Real-time Voting Stats | 📊 |
| Admin-only Results Access | 🚫 |
| Export CSV | ⬇ |
| Session Protection | 🛡 |
| Responsive UI | 📱 |

---

## 📸 Screenshots

### Admin Dashboard  
![Admin Dashboard](https://raw.githubusercontent.com/Dantechdevs/Jitihada-_Group_voting_system/main/screenshots/admin%20dashboard.png)

### Admin Results  
![Admin Results](https://raw.githubusercontent.com/Dantechdevs/Jitihada-_Group_voting_system/main/screenshots/admin%20results.png)

### Dashboard  
![Dashboard](https://raw.githubusercontent.com/Dantechdevs/Jitihada-_Group_voting_system/main/screenshots/dashboard.png)

### Login Page  
![Login Page](https://raw.githubusercontent.com/Dantechdevs/Jitihada-_Group_voting_system/main/screenshots/loginpage.png)

### Members  
![Members](https://raw.githubusercontent.com/Dantechdevs/Jitihada-_Group_voting_system/main/screenshots/members.png)

---

## 🧩 How It Works

```mermaid
flowchart TD
    A[Admin Login] --> B{Authenticated?}
    B -- Yes --> C[Admin Dashboard]
    B -- No --> D[Error / Redirect]
    C --> E[View Voting Stats]
    C --> F[Export CSV]
    C --> G[Manage Members]
    G --> H[Add/Edit Members]
    H --> I[Members Vote]
    I --> E
🚀 Installation & Setup
1. Clone the repository
git clone https://github.com/Dantechdevs/Jitihada-_Group_voting_system.git
cd Jitihada-_Group_voting_system
2. Create the database

Create a MySQL database named jitihada_db and run:

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

3. Configure database connection

Edit config/db.php:

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

4. Create an admin account

Create api/add_admin.php with your desired username & password.

Load it once in the browser to insert the admin account.

Delete this file after use for security.

5. Access the system

Admin login: admin_login.php

Members vote via the member page

Export results as CSV from the admin panel

🔐 Security

Passwords hashed using password_hash()

Session validation on all admin pages

Sensitive scripts should be removed after setup

🤝 Contributing

Pull requests are welcome!

Add features

Fix bugs

Improve UI/UX

Update documentation

Open an issue for help or suggestions.

📄 License

This project is licensed under the MIT License.
