# 📘 A Multi-Role Learning Management System (LMS) for Medico Prep Point (MPP)

This project is a role-based Learning Management System built with **PHP**, **MySQL**, **HTML**, **CSS**, and **Bootstrap 5**. It was developed as a semester project for the *Database Systems* course and designed to streamline content delivery and user management for an academic institute — Medico Prep Point (MPP).

---

## 🔑 Core Features

- 🔐 **Unified Login System** for Admins, Teachers, and Students  
- 🧑‍💼 **Admin Panel** to manage students, teachers, courses, and departments (CRUD)  
- 👨‍🏫 **Teacher Dashboard** to manage assigned courses and upload materials  
- 👨‍🎓 **Student Dashboard** to view enrolled courses and download resources  
- 🗃 **File Upload System** with server-side storage and tracking  
- 🔐 **Secure Authentication** using `password_hash()` and session management  
- 🛡 **SQL Injection Protection** with prepared statements  

---

## 🛠 Technologies Used

- **Backend:** PHP (Procedural)  
- **Frontend:** HTML5, CSS3, Bootstrap 5  
- **Database:** MySQL (via XAMPP)  
- **Server:** Apache (XAMPP)

---

## 🗃 Key Database Tables

- `Users` – Central login system with roles (`admin`, `teacher`, `student`)  
- `Students`, `Teachers` – Store user-specific academic data  
- `Courses`, `Departments`, `Programs` – Academic structure  
- `Enrollments` – Many-to-many link between students and courses  
- `CourseMaterials` – Stores uploaded learning resources

---

## 📈 Future Enhancements

- 📑 Assignment submission & gradebook module  
- 🔔 Notification & announcement system  
- 📊 Student progress tracking  
- ☁️ Cloud storage integration

---

> 🎓 Developed by **Muhammad Huzaifa Riaz**  
> Muslim Youth University Islamabad — BS Cybersecurity (Semester 2)  
> For **Database Systems** semester project

---

