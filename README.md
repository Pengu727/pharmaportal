# PharmaPortal

<p align="center">
  <img src="public/images/pharmaportal.png" alt="PharmaPortal Logo" width="180">
</p>

<p align="center">
  <strong>Pharmacy Availability & Inventory Management Platform</strong><br>
  Find medications, reserve them online, and manage pharmacy stock from a single platform.
</p>

---

## About PharmaPortal

PharmaPortal is a Laravel web application built for the Algerian pharmacy market. It connects clients looking for medications with nearby pharmacies, while giving pharmacy owners and sellers the tools to manage inventory, stock levels, expiration dates, reservations, and sales.

The platform has four user roles:

| Role | Purpose |
| --- | --- |
| **Administrator** | Creates and manages pharmacies and their owner accounts |
| **Owner** | Manages the pharmacy inventory, sellers, reservations, and sales |
| **Seller** | Handles point-of-sale operations, stock decrements, and reservation pickup |
| **Client** | Searches products, finds nearby pharmacies, and reserves medications |

### Main features

- Multi-role authentication with role-protected dashboards
- Pharmacy management with geolocation coordinates
- Inventory management with stock, price, expiration date, and visibility control
- Public and private products (private items are hidden from clients)
- Product search with relevance scoring and autocomplete suggestions
- "Near Me" pharmacy discovery using the Haversine distance formula
- Reservation system with a unique 6-digit pickup code
- Automatic reservation expiry after 24 hours with stock restoration
- Seller POS stock decrements and reservation claim verification
- Sales history for owners and sellers
- Low-stock, expiring-soon, and expired product alerts
- Email OTP verification for client accounts

### Technologies

- **Backend:** Laravel 13, PHP 8.3+
- **Database:** MongoDB (via `mongodb/laravel-mongodb`)
- **Frontend:** Blade templates, Tailwind CSS, Vite
- **Authentication:** Laravel multi-guard authentication and custom role middleware
- **Scheduling:** Laravel scheduler and Artisan commands
- **Mail:** Laravel Mail with Mailable classes

---

## Requirements

Make sure the following are installed before running the project:

- PHP 8.3 or newer
- Composer
- Node.js and npm
- MongoDB running locally on `127.0.0.1:27017`
- PHP MongoDB extension (`ext-mongodb`)

Check the PHP extension with:

```bash
php -m | grep mongodb
```

---

## Run the Application

### 1. Install PHP dependencies

```bash
composer install
```

### 2. Create the environment file

```bash
cp .env.example .env
```

Open `.env` and configure the application and MongoDB connection:

```env
APP_NAME=PharmaPortal
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27017
DB_DATABASE=pharmacy_management
DB_USERNAME=
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@pharmaportal.dz"
MAIL_FROM_NAME="${APP_NAME}"
```

Notes:

- The application stores its data in MongoDB, so `DB_CONNECTION` must be `mongodb`.
- `SESSION_DRIVER`, `CACHE_STORE`, and `QUEUE_CONNECTION` are set to local drivers so the application can run without additional database tables.
- With `MAIL_MAILER=log`, verification OTP codes are written to `storage/logs/laravel.log`. Configure SMTP credentials instead if you want real emails.

### 3. Generate the application key

```bash
php artisan key:generate
```

### 4. Install and build frontend assets

```bash
npm install
npm run build
```

For development with hot reloading, use `npm run dev` in a separate terminal instead of `npm run build`.

### 5. Start MongoDB

Make sure the MongoDB service is running:

```bash
sudo systemctl start mongod
```

Or start it manually with your preferred MongoDB setup. The default connection expects `127.0.0.1:27017`.

### 6. Start the Laravel development server

```bash
php artisan serve
```

Open the application at:

```text
http://127.0.0.1:8000
```

### 7. Create the first administrator

On a fresh database there is no administrator account yet. Create one with Tinker:

```bash
php artisan tinker
```

