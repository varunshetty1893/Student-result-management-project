# 🎓 Student Result Management System (SRMS)

A web-based Student Result Management System built with PHP and MySQL. This system allows administrators to manage students, subjects, classes, and results efficiently.

---

## 📌 Features

- 🔐 Admin Login & Authentication
- 👨‍🎓 Add / Edit / Delete Students
- 📚 Create and Manage Subjects
- 🏫 Create and Manage Classes
- 📝 Add / Edit Results
- 📢 Notice Board Management
- 🔍 Find/View Student Results
- 🔒 Change Password Option

---

## 🛠️ Tech Stack

| Technology | Usage |
|------------|-------|
| PHP | Backend / Server-side |
| MySQL | Database |
| HTML/CSS | Frontend |
| XAMPP | Local Server |

---

## ⚙️ Installation & Setup

### Requirements
- XAMPP (or any Apache + PHP + MySQL setup)
- PHP >= 7.0
- MySQL

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/varunshetty1893/Student-result-management-project.git
   ```

2. **Move to XAMPP htdocs folder**
   ```
   C:\xampp\htdocs\SRMS1
   ```

3. **Import the Database**
   - Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Create a new database: `upresult`
   - Import the file: `database/upresult.sql`

4. **Configure Database Connection**
   - Open `includes/db.php`
   - Update your DB credentials:
   ```php
   $host = "localhost";
   $user = "root";
   $password = "";
   $database = "upresult";
   ```

5. **Run the project**
   - Start Apache & MySQL in XAMPP
   - Open browser and go to:
   ```
   http://localhost/SRMS1
   ```

---

## 🔑 Admin Login

| Field | Value |
|-------|-------|
| Username | admin |
| Password | admin123 |

*(Change after first login)*

---

## 📁 Project Structure

```
SRMS1/
├── index.php
├── admin-login.php
├── dashboard.php
├── add-students.php
├── edit-student.php
├── add-result.php
├── edit-result.php
├── find-result.php
├── create-class.php
├── create-subject.php
├── add-notice.php
├── change-password.php
├── includes/
│   ├── db.php
│   ├── header.php
│   └── footer.php
├── database/
│   └── upresult.sql
└── images/
```

---

## 👨‍💻 Developer

**Varun Shetty**
- GitHub: [@varunshetty1893](https://github.com/varunshetty1893)
- Email: shettybvarun@gmail.com

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).
