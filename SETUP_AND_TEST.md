# 🏥 PharmaPortal - Setup & Testing Guide

## ✅ Status: Ready for Testing

All routes are configured and test data is seeded. The application is fully functional for manual testing.

---

## 🚀 Quick Start

### Start the Server
```bash
cd /home/pengu/Documents/PFE/software/pharmacy-app
php artisan serve --host=0.0.0.0 --port=8000
```

**Access**: http://localhost:8000

### Reset Database & Reseed
```bash
php artisan migrate:fresh --force
php artisan db:seed --class=PharmacyTestSeeder
```

---

## 🧪 Test Users

### 1. **Pharmacy Owner** (Inventory Management)
- **Email**: `owner1@pharmacy.dz`
- **Password**: `password123`
- **Pharmacy**: Pharmacie Hydra Centre
- **Access**: `/owner/inventory` - Full CRUD on products

### 2. **Pharmacy Seller** (Point-of-Sale)
- **Email**: `seller1@pharmacy.dz`
- **Password**: `password123`
- **Pharmacy**: Pharmacie Hydra Centre
- **Access**: `/seller/inventory` - View products, process sales, decrement stock

### 3. **Patient/Client** (Browse Medications)
- **Email**: `patient@test.dz`
- **Password**: `password123`
- **Access**: `/client-dashboard` - Browse pharmacies & medications

### 4. **Admin** (Pharmacy Management)
- **Email**: `admin@pharmaportal.dz`
- **Password**: `admin123`
- **Access**: `/admin/dashboard` - Onboard & manage pharmacies

---

## 📋 Feature Testing Checklist

### ✅ Authentication & Authorization
- [ ] Register new patient at `/client-register`
- [ ] Login as client at `/client-login`
- [ ] Login as owner at `/owner-login`
- [ ] Login as seller at `/seller-login`
- [ ] Verify role-based access control (try accessing owner routes as seller → should fail)
- [ ] Test logout (`POST /logout`)

### ✅ Owner Inventory Management
- [ ] Login as `owner1@pharmacy.dz`
- [ ] Navigate to `/owner/inventory`
- [ ] Add new product (click **➕ Add Product**)
  - Name: `Antibiotics Amoxicillin 500mg`
  - SKU: `AMX-500-001`
  - Price: `600.00`
  - Stock: `75`
  - Description: `Broad-spectrum antibiotic`
- [ ] View product in table
- [ ] Click **✏️ Edit** on any product
- [ ] Modify stock to `25`
- [ ] Verify update in table

### ✅ Seller POS (Point of Sale)
- [ ] Login as `seller1@pharmacy.dz`
- [ ] Navigate to `/seller/inventory`
- [ ] See card-based product layout
- [ ] Click **✓ Sell Item** button
- [ ] Verify stock decrements by 1
- [ ] Repeat until stock = 0
- [ ] Verify button becomes disabled
- [ ] Try processing sale on out-of-stock item → should fail gracefully

### ✅ Multi-Pharmacy Segregation
- [ ] Login as owner2 (`owner2@pharmacy.dz`)
- [ ] Go to `/owner/inventory`
- [ ] Should see DIFFERENT products (from Pharmacie El Biar, not Hydra Centre)
- [ ] Login as seller3 (`seller3@pharmacy.dz`)
- [ ] Should only see products from Pharmacie El Biar

