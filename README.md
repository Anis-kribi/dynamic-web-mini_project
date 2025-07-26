# 📚 BOOKORY – Online Book Management Platform

An interactive and responsive platform to manage books, authors, and user interactions. Designed with separate access levels for **Admin** and **Client**, Bookory offers full CRUD operations, dynamic dashboards, and an intuitive UI.

---

## 🎯 **Project Objectives**

- **Admin** can manage:
  - Users (add, update, delete)
  - Books & Authors
  - View statistics (most viewed, most liked, etc.)

- **Clients** (users):
  - Browse books and authors
  - Mark favorites, add comments
  - Access personal dashboard with their activity

- **Visitors**:
  - Can browse public pages
  - Leave comments & send messages (without account)

---

## 🧩 **Key Features**

### 🔒 **Role-Based Access**
- 🔐 Admin Panel (secured login)
- 👤 Client Dashboard (limited rights)
- 🌍 Visitor interaction (public view)

### 🖥️ **Dashboards**
- **Admin Dashboard**:  
  - Total users, books, views  
  - Graphs, recent activity  
  - Control panels for CRUD actions  
<img width="1896" height="634" alt="image" src="https://github.com/user-attachments/assets/c6c3fa54-0316-48a3-8e09-408dbbf00182" />
<img width="1919" height="651" alt="image" src="https://github.com/user-attachments/assets/339bc933-85cb-4d8a-9b8f-d2d5cb514f58" />
<img width="1902" height="934" alt="image" src="https://github.com/user-attachments/assets/36c8082a-cf4d-4df8-81ee-8f89f77853bf" />
<img width="1896" height="936" alt="image" src="https://github.com/user-attachments/assets/dfa55104-3149-43c0-9928-ce352db01d49" />






- **Client Dashboard**:  
  - Favorite books  
  - Recent comments  
  - Author list with bios
  <img width="1901" height="932" alt="image" src="https://github.com/user-attachments/assets/313371c9-9fdf-4885-a86d-83c42d42fef3" />


### 📊 **Statistics (Chart.js)**
- Most liked books  
- Most commented  
- Active users  
<img width="1919" height="934" alt="image" src="https://github.com/user-attachments/assets/55c3a783-8304-4e53-aeb2-fcbfebe67877" />

### 🗂️ **CRUD Operations**
Admins can manage:
- Users  
- Books  
- Authors  
All via dynamic modals and styled forms.

---

## 🛠️ **Technologies Used**

| Layer | Tech |
|-------|------|
| 💻 Frontend | HTML, CSS, Bootstrap, JavaScript, Animate.css |
| ⚙️ Backend | PHP, MySQL |
| 📊 Charts | Chart.js |

---

## 📂 **Folder Structure**
(Example layout)

mini_project/
├── admin/
│ ├── dashboard.php
│ ├── manage_books.php
│ └── manage_users.php
│
├── client/
│ ├── dashboard.php
│ ├── favorites.php
│ └── comments.php
│
├── public/
│ ├── index.php
│ ├── books.php
│ └── contact.php
│
├── includes/
│ ├── db.php
│ ├── functions.php
│ └── auth.php
│
├── assets/
│ ├── css/
│ ├── js/
│ └── images/

---

## 🔐 **Login Credentials (Demo)**

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@bookory.com | admin123 |
| Client | user@bookory.com | user123 |

---

## 🧪 **Setup Instructions**

1. **Clone the repo**
git clone https://github.com/Anis-kribi/mini_project.git
Import the Database

Create a DB named BOOKSTORE

Import bookstore.sql

Move project to your XAMPP htdocs folder

Run Apache & MySQL, then access:

arduino
Copier
Modifier
http://localhost/mini_project
🖼️ Screenshots (To Add Later)
Section	Description
Home page :
<img width="1901" height="934" alt="image" src="https://github.com/user-attachments/assets/7a4d959e-b062-44ec-97c6-5136d92fa421" />
Books page :
<img width="1893" height="912" alt="image" src="https://github.com/user-attachments/assets/2c29e4b4-121c-40e7-ad94-b22e5b26e103" />
Writer page :
<img width="1895" height="913" alt="image" src="https://github.com/user-attachments/assets/28ecb6c4-9057-4e2c-affd-e3a1fb52e1f5" />
Contact page :
<img width="1893" height="905" alt="image" src="https://github.com/user-attachments/assets/7197472d-d842-4ae7-87f4-0bbc5077c99f" />
About us page :
<img width="1919" height="934" alt="image" src="https://github.com/user-attachments/assets/8f655805-4087-41a8-b325-d3f95729b241" />





👤 Author
👨‍💻 Anis Kribi
📧 Contact: anis@example.com 

📝 License
This project was built as part of a university exercise and is open for educational use.


