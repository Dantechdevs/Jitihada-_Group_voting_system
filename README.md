🗳️ Jitihada Group Voting System

A secure, web-based voting system built with PHP, Tailwind CSS, and Bootstrap for managing members, collecting votes, and tracking results. Designed for contribution groups, organizations, and small elections.

⚡ Badges












🌟 Features

🔐 Secure admin login with password hashing

👤 Member registration & voting page

📊 Real-time voting statistics

🚫 Admin-only access to results and exports

⬇ Export voting data to CSV

🛡 Session protection for all sensitive pages

📱 Fully responsive UI (mobile & desktop friendly)

📸 Screenshots
Admin Dashboard

Admin Results

Dashboard

Login Page

Members

🚀 Live Demo (Local Setup)

You can run the project locally using XAMPP/WAMP/LAMP:

1. Clone the repository
git clone https://github.com/Dantechdevs/Jitihada-_Group_voting_system.git
cd Jitihada-_Group_voting_system

2. Create the database

Create a MySQL database, for example: jitihada_db

Run the following SQL commands:

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

3. Configure the database connection

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

Members can vote via the member page

Export results as CSV from the admin panel

🛠 Installation Notes

PHP ≥ 8.0

MySQL or MariaDB

Apache (XAMPP/WAMP/LAMP)

curl extension enabled in PHP

🔐 Security

Passwords hashed using password_hash()

Sessions validated before accessing admin pages

Sensitive setup scripts should be deleted after use

🤝 Contributing

Pull requests are welcome!

Add features

Fix bugs

Improve UI/UX

Update documentation

Open an issue if you need help or want to suggest improvements.

📄 License

This project is licensed under the MIT License.
