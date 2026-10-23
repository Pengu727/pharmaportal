# PharmaPortal - Complete Testing Guide

## Server Status
✅ **Server**: http://127.0.0.1:8000

---

## Test Credentials

### 👥 Pharmacy Owners
| Email | Password | Pharmacy |
|-------|----------|----------|
| owner1@pharmacy.dz | password123 | Pharmacie Hydra Centre |
| owner2@pharmacy.dz | password123 | Pharmacie El Biar |

### 🏪 Pharmacy Sellers
| Email | Password | Assigned Pharmacy |
|-------|----------|-------------------|
| seller1@pharmacy.dz | password123 | Pharmacie Hydra Centre |
| seller2@pharmacy.dz | password123 | Pharmacie Hydra Centre |
| seller3@pharmacy.dz | password123 | Pharmacie El Biar |

### 🧑‍⚕️ Patient / Client
| Email | Password |
|-------|----------|
| patient@test.dz | password123 |

---

## Testing Flows

### 1️⃣ Client Registration & Login
1. Go to: **http://127.0.0.1:8000/client-register**
2. Fill in:
   - Nom: `TestPatient`
   - Prenom: `John`
   - Email: `testpatient@test.dz` (unique)
   - Phone: `0550123456`
   - Wilaya: `Alger`
   - Commune: `Hydra`
   - Date of Birth: `1995-05-15`
   - Password: `password123`
3. Click **Register Account**
4. ✅ Should redirect to login with success message
5. Login at: **http://127.0.0.1:8000/client-login**
   - Email: `testpatient@test.dz`
   - Password: `password123`
6. ✅ Should redirect to dashboard

### 2️⃣ Owner Inventory Management
1. Go to: **http://127.0.0.1:8000/owner-login**
2. Login with:
   - Email: `owner1@pharmacy.dz`
   - Password: `password123`
3. ✅ Should redirect to **http://127.0.0.1:8000/owner-dashboard**
4. Navigate to: **http://127.0.0.1:8000/owner/inventory**
5. **Add a Product:**
   - Click **➕ Add Product**
   - Fill in:
     - Name: `Aspirin 250mg`
     - SKU: `ASP-250-001`
     - Price: `250.00`
     - Stock: `100`
     - Description: `Pain reliever`
   - Click **Save Product**
   - ✅ Product should appear in table
6. **Edit a Product:**
   - Click **✏️ Edit** on any product
   - Change stock to `50`
   - Click **Save Changes**
   - ✅ Stock should update in table

### 3️⃣ Seller Point-of-Sale (POS)
1. Go to: **http://127.0.0.1:8000/seller-login**
2. Login with:
   - Email: `seller1@pharmacy.dz`
   - Password: `password123`
3. ✅ Should redirect to **http://127.0.0.1:8000/seller-dashboard**
4. Navigate to: **http://127.0.0.1:8000/seller/inventory**
5. ✅ Should see card-based view of all products from Pharmacie Hydra Centre
6. **Process a Sale:**
   - Click **✓ Sell Item** on any product (stock > 0)
   - ✅ Page refreshes with success message
   - ✅ Stock count decreases by 1
7. **Out of Stock Behavior:**
   - Keep clicking **✓ Sell Item** until stock reaches 0
   - ✅ Button becomes disabled with "Out of Stock" message

### 4️⃣ Role-Based Access Control
1. Try to access owner routes as a seller:
   - Login as `seller1@pharmacy.dz`
   - Go to: **http://127.0.0.1:8000/owner/inventory**
   - ✅ Should get 403 Forbidden error
2. Try to access without authentication:
   - Logout (POST to **/logout**)
   - Go to: **http://127.0.0.1:8000/owner/inventory**
   - ✅ Should redirect to login

### 5️⃣ Admin Dashboard (if needed)
1. Go to: **http://127.0.0.1:8000/admin/login**
2. Login with:
   - Email: `admin@pharmaportal.dz`
   - Password: `admin123`