```php
\App\Models\Admin::create([
    'nom' => 'System',
    'prenom' => 'Administrator',
    'email' => 'admin@pharmaportal.dz',
    'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
    'num_tel' => '0550000000',
]);
```

Then log in at:

```text
http://127.0.0.1:8000/admin/login
```

Use `admin@pharmaportal.dz` / `admin123`. Change these credentials after the first login in a real deployment.

---

## First-Time Setup Flow

1. Log in as the administrator at `/admin/login`.
2. Create a pharmacy from the admin dashboard. The administrator form also creates the pharmacy owner account.
3. Log in as the owner at `/owner-login` and add products, or seed demo inventory.
4. Create seller accounts from the owner's **Sellers** page.
5. Log in as a seller at `/seller-login` to process sales and validate reservations.
6. Clients can register at `/client-register`, verify their email, and browse products.

The guest catalog is available at:

```text
http://127.0.0.1:8000/guest-products
```

---

## Optional Demo Data

### Seed realistic inventory

After at least one pharmacy exists:

```bash
php artisan db:seed-inventory
```

This creates 10 to 20 realistic medication products per pharmacy.

To append more products instead of replacing existing ones:

```bash
php artisan db:seed-additional-products
```

### Seed test users and products

```bash
php artisan db:seed --class=PharmacyTestSeeder
```

Test accounts created by this seeder:

| Role | Email | Password |
| --- | --- | --- |
| Owner | `owner1@pharmacy.dz` | `password123` |
| Owner | `owner2@pharmacy.dz` | `password123` |
| Seller | `seller1@pharmacy.dz` | `password123` |
| Seller | `seller2@pharmacy.dz` | `password123` |
| Seller | `seller3@pharmacy.dz` | `password123` |
| Client | `patient@test.dz` | `password123` |

---

## Automatic Reservation Expiry

Pending reservations expire 24 hours after creation and their stock is restored automatically. The expiry command is scheduled every minute in `routes/console.php`:

```php
Schedule::command('reservations:expire')->everyMinute();
```

To run the scheduler locally, start it in a separate terminal:

```bash
php artisan schedule:work
```

You can also run the expiry command manually:

```bash
php artisan reservations:expire
```

---

## Main Routes

| Route | Description |
| --- | --- |
| `/guest-products` | Public product catalog |
| `/client-register` | Client registration |
| `/client-verify` | Email OTP verification |
| `/client-login` | Login for clients, owners, and sellers |
| `/client-dashboard` | Client product search |
| `/client-nearme` | Pharmacies near the client |
| `/client-orders` | Client reservations |
| `/owner-login` | Owner login |
| `/owner/inventory` | Owner inventory management |
| `/owner/reservations` | Owner reservation list |
| `/owner/sellers` | Owner seller management |
| `/owner/sales` | Owner sales history |
| `/seller-login` | Seller login |
| `/seller/inventory` | Seller point of sale |
| `/seller/reservations` | Seller reservation validation |
| `/seller/sales` | Seller sales history |
| `/admin/login` | Administrator login |
| `/admin/dashboard` | Pharmacy management |

---

## Project Structure

```text
app/
  Console/Commands/       Reservation expiry and inventory seeding commands
  Http/Controllers/       Application controllers
  Http/Middleware/        Role-based access middleware
  Mail/                   Verification email classes
  Models/                 MongoDB models (Admin, User, Pharmacy, Product, Reservation, Sale)
  Services/               Product search and verification services
resources/views/
  admin/                  Administrator views
  client/                 Client views
  dashboard/owner/        Owner dashboard views
  dashboard/seller/       Seller dashboard views
  guest/                  Public catalog views
  emails/                 Email templates
routes/
  web.php                 Web routes and role-protected route groups
  api.php                 Public API routes
  console.php             Scheduled commands
public/images/
  pharmaportal.png        PharmaPortal logo
```

---

## Testing

Run the test suite with:

```bash
php artisan test
```

or:

```bash
composer test
```
