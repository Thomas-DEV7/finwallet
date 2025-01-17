# **FinWallet - Digital Wallet Application**

## **Overview**
FinWallet is a secure, efficient, and user-friendly digital wallet application. Designed for modern financial management, it supports functionalities like balance management, secure transactions, deposit handling, and detailed reversal request workflows. This application is ideal for both end-users and administrators, providing a seamless experience with robust backend capabilities.

---

## **Features**
- **User Features:**
  - View current balance.
  - Perform secure deposits and transfers.
  - View transaction history with detailed information.
  - Request transaction reversals.

- **Admin Features:**
  - Admin dashboard with metrics:
    - Total users.
    - Total transactions.
    - Pending reversal requests.
  - Manage users:
    - Edit user information.
    - Delete user accounts.
  - Handle reversal requests:
    - Approve or reject with real-time updates.
  - View and analyze detailed transaction logs.

---

## **Tech Stack**
- **Backend:** Laravel 11 (PHP 8.2)
- **Frontend:** Tailwind CSS, Blade Templates
- **Database:** MySQL
- **Authentication:** Laravel Breeze
- **Containerization:** Docker
- **Version Control:** Git
- **Observability:** Logging and request monitoring with Laravel Telescope (optional)

---

## **System Requirements**
- **PHP:** >= 8.2
- **Laravel:** 11
- **Composer:** >= 2.0
- **Node.js:** >= 18.x with npm or Yarn
- **Database:** MySQL 8.0 or compatible
- **Optional:** Docker with Docker Compose

---

## **Installation**

### **Prerequisites**
1. Ensure PHP, Composer, and Node.js are installed.
2. Set up a MySQL database for the project.

### **Steps**
1. Clone the repository:
   ```bash
   https://github.com/Thomas-DEV7/finwallet
   cd finwallet
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install Node.js dependencies:
   ```bash
   npm install
   npm run build
   ```

4. Set up the environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure the `.env` file with:
   - **Database credentials**
   - **Mail settings** (for password recovery emails)

6. Run database migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

7. Start the development server:
   ```bash
   php artisan serve
   ```

8. Access the application at:
   ```
   http://localhost:8000
   ```

---
## **Docker Setup**

### **Steps**
1. Build and start the Docker containers:
   ```bash
   docker-compose up -d --build
   ```

2. Install dependencies:
   ```bash
   docker exec -it finwallet-app composer install
   docker exec -it finwallet-app npm install
   docker exec -it finwallet-app npm run build
   ```

3. Set up the environment and application key:
   ```bash
   docker exec -it finwallet-app cp .env.example .env
   docker exec -it finwallet-app php artisan key:generate
   ```

4. Run database migrations and seeders:
   ```bash
   docker exec -it finwallet-app php artisan migrate --seed
   ```

5. Start Laravel's development server:
   ```bash
   docker exec -it finwallet-app php artisan serve --host=0.0.0.0 --port=8000
   ```

6. Access the application at:
   ```
   http://localhost:8000
   ```
---
# Users of system (Seeds) 
###  Important utility to run the system


emails: 
   
   'admin@exemplo.com' (ADMIN)

   'carlos@exemplo.com' (User)

   'ana@exemplo.com' (User)

   'maria@exemplo.com' (user)

password for all: 'secret'
---
## **Folder Structure**
```
- app/
  - Http/
    - Controllers/        # Application controllers
    - Middleware/         # Custom middlewares
  - Models/               # Eloquent models
- database/
  - migrations/           # Database migrations
  - seeders/              # Database seeders
- resources/
  - views/                # Blade templates
  - css/                  # Custom styles
  - js/                   # JavaScript files
- routes/
  - web.php               # Web routes
- public/
  - images/               # Static assets (e.g., logo)
```

---

## **Key Models**
### **User**
Represents users in the application.
- **Attributes:** `uuid`, `name`, `email`, `password`, `role`, `balance`.

### **Transaction**
Tracks all user transactions.
- **Attributes:** `uuid`, `user_id`, `sender_id`, `recipient_id`, `amount`, `type`, `related_transaction_id`.

### **ReversalRequest**
Handles requests for transaction reversals.
- **Attributes:** `uuid`, `user_uuid`, `transaction_uuid`, `comment`, `status`.

---

## **Core Routes**
### **User Routes**
| Method | Endpoint                  | Action                        |
|--------|---------------------------|-------------------------------|
| GET    | /dashboard                | Show user dashboard           |
| POST   | /wallet/deposit           | Handle deposit transactions   |
| POST   | /wallet/transfer          | Handle balance transfers      |
| POST   | /transactions/reversal-request | Submit reversal request |

### **Admin Routes**
| Method | Endpoint                          | Action                        |
|--------|-----------------------------------|-------------------------------|
| GET    | /admin/dashboard                  | Admin dashboard overview      |
| GET    | /admin/users                      | Manage users                  |
| POST   | /admin/reversal-requests/{uuid}/approve | Approve reversal request   |
| POST   | /admin/reversal-requests/{uuid}/reject  | Reject reversal request    |

---

## **Admin Dashboard**
Displays key metrics:
- Total users.
- Total transactions.
- Pending reversal requests.
- Logs of recent transactions (latest 10).

---

## **Development Notes**

### **Transactions**
- Transaction types include:
  - `deposit`: Adding funds to a user's account.
  - `transfer`: Moving funds between users.
  - `refund`: Reversal of a previous transaction.

### **Reversal Requests**
- Prevent duplicate requests for the same transaction.
- Admins can approve or reject requests.

### **Database Seeders**
- Creates initial users and transaction records for testing:
  - **Users:** Includes admin and multiple regular users.
  - **Transactions:** Various types to simulate real-world data.

---



<!-- ## **Screenshots**
### **User Dashboard**
![User Dashboard](path-to-user-dashboard.png)

### **Admin Dashboard**
![Admin Dashboard](path-to-admin-dashboard.png)

--- -->
<!-- 
## **Contributing**
1. Fork the repository.
2. Create a feature branch:
   ```bash
   git checkout -b feature-name
   ```
3. Commit and push your changes:
   ```bash
   git commit -m "Add feature"
   git push origin feature-name
   ```
4. Submit a pull request.

--- -->
<!-- 
## **License**
This project is licensed under the MIT License. -->


## **User Test**

### **Descrição**
O teste verifica a criação de um usuário no banco de dados, assegurando que a senha seja armazenada como um hash seguro.

### **Código**
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_user_successfully()
    {
        // Dados simulados de um usuário
        $userData = [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'), // Usando Hash::make
            'role' => 'user',
            'balance' => 500.00,
        ];

        // Criar o usuário
        $user = User::create($userData);

        // Verificar se o usuário foi criado corretamente no banco de dados
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'name' => 'Test User',
        ]);

        // Verificar se a senha foi armazenada como um hash
        $this->assertTrue(Hash::check('password123', $user->password));
    }
}
