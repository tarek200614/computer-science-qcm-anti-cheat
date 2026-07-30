# 🖥️ Computer Science QCM Exam App with Anti-Cheat System

A secure web-based Computer Science Multiple Choice Question (QCM) examination platform built with **PHP**, **MySQL**, **JavaScript**, and **CSS**. The application allows students to take timed exams while providing an administration panel to manage users and questions. It also integrates an anti-cheat system to improve exam integrity.

---

## ✨ Features

### 👨‍🎓 Student

- User registration and login
- Timed Computer Science QCM exams
- Automatic score calculation
- Answer review after submission
- Personal exam history
- Responsive interface

### 👨‍💼 Administrator

- Secure admin dashboard
- User management
- Question management (CRUD)
- View examination statistics

### 🔒 Security

- CSRF protection
- Password hashing (`password_hash()` / `password_verify()`)
- PDO prepared statements
- Session authentication
- Output sanitization with `htmlspecialchars()`

### 🚫 Anti-Cheat System

- Mandatory Fullscreen Mode
- Tab switching detection
- Window blur detection
- Automatic warnings
- Automatic submission when timer expires

---

# 📸 Screenshots

## Home Page

![Home](assets/screenshots/home-page.png)

---

## Login

![Login](assets/screenshots/login.png)

---

## Registration

![Register](assets/screenshots/registration.png)

---

## Student Dashboard

![Dashboard](assets/screenshots/user-dashboard.png)

---

## QCM Examination

![Exam](assets/screenshots/qcm-exam.png)

---

## Results

![Results](assets/screenshots/results-page.png)

---

## Admin Dashboard

![Admin](assets/screenshots/admin-dashboard.png)

---

# 🛠️ Technologies Used

- PHP 8+
- MySQL
- HTML5
- CSS3
- JavaScript (Vanilla)
- PDO
- XAMPP / WAMP / MAMP

---

# 🚀 Installation

1. Clone the repository

```bash
git clone https://github.com/tarek200614/computer-science-qcm-anti-cheat.git
```

2. Move the project into your web server directory.

Example (XAMPP):

```
htdocs/
```

3. Import the database

```
sql/database.sql
```

using **phpMyAdmin**.

4. Configure the database credentials inside

```
src/db.php
```

5. Start Apache and MySQL.

6. Open

```
http://localhost/qcm_project/
```

---

# 📁 Project Structure

```
qcm_project/
│
├── assets/
│   ├── logo/
│   └── screenshots/
│
├── docs/
│   └── project-structure.txt
│
├── public/
│   ├── css/
│   └── js/
│
├── sql/
│   └── database.sql
│
├── src/
│   ├── auth.php
│   ├── db.php
│   └── functions.php
│
├── views/
│   ├── admin/
│   └── user/
│
├── index.php
├── login.php
├── register.php
├── logout.php
└── README.md
```

---

# 🔒 Security Measures

- CSRF Token protection
- Session authentication
- Password hashing
- Prepared SQL statements (PDO)
- Output escaping
- Anti-cheat JavaScript
- Input validation

---

# 📂 Documentation

Additional documentation is available inside the **docs/** folder.

- Project Structure
- Database Script
- Source Code Organization

---

# 👨‍💻 Author

**MEGHARI Abderrahmane Tarek**

AI & Computer Science Student

GitHub:
https://github.com/tarek200614

---

# 📄 License

This project was developed for educational purposes.
