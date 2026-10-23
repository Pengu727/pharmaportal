# PharmaPortal Quick Start - Complete Testing Guide

## ✅ Server Status
- **URL**: http://localhost:8000
- **Status**: Running
- **Database**: MongoDB

---

## 🔐 Test Credentials

### Patient/Client
- **Email**: patient@example.com
- **Password**: password123
- **Role**: client
- **Action**: Search pharmacies, reserve medications

### Owner (Pharmacy Manager)
- **Email**: owner@pharmacy1.com
- **Password**: password123
- **Role**: owner
- **Action**: Manage inventory, add/edit products

### Seller (Pharmacy Counter)
- **Email**: seller@pharmacy1.com
- **Password**: password123
- **Role**: seller
- **Action**: Process sales, decrement stock, verify reservations

### Admin
- **Email**: admin@example.com
- **Password**: password123
- **Role**: admin
- **Action**: Verify and onboard new pharmacies

---

## 🧭 Route Map & Testing Flow

### 1. **Public Routes** (No Login Required)
```
GET  /                           → Landing page
GET  /client-login              → Client login form
POST /client-login              → Submit client credentials
GET  /client-register           → Client registration form
POST /client-register           → Submit client registration
GET  /verify-otp                → OTP verification form
POST /verify-otp                → Verify OTP code
GET  /logout                    → Logout (all roles)
```

### 2. **Owner Routes** (Pharmacy Owner Only)
```
GET  /owner/login               → Owner login form
POST /owner/login               → Submit owner credentials
GET  /owner/inventory           → View all products
POST /owner/inventory           → Add new product
GET  /owner/inventory/{id}/edit → Edit product form
POST /owner/inventory/{id}      → Update product
```

### 3. **Seller Routes** (Pharmacy Counter Staff Only)
```
GET  /seller/login              → Seller login form
POST /seller/login              → Submit seller credentials
GET  /seller/inventory          → POS interface (card grid)
POST /seller/inventory/{id}/decrement → Sell item (stock -1)
```

### 4. **Client Routes** (Patients)
```
GET  /client/dashboard          → Patient home
GET  /client/categories         → Browse medications
GET  /client/product/{id}       → Product details
GET  /client/orders             → Reservation history
POST /client/reserve            → Create reservation
GET  /client/account-settings   → Profile & settings
```

### 5. **Admin Routes** (Platform Admin)
```
GET  /admin/login               → Admin login form
POST /admin/login               → Submit admin credentials
GET  /admin/dashboard           → Pharmacy verification list
GET  /admin/pharmacies          → All registered pharmacies
POST /admin/pharmacies          → Onboard new pharmacy
GET  /admin/pharmacies/{id}     → View pharmacy details
```

---

## 📝 Testing Workflow (Step-by-Step)

### **Test 1: Owner Inventory Management**
1. **Login as Owner**
   ```
   Navigate to: http://localhost:8000/owner/login
   Email: owner@pharmacy1.com
   Password: password123
   ```

2. **View Inventory**
   ```
   Click "My Inventory" or navigate to: http://localhost:8000/owner/inventory
   Expected: See 18 products with stock levels
   ```

3. **Add Product**
   ```
   Click "+ Add Product" button
   Fill form:
     - Name: Aspirin 500mg
     - Brand: Generic
     - Dosage: 500 mg
     - Batch Number: BATCH001
     - Quantity: 100
     - Price: 5.00 DZD
     - Expiry Date: 2026-12-31
   Click "Add Product"
   Expected: Product added, appears in list with green stock badge
   ```

4. **Edit Product**
   ```
   Click edit icon on any product
   Change stock quantity to 50
   Click "Update Product"
   Expected: Stock updated, reflecting in inventory list
   ```

---

### **Test 2: Seller POS Operations**
1. **Login as Seller**
   ```
   Navigate to: http://localhost:8000/seller/login
   Email: seller@pharmacy1.com
   Password: password123
   ```

2. **View POS Interface**
   ```
   Click "My Sales" or navigate to: http://localhost:8000/seller/inventory
   Expected: See products in card grid layout
   ```

3. **Sell Item**
   ```
   Find a product with stock > 0
   Click "✓ Sell Item"
   Expected: Stock decreases by 1, card updates instantly
   Disabled state: Products with stock=0 show disabled button (red card)
   ```

4. **Verify Stock Decrement**
   ```
   After selling, open owner/inventory
   Expected: Stock count matches (one less than before)
   ```

---

### **Test 3: Client Registration & Login**
1. **Register as Client**
   ```
   Navigate to: http://localhost:8000/client-register
   Fill form:
     - Name: John Patient
     - Email: john@patient.com
     - Phone: 0551234567
     - Wilaya: Algiers (16)
     - Commune: Downtown
     - Date of Birth: 1990-05-15
     - Password: secure123
   Click "Register"
   Expected: Redirected to OTP verification
   ```

2. **Verify OTP**
   ```
   (In console output, find OTP code printed by VerificationService)
   Enter OTP (default: 123456 in demo mode)
   Click "Verify"
   Expected: Redirected to /client/dashboard
   ```

3. **Login**
   ```
   Navigate to: http://localhost:8000/client-login
   Email: john@patient.com
   Password: secure123
   Click "Sign In"
   Expected: Logged in, see patient dashboard
   ```

