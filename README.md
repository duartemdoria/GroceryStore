# Online Grocery Store

This repository contains the source code for a web application for an online grocery store. The application allows customers to view products, add them to a cart, and complete a purchase. It also includes an administration panel for the store owner to manage products and view orders.

## ✨ Key Features

### 🛍️ Customer Area
- **Product Showcase:** A homepage with a grid of all available products in stock.
- **Dynamic Shopping Cart:** Add, remove, and view products in the cart, with the total updated in real-time (via PHP session).
- **Secure Checkout:** A checkout form with data validation (minimum age of 18, required fields) and protection against XSS.
- **Stock Updates:** Product stock is automatically updated after each successful purchase.

### ⚙️ Admin Panel
- **Secure Login:** An exclusive login page for the administrator with password verification using hashing.
- **Central Dashboard:** A control panel with a summary of the latest orders and a list of all products.
- **Complete Product Management:** Add new products and edit existing ones (name, description, price, stock, and image).
- **Order Management:** View a list of all orders and check the details of each one (customer data and purchased products).

## 🛠️ Technologies Used
* **Back-end:** PHP
* **Database:** MySQL
* **Front-end:** HTML5, CSS3, Bootstrap 5
* **Web Server:** Apache (typically via XAMPP or MAMP)

---

## 🚀 How to Run the Project

Follow these steps to set up the local development environment.

### Prerequisites
* A local server environment like [XAMPP](https://www.apachefriends.org/index.html) or MAMP.
* A database manager like phpMyAdmin (included with XAMPP).

### 1. Set up the Database
-   Start the Apache and MySQL services in your XAMPP/MAMP control panel.
-   Open phpMyAdmin and create a new database named `mercearia_db`.
-   Import the `database.sql` file (located in the config folder) into the `mercearia_db` database.

### 2. Configure the Project
-   Clone or download this repository into the `htdocs` (for XAMPP) or `htdocs` (for MAMP) folder.
-   Open the `/config/database.php` file.
-   Update the database credentials according to your local setup (usually, the user is `root` and the password is
    empty by default).
```php
$db_host = 'localhost';
$db_name = 'mercearia_db';
$db_user = 'root'; // Your username
$db_pass = '';     // Your password
```

### 3. Access the Application
-   **Store (Public Page):** Open your browser and navigate to `http://localhost/mercearia-online/public/`.
-   **Admin Panel:** Navigate to `http://localhost/mercearia-online/admin/`.
    -   **Username:** `admin`
    -   **Password:** `admin123`

---

## 📁 Folder Structure

The project is organized in a modular way to separate concerns:

```
/
|-- /admin/             # Files for the admin panel
|-- /assets/            # Static assets like images, CSS, JS
|-- /config/            # Configuration files (e.g., database)
|-- /public/            # Public area of the site, accessible to customers
|-- /templates/         # Reusable HTML parts (header, footer)
|-- ecom.sql            # Database setup file
|-- README.md           # This file
