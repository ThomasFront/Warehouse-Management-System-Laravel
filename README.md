# Warehouse Management System

**Warehouse Management System** is a comprehensive application designed for managing warehouse operations, built using **React** for the frontend and **Laravel** for the backend. The application provides an intuitive user interface and is available in both Polish and English.

## Features

- **Dashboard**: Displays key information such as the number of users, products, categories, total sales, and includes useful charts related to sales and top products.
  
- **Products**: Allows users to view a list of products with options to export data to CSV, edit, delete, and add new products along with images.
  
- **Categories**: Provides management of product categories through options to edit, delete, and add new categories.
  
- **Sales**: Enables adding sales records for products and viewing a list of all transactions with the ability to export to CSV.
  
- **Profile**: Users can view their profile data and edit their profile picture and theme.
  
- **Messages**: Users can add messages with priority settings and edit or delete existing messages.
  
- **Users**: A list of all users is available, allowing everyone to view profiles, while administrators can add new users, edit, and delete other user accounts.

## Administrator Privileges

Administrators have enhanced permissions, including:
- Managing user accounts (adding, editing, and deleting users).
- Editing and deleting messages.
- Accessing advanced system features.

## Technologies Used

- **Frontend**: React, TypeScript, Material UI, React Hook Form, Yup, Date-fns, Framer Motion, Vitest, React Testing Library 
- **Backend**: Laravel
- **Database**: MySQL

## Some screenshots: 

## Installation

![Login page](https://github.com/user-attachments/assets/4ecc6254-6c00-4fde-90c8-f4cc6c55efb3)
![Dashboard page](https://github.com/user-attachments/assets/ec1d4b20-8f02-45b6-b63f-1f5888209324)
![Products page](https://github.com/user-attachments/assets/a1ec9193-bcea-41a2-8ee8-97d2f1eef482)
![Add product page](https://github.com/user-attachments/assets/0cd8d67a-5cd6-4bb7-9405-84d6f13421b3)
![Sales history page](https://github.com/user-attachments/assets/0d2e6d37-b907-4901-ad9d-2677462911db)
![Message list page](https://github.com/user-attachments/assets/9d1f10b9-0674-4e3e-a1a4-8f1b86772b43)
![Edit user profile page](https://github.com/user-attachments/assets/31d8df89-019c-48ea-83cf-ec928c793f14)
![User list page](https://github.com/user-attachments/assets/452b9c5b-1f21-4740-aa5b-f8c2a0c27695)


**Frontend:**

1. Clone the repository:
   ```bash
   git clone https://github.com/ThomasFront/Warehouse-Management-System-React.git
   ```

2. Navigate to the directory:
   ```bash
   cd Warehouse-Management-System-React
   ```

3. Install frontend dependencies using **npm**:
   ```bash
   npm install
   ```

    or using **yarn**:
    ```bash
    yarn install
    ```

4. Create a **.env.local** file in the root directory with the following values::
   ```bash
   VITE_BACKEND_API_URL=http://127.0.0.1:8000/api/
   VITE_BACKEND_LARAVEL=http://127.0.0.1:8000
   ```

5. Start the React development server using npm:
   ```bash
   npm run dev
   ```

    or using **yarn**:
    ```bash
    yarn dev
    ```

6. Open your browser and navigate to:
   ```bash
   http://localhost:5173/
   ```



**Backend:**

1. Clone the repository:
   ```bash
   git clone https://github.com/ThomasFront/Warehouse-Management-System-Laravel.git
   ```

2. Navigate to the directory:
   ```bash
   cd Warehouse-Management-System-Laravel
   ```

3. Install backend dependencies:
   ```bash
   composer install
   ```

4. Create the .env configuration file:
   ```bash
   cp .env.example .env
   ```

5. Set the appropriate database values in the **.env** file:
   ```bash
   DB_DATABASE=wms
   DB_USERNAME=user
   DB_PASSWORD=password
   ```

6. Generate an application key:
   ```bash
   php artisan key:generate
   ```

7. Generate the JWT secret:
   ```bash
   php artisan jwt:secret
   ```

8. Start the application using Docker:
   ```bash
   docker-compose up -d
   ```

9. Run the database migrations:
   ```bash
   php artisan migrate
   ```

10. Seed the database with initial data:
   ```bash
   php artisan db:seed
   ```

11. Start the development server:
   ```bash
   php artisan serve
   ```

12. Access the frontend at **http://localhost:5173/** and log in to your account:

  Example login credentials:

  For **ADMIN**:
  ```bash
  Email: thomas.admin@example.com
  Password: thomas
  ```

  For **USER**:
  ```bash
  Email: george.user@example.com
  Password: george
  ```