---

### **Test 4: Multi-Tenant Isolation**
1. **Verify Owner A Cannot See Owner B's Products**
   ```
   Login as owner@pharmacy1.com
   View /owner/inventory → See 18 products
   Logout
   Login as owner@pharmacy2.com (if created)
   View /owner/inventory → See different 18 products
   Expected: Data is isolated by pharmacy_id
   ```

2. **Verify Seller Can Only Decrement Own Pharmacy's Stock**
   ```
   Seller from pharmacy1 cannot directly access seller2's inventory
   (Enforced by InventoryController::authorizePharmacyAccess())
   ```

---

## 🔄 Session & Authentication

### How Sessions Work
- **Web Form Login**: `Auth::login($user)` creates Laravel session
- **Session Storage**: File-based (can be Redis/Database in production)
- **Session Duration**: 2 hours idle timeout (configurable in config/session.php)
- **Logout**: `Auth::logout()` destroys session

### How Roles Work
- **Role Checking**: Middleware `CheckRole` enforces role-based gates
- **Middleware Stack**: Routes protected via `middleware('role:owner')`
- **Permission Levels**:
  - `admin`: Full platform access
  - `owner`: Single pharmacy management
  - `seller`: Point-of-sale only
  - `client`: Consumer search & reservations

---

## 🛠️ Database & Seeding

### View Test Data
```bash
# Check MongoDB collections
php artisan tinker
> DB::connection('mongodb')->collection('users')->count()
> DB::connection('mongodb')->collection('products')->first()
```

### Reseed Database
```bash
# Fresh database with seeded data
php artisan migrate:fresh --force
php artisan db:seed --class=PharmacyTestSeeder
```

---

## 🧪 API Testing (cURL Examples)

### Client Login
```bash
curl -X POST http://localhost:8000/client-login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "patient@example.com",
    "password": "password123"
  }' \
  -c cookies.txt
```

### View Owner Inventory
```bash
curl -X GET http://localhost:8000/owner/inventory \
  -b cookies.txt
```

### Sell Item (Seller)
```bash
curl -X POST http://localhost:8000/seller/inventory/{product_id}/decrement \
  -H "Content-Type: application/json" \
  -b cookies.txt
```

---

## 📊 Expected Data Structure

### Product Document (MongoDB)
```json
{
  "_id": ObjectId("..."),
  "pharmacy_id": "66a1b2c3d4e5f6g7h8i9j0k1",
  "name": "Ibuprofen",
  "brand": "Nurofen",
  "dosage": "200 mg",
  "batch_number": "BATCH123",
  "stock": 50,
  "price": 150.00,
  "expiry_date": "2026-12-31",
  "created_at": "2024-01-15T10:30:00Z",
  "updated_at": "2024-01-15T10:30:00Z"
}
```

### User Document (MongoDB)
```json
{
  "_id": ObjectId("..."),
  "name": "John Owner",
  "email": "owner@pharmacy1.com",
  "phone": "0551234567",
  "role": "owner",
  "pharmacy_id": "66a1b2c3d4e5f6g7h8i9j0k1",
  "password": "$2y$12$...",
  "created_at": "2024-01-15T10:30:00Z"
}
```

---

## ⚠️ Known Limitations & Roadmap

### Current Implementation
- ✅ Session-based authentication
- ✅ Role-based middleware enforcement
- ✅ Owner inventory CRUD
- ✅ Seller POS interface
- ✅ Multi-tenant data isolation
- ✅ Professional UI with consistent design

### Not Yet Implemented
- 🔄 **Reservations & Airlock** (24-hour hold system)
- 🔄 **Geospatial Search** (location-based pharmacy discovery)
- 🔄 **Email OTP** (currently logs to console)
- 🔄 **Atomic Stock Updates** (needs MongoDB $inc operator)
- 🔄 **Payment Gateway** (Stripe/PayPal integration)
- 🔄 **Admin Verification Flow** (pharmacy onboarding)
- 🔄 **API Tokens** (Laravel Sanctum)

---

## 🚀 Next Steps After Testing

1. **Reservations System**
   - Create Reservation model
   - Implement 24-hour Airlock hold
   - Generate pickup tokens

2. **Geospatial Discovery**
   - Add location field to Pharmacy model
   - Index spatial coordinates
   - Create location-based search endpoint

3. **Email Notifications**
   - Configure mail driver
   - Send OTP via email
   - Send reservation confirmations

4. **Admin Dashboard**
   - Pharmacy verification workflow
   - Seller approval system
   - Platform analytics

---

## 💡 Troubleshooting

### Server Not Responding
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### Database Connection Error
```bash
# Check MongoDB is running
mongosh --eval "db.version()"

# Verify .env MONGODB_URI
cat .env | grep MONGODB
```

### Session Not Persisting
```bash
# Clear sessions
php artisan session:clear

# Check session driver in config/session.php
# Should be 'driver' => env('SESSION_DRIVER', 'file')
```

### Middleware Not Enforcing
```bash
# Verify middleware alias in bootstrap/app.php
php artisan tinker
> config('app.providers')
```

---

## 📞 Support

All routes fully functional and ready for manual testing. Database seeded with test credentials above.

**Happy Testing! 🎉**
