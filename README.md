# 🖥️ Computer Science QCM Exam App with Anti-Cheat System

A secure web-based Computer Science Multiple Choice Question (QCM) examination platform built with **PHP**, **MySQL**, **JavaScript**, and **CSS**. The application allows students to take timed exams while providing an administration panel to manage users and questions. It also integrates an anti-cheat system to improve exam integrity.

---
# 📖 Overview

This project was developed as a **Computer Science web application** to simulate a secure online examination platform. It enables students to register, authenticate, complete timed multiple-choice exams, and instantly receive their scores and answer corrections.

The application includes a dedicated administrator interface for managing users and question banks while implementing several security mechanisms to protect both user data and exam integrity. An integrated anti-cheat system monitors fullscreen mode, detects tab switching or window changes, and automatically submits the exam when the allocated time expires.

The project follows a modular architecture using **PHP**, **MySQL**, **PDO**, **HTML**, **CSS**, and **Vanilla JavaScript**, making it easy to maintain, extend, and deploy in a local web server environment such as **XAMPP**, **WAMP**, or **MAMP**.

---
## ⭐ Project Highlights

- 🔐 Secure authentication system
- 🚫 Anti-cheat examination environment
- ⏱️ Timed QCM examinations
- 📊 Automatic grading and score calculation
- 📚 Personal exam history
- 👨‍💼 Administrator management panel
- 📱 Responsive user interface
- 🛡️ CSRF protection and secure PDO queries
- 🎯 Clean modular PHP architecture

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

<img width="1920" height="1080" alt="home-page" src="https://github.com/user-attachments/assets/fd000c17-6f9c-495d-a083-caddd451235e" />


---

## Login

<img width="1920" height="1080" alt="login" src="https://github.com/user-attachments/assets/5d490bd1-80df-4680-bf07-e879a000eabb" />


---

## Registration

<img width="1920" height="1080" alt="registration" src="https://github.com/user-attachments/assets/d4427f15-99d3-4be7-b47f-40829216d52f" />


---

## Student Dashboard

<img width="1920" height="1080" alt="user-dashboard" src="https://github.com/user-attachments/assets/d8554f04-7bdd-4cf0-80c6-fd21dfccdbec" />


---

## QCM Examination

<img width="1920" height="1080" alt="qcm-exam" src="https://github.com/user-attachments/assets/39aaccf4-2b64-4182-b12a-d50b1f0af211" />


---

## Results

<img width="1920" height="1080" alt="results-page" src="https://github.com/user-attachments/assets/d29d239e-c8bc-41dd-b81d-065eeac0fc12" />


---

## Admin Dashboard

<img width="1920" height="1080" alt="admin-dashboard" src="https://github.com/user-attachments/assets/e3960296-5cc2-45ac-8618-81ea8e6d1619" />


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