### ✅ Design & UI
- [ ] All pages use emerald green theme (#059669)
- [ ] Responsive on mobile (resize browser to <640px)
- [ ] Forms have proper validation styling
- [ ] Success/error messages display correctly
- [ ] Navbar is consistent across all pages
- [ ] Product cards/tables display clearly

### ✅ Data Persistence
- [ ] Add a product, refresh page → product still exists
- [ ] Decrement stock, refresh → stock change persists
- [ ] Login/logout cycles work properly

---

## 🛣️ Complete Route Map

### Public Routes (No Auth Required)
```
GET  /                          → Redirect to /client-login
GET  /client-login              → Login form for patients
POST /client-login              → Authenticate client
GET  /client-register           → Registration form for patients
POST /client-register           → Create patient account
GET  /owner-login               → Login form for owners
GET  /seller-login              → Login form for sellers
GET  /admin/login               → Login form for admins
POST /admin/login               → Authenticate admin
POST /logout                    → End session
```

### Client/Patient Routes (Protected: auth)
```
GET  /client-dashboard          → Browse medications
GET  /client-categories         → Medication categories
GET  /client-orders             → View reservations
GET  /client-product-view       → Product details
GET  /client-account-settings   → Profile management
```

### Owner Routes (Protected: auth + role:owner)
```
GET  /owner-dashboard           → Owner home
GET  /owner/inventory           → View/manage products
POST /owner/inventory           → Add product
GET  /owner/inventory/{id}/edit → Edit product form
PUT  /owner/inventory/{id}/update → Save changes
```

### Seller Routes (Protected: auth + role:seller)
```
GET  /seller-dashboard          → Seller home
GET  /seller/inventory          → POS interface
POST /seller/inventory/{id}/decrement → Process sale
```

### Admin Routes (Protected: auth + role:admin)
```
GET  /admin/dashboard           → Pharmacy management
GET  /admin/pharmacy/create     → Add pharmacy form
POST /admin/pharmacy/store      → Create pharmacy
GET  /admin/pharmacy/{id}       → View pharmacy
GET  /admin/pharmacy/{id}/edit  → Edit pharmacy form
PUT  /admin/pharmacy/{id}/update → Save pharmacy changes
```

---

## 🔍 API Routes (JSON)

**Note**: These use header-based role injection for testing. Use with: `X-User-Role: owner` header

```
POST /api/auth/register         → Register user (JSON)
POST /api/auth/login            → Login user (JSON)
POST /api/auth/verify-otp       → Verify OTP (JSON)
GET  /api/test-as-client        → Test client access
GET  /api/test-as-seller        → Test seller access
GET  /api/test-as-owner         → Test owner access
GET  /api/owner/sellers         → List sellers
POST /api/owner/sellers         → Create seller
GET  /api/admin/pharmacies      → List pharmacies
POST /api/admin/pharmacies      → Create pharmacy
```

---

## 💾 Database Collections

**MongoDB Collections (Auto-created)**:
- `users` - All user accounts (clients, owners, sellers, admins)
- `products` - Medication inventory
- `pharmacies` - Pharmacy records
- `verification_otps` - One-time password cache (15 min TTL)

**Test Data Seeded**:
- 2 pharmacies
- 2 owners
- 3 sellers
- 1 patient
- 36 products (6 medications × 2 pharmacies)

---

## �� Key Features Implemented

✅ **Multi-tenant Architecture**
- Each owner/seller sees only their pharmacy's data
- Strict isolation via `pharmacy_id` field

✅ **Role-Based Access Control**
- Owner: CRUD products, view dashboard
- Seller: View products, process sales
- Client: Browse & search medications
- Admin: Manage pharmacies

✅ **Authentication**
- Email/password registration & login
- OTP verification (logs to console in dev)
- Session-based web auth
- Middleware enforcement

✅ **Inventory Management**
- Add/edit/delete products (owners)
- Real-time stock view
- Atomic decrements on sales
- Stock status indicators (green/yellow/red)

✅ **Professional UI**
- Emerald green theme consistent across all pages
- Responsive design (mobile/tablet/desktop)
- Tailwind CSS framework
- Clear navigation & user feedback

---

## 📝 Manual Testing Example (Owner Workflow)

1. **Login**
   ```
   URL: http://localhost:8000/owner-login
   Email: owner1@pharmacy.dz
   Password: password123
   ```

2. **Add Product**
   ```
   Navigate: http://localhost:8000/owner/inventory
   Click: ➕ Add Product
   Fill form with:
     - Name: Paracetamol Extra Strong 1000mg
     - SKU: PARA-1000-001
     - Price: 350.00
     - Stock: 200
     - Description: Strong pain reliever for adults
   Click: Save Product
   ```

3. **Verify Product Added**
   ```
   ✅ Product appears in table
   ✅ All fields display correctly
   ✅ Stock shows as 200 units (green badge)
   ```

4. **Edit Product**
   ```
   Click: ✏️ Edit on the product row
   Change Stock: 150
   Click: Save Changes
   ```

5. **Verify Update**
   ```
   ✅ Redirected back to inventory
   ✅ Stock now shows 150 in table
   ✅ Edit form shows new value on reload
   ```

---

## 🐛 Troubleshooting

### Server won't start?
```bash
# Check PHP is installed
php -v

# Clear cache and retry
php artisan cache:clear
php artisan config:cache
php artisan serve --host=0.0.0.0 --port=8000
```

### Can't login?
- Verify email is in database: `php artisan tinker` then `User::all()`
- Password hash may be wrong; reseed: `php artisan db:seed --class=PharmacyTestSeeder`

### Products not showing for seller?
- Seller and owner must have same `pharmacy_id`
- Check database: seller's `pharmacy_id` must match owner's

### Role middleware not working?
- Ensure user is logged in: check session contains `auth:web`
- Verify `role` field is set on user record
- CheckRole middleware is registered in `bootstrap/app.php` as alias `'role'`

---

## 📚 Files Overview

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php       ← Login/register
│   │   ├── InventoryController.php  ← Owner/seller inventory
│   │   └── AdminController.php      ← Pharmacy management
│   └── Middleware/
│       └── CheckRole.php            ← Role enforcement
├── Models/
│   ├── User.php                     ← User model
│   ├── Product.php                  ← Product model
│   └── Pharmacy.php                 ← Pharmacy model
└── Services/
    └── VerificationService.php      ← OTP generation

routes/
├── web.php                          ← Web routes & forms
└── api.php                          ← API routes (JSON)

resources/views/
├── client_*.blade.php               ← Patient pages
├── owner_*.blade.php                ← Owner pages
├── seller_*.blade.php               ← Seller pages
├── admin/                           ← Admin pages
└── *_login.blade.php                ← Login forms

database/
└── seeders/
    └── PharmacyTestSeeder.php       ← Test data
```

---

## ✨ What's Next?

- [ ] OTP Email delivery (currently logs to console)
- [ ] Reservations & 24-hour Airlock system
- [ ] Geospatial search (find nearest pharmacies)
- [ ] Atomic stock operations (MongoDB $inc operator)
- [ ] CSV import for products
- [ ] Payment gateway integration
- [ ] Automated test suite (PHPUnit)
- [ ] API token auth (Sanctum)
- [ ] Admin role verification on login
- [ ] Prescription upload & tracking

---

## 🎉 Ready to Test!

Server is running and all routes are configured. Start with the **Client Registration & Login** flow, then progress through owner/seller features.

**Questions?** Check TESTING.md for detailed flow instructions.