3. ✅ Should see pharmacy management dashboard

---

## Route Summary

### 🌐 Public Routes
- `GET /` → Redirects to `/client-login`
- `GET /client-login` → Patient login page
- `POST /client-login` → Authenticate client (AuthController)
- `GET /client-register` → Patient registration page
- `POST /client-register` → Create patient account (AuthController)

### 🧑‍⚕️ Client/Patient Routes (Protected)
- `GET /client-dashboard` → Medication search dashboard
- `GET /client-categories` → Browse categories
- `GET /client-orders` → View orders/reservations
- `GET /client-product-view` → Product detail
- `GET /client-account-settings` → Profile management

### 🏪 Owner Routes (Protected, role:owner)
- `GET /owner-login` → Owner login page
- `GET /owner-dashboard` → Owner home
- `GET /owner/inventory` → View/manage products
- `POST /owner/inventory` → Add product
- `GET /owner/inventory/{id}/edit` → Edit product form
- `PUT /owner/inventory/{id}/update` → Save product changes

### 💼 Seller Routes (Protected, role:seller)
- `GET /seller-login` → Seller login page
- `GET /seller-dashboard` → Seller home
- `GET /seller/inventory` → POS/sales interface
- `POST /seller/inventory/{id}/decrement` → Process sale

### 🔐 Admin Routes (Protected, role:admin)
- `GET /admin/login` → Admin login
- `POST /admin/login` → Admin authentication
- `GET /admin/dashboard` → Pharmacy management
- `GET /admin/pharmacy/create` → Add pharmacy form
- `POST /admin/pharmacy/store` → Create pharmacy
- `GET /admin/pharmacy/{id}` → View pharmacy details
- `GET /admin/pharmacy/{id}/edit` → Edit pharmacy form
- `PUT /admin/pharmacy/{id}/update` → Save pharmacy

### 🚪 Utility
- `POST /logout` → End session

---

## Database

**Collections (MongoDB):**
- `users` - Clients, Owners, Sellers (all with `role` field)
- `products` - Medication inventory
- `pharmacies` - Pharmacy records (Admin)

**Test Data Populated:**
- 2 Owners
- 3 Sellers (distributed across 2 pharmacies)
- 1 Patient
- 36 Products (6 meds × 2 pharmacies, 50-100 stock each)

---

## Key Features to Test

✅ **Authentication**
- Registration with OTP flow (logs to console)
- Login with verification check
- Session-based auth for web flows
- Role-based middleware enforcement

✅ **Inventory Management**
- Owners can CRUD products
- Sellers can view only their pharmacy's products
- Stock counters visible to both
- Real-time stock updates on sales

✅ **Design System**
- Emerald green theme (#059669)
- Consistent Tailwind styling across all pages
- Responsive mobile/desktop layouts
- Professional UI matching design specs

✅ **Error Handling**
- Graceful messages for auth failures
- Proper redirects on unauthorized access
- Validation errors displayed inline

---

## Troubleshooting

### Server not starting?
```bash
cd /home/pengu/Documents/PFE/software/pharmacy-app
php artisan serve --host=127.0.0.1 --port=8000
```

### Need to reset database?
```bash
php artisan migrate:fresh --force
php artisan db:seed --class=PharmacyTestSeeder
```

### Want to add more test data?
Edit `database/seeders/PharmacyTestSeeder.php` and reseed.

---

## Next Steps (To Implement)

- [ ] OTP Email Verification UI
- [ ] Reservations & 24-hour Airlock system
- [ ] Geospatial search for nearest pharmacies
- [ ] Atomic stock decrements (MongoDB $inc)
- [ ] CSV product import for owners
- [ ] Payment integration
- [ ] Admin role enforcement for admin routes
- [ ] API token auth (Sanctum)
- [ ] Automated tests (PHPUnit/Pest)

---

**Happy Testing! 🎉**
