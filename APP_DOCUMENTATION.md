# PharmaPortal — Complete System Documentation

## 📋 Table of Contents
1. [Project Overview](#project-overview)
2. [Architecture](#architecture)
3. [Database Models](#database-models)
4. [User Roles & Permissions](#user-roles--permissions)
5. [Core Features](#core-features)
6. [API Routes](#api-routes)
7. [Technology Stack](#technology-stack)
8. [Project Structure](#project-structure)
9. [Setup & Testing](#setup--testing)
10. [Key Implementation Details](#key-implementation-details)

---

## 📱 Project Overview

**PharmaPortal** is a multi-tenant, intelligent pharmacy management and medication discovery platform designed to bridge the gap between healthcare providers and consumers. The system provides real-time inventory tracking, geospatial medication discovery, and automated reservation workflows with a 24-hour airlock holding system.

### Core Mission
- Enable patients to find medications near them in real-time
- Provide pharmacies with inventory management tools
- Automate medication reservations with pickup verification
- Ensure multi-tenant data isolation and security

### Key Differentiators
✅ **Multi-tenant architecture** — Independent pharmacy data isolation  
✅ **Role-based access control** — 4 distinct system roles  
✅ **24-hour Airlock** — Automated reservation expiration system  
✅ **Geospatial discovery** — Location-based pharmacy search  
✅ **OTP verification** — Email-based client authentication  
✅ **6-digit pickup codes** — Secure in-person confirmation  

---

## 🏗️ Architecture

### 3-Layer Design

```
┌─────────────────────────────────────────────────────────┐
│                   Frontend Layer (Blade/HTML)           │
├─────────────────────────────────────────────────────────┤
│  Guest  │  Client Auth  │  Client Dashboard  │  Admin   │
│         │   Buyer       │   Pharmacy Mgmt    │  Console │
├─────────────────────────────────────────────────────────┤
│                   Business Logic (Controllers)           │
├─────────────────────────────────────────────────────────┤
│ AuthController │ GuestController │ InventoryController │
│ ReservationCtl │ AdminController │ CategoryController  │
├─────────────────────────────────────────────────────────┤
│                  Data Layer (MongoDB)                    │
├─────────────────────────────────────────────────────────┤
│ users │ pharmacies │ products │ reservations │ admins  │
└─────────────────────────────────────────────────────────┘
```

### Multi-Tenant Isolation Strategy

Each pharmacy operates as an isolated tenant:
- Every entity (product, seller, stock) linked to `pharmacy_id`
- Queries automatically filtered by user's pharmacy context
- Middleware enforces role-based access at route level
- No cross-tenant data leakage possible

---

## 📊 Database Models

### 1. **User Model** (MongoDB: `users` collection)
```
{
  _id: ObjectId,
  nom: String,
  prenom: String,
  email: String (unique),
  phone: String,
  password: String (hashed),
  role: String (client|owner|seller),
  pharmacy_id: String (foreign ref),
  wilaya: String,
  commune: String,
  is_verified: Boolean,
  verification_otps: Array,
  created_at: DateTime,
  updated_at: DateTime
}
```
**Relationships:** One pharmacy → Many users (owner + sellers + patients)

### 2. **Pharmacy Model** (MongoDB: `pharmacies` collection)
```
{
  _id: ObjectId,
  nom: String,
  email: String,
  num_tel: String,
  wilaya: String,
  role: String (always 'owner'),
  is_verified: Boolean,
  role_profile: {
    nom_pharmacie: String,
    registre_commerce: String,
    heure_ouverture: Time,
    heure_fermeture: Time,
    adresse_complete: String,
    google_maps_link: String
  },
  created_at: DateTime
}
```
**Relationships:** One pharmacy → Many products, Many sellers

### 3. **Product Model** (MongoDB: `products` collection)
```
{
  _id: ObjectId,
  pharmacy_id: String,
  name: String,
  brand: String,
  dosage: String,
  batch_number: String,
  stock: Integer,
  price: Float,
  expiry_date: Date,
  description: String,
  is_prescription_required: Boolean,
  created_at: DateTime,
  updated_at: DateTime
}
```
**Relationships:** Many products → One pharmacy

### 4. **Reservation Model** (MongoDB: `reservations` collection)
```
{
  _id: ObjectId,
  user_id: String,
  product_id: String,
  pharmacy_id: String,
  product_name: String,
  pharmacy_name: String,
  confirmation_code: String (6-digit),
  status: String (pending|confirmed|expired|claimed),
  created_at: DateTime,
  expires_at: DateTime (24 hours from creation),
  claimed_at: DateTime (nullable)
}
```
**Statuses:**
- `pending` — Reservation created, waiting for client confirmation
- `confirmed` — Client confirmed, ready for pickup
- `expired` — 24 hours passed without claim
- `claimed` — Seller verified code, transaction complete

### 5. **Admin Model** (MongoDB: `admins` collection)
```
{
  _id: ObjectId,
  nom: String,
  prenom: String,
  email: String (unique),
  password: String (hashed),
  role: String (always 'admin'),
  num_tel: String,
  created_at: DateTime
}
```

---

## 👥 User Roles & Permissions

### 1. **Platform Administrator**
**Operates at:** Global cluster level  
**Permissions:**
- ✅ Verify and onboard new pharmacy owners
- ✅ View all registered pharmacies
- ✅ Edit pharmacy information
- ✅ Access admin dashboard
- ❌ Cannot manage inventory or sales

**Access Points:**
- `/admin/login` — Login page
- `/admin/dashboard` — Pharmacy verification list
- `/admin/pharmacy/{id}` — Pharmacy details
- `/admin/pharmacy/create` — Onboard new pharmacy

---

### 2. **Pharmacy Owner**
**Operates at:** Single pharmacy (tenant) level  
**Permissions:**
- ✅ Add/edit/delete products
- ✅ Manage stock levels
- ✅ Add and revoke seller credentials
- ✅ View seller performance
- ✅ Generate seller invite links
- ❌ Cannot process sales directly
- ❌ Cannot access other pharmacies' data

**Access Points:**
- `/owner-login` — Login page
- `/owner/inventory` — Product management
- `/owner/inventory/create` — Add new product
- `/owner/inventory/{id}/edit` — Edit product
- `/owner/sellers` — Manage sales staff

---

### 3. **Pharmacy Seller**
**Operates at:** Point-of-sale level  
**Permissions:**
- ✅ View available inventory
- ✅ Decrement stock on sales
- ✅ Verify 6-digit reservation codes
- ✅ Confirm client pickups
- ❌ Cannot modify prices
- ❌ Cannot add products
- ❌ Cannot access other pharmacies

**Access Points:**
- `/seller-login` — Login page
- `/seller/inventory` — POS interface (card grid)
- `/seller/inventory/{id}/decrement` — Process sale
- `/seller/reservations/verify` — Claim reservation

---

### 4. **Public Client / Patient**
**Operates at:** Consumer level  
**Permissions:**
- ✅ Browse all medications (guest mode)
- ✅ Register and create account
- ✅ Search medications by name/dosage
- ✅ View product details & pharmacy info
- ✅ Create 24-hour reservations
- ✅ View reservation history
- ✅ Verify pickup in-person with code
- ❌ Cannot modify products
- ❌ Cannot see seller/owner interfaces

**Access Points:**
- `/` — Guest browse page
- `/client-register` — Registration form
- `/client-login` — Login form
- `/client/dashboard` — Patient home
- `/client/product/{id}` — Product details
- `/client/reservations` — Reservation history
- `/api/guest/products` — Lazy-load products (paginated)

---

## 🚀 Core Features

### 1. **Authentication & Authorization**

#### Client Registration (with OTP)
```
Flow: Registration → OTP Sent → OTP Verification → Authenticated Session
```
- Email validation
- Password hashing (bcrypt)
- 6-digit OTP generated + stored (15-min TTL)
- Session-based auth for web
- Role-based middleware enforcement

**Status:** ✅ Implemented
- `AuthController@register` handles registration
- `VerificationService` generates OTP
- Email logging (currently console; can integrate with Mailgun/SES)

#### Owner/Seller/Admin Login
- Email + password
- Immediate session creation (no OTP required)
- Role-based route protection

---

### 2. **Guest Product Browsing**

Unauthenticated users can:
- Browse all medications across all pharmacies
- Search by product name, dosage, brand
- View basic product info (name, price, stock status)
- **Clicking product detail requires login redirect**

**Implementation:**
- `GuestController@browse()` serves guest page
- `GuestController@productsApi()` provides paginated API
- Lazy-loading: 10 products per page (infinite scroll)
- Search filtering on backend (efficient)

**Status:** ✅ Implemented
- Guest page at `/` (view: `guest/products.blade.php`)
- API endpoint: `/api/guest/products?page=1&search=term`

---

### 3. **Inventory Management (Owner)**

Owners manage their pharmacy's product catalog:

**CRUD Operations:**
- ✅ **Create:** Add new product with batch info, pricing, expiry
- ✅ **Read:** View all products in table format
- ✅ **Update:** Edit stock, price, batch info
- ✅ **Delete:** Remove discontinued products (soft delete planned)

**Status Display:**
- Green badge: stock > 20
- Yellow badge: 0 < stock ≤ 20
- Red badge: stock = 0 (out of stock)

**Implementation:**
- `InventoryController@ownerIndex()` lists products
- `InventoryController@ownerStore()` creates product
- `InventoryController@ownerEdit()` shows edit form
- `InventoryController@ownerUpdate()` persists changes
- Multi-tenant filtering: `pharmacy_id` field

**Status:** ✅ Implemented
- Views: `dashboard/owner/inventory.blade.php`, `inventory_edit.blade.php`
- Routes: `/owner/inventory` (GET/POST), `/owner/inventory/{id}/edit` (GET/POST)

---

### 4. **Point-of-Sale (Seller)**

Sellers process counter sales and manage real-time stock:

**Features:**
- Card-based product grid (visual POS interface)
- One-click "Sell Item" button per product
- Real-time stock decrement
- Visual feedback (disabled buttons on out-of-stock)

**Current Implementation:**
- Simple stock decrement: `$product->stock = max(0, $product->stock - 1)`
- ⚠️ **Issue:** Not atomic; potential race conditions if multiple sellers decrement simultaneously
- **Fix Needed:** Use MongoDB `$inc` operator for atomic updates

**Implementation:**
- `InventoryController@sellerIndex()` displays POS grid
- `InventoryController@decrement()` decrements stock
- View: `dashboard/seller/inventory.blade.php`

**Status:** ⚠️ Partially Implemented (not atomic)
- Routes: `/seller/inventory` (GET), `/seller/inventory/{id}/decrement` (POST)

---

### 5. **24-Hour Airlock Reservation System**

Core feature: Temporary medication holds with auto-expiration

**Workflow:**
```
Client Creates Reservation (locked stock)
           ↓ (24-hour window opens)
  Seller Verifies 6-digit Code
           ↓
   Reservation Claimed (transaction complete)
           
If not claimed in 24h → Auto-expires, stock unlocked
```

**Reservation States:**
1. **pending** — Created, awaiting client confirmation
2. **confirmed** — Client showed up, waiting for seller verification
3. **claimed** — Seller scanned code, transaction complete
4. **expired** — 24 hours passed, automatic rollback

**Implementation:**
- `ReservationController@store()` creates reservation
- `ReservationController@confirm()` marks as confirmed
- `ReservationController@claim()` validates code & marks claimed
- Automatic expiration: checked on every view (lazy evaluation)
- 6-digit code: `str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT)`

**Status:** ✅ Implemented
- Model: `Reservation`
- Routes: `/api/reservations` (POST), `/api/reservations/{id}/confirm` (POST), `/api/reservations/claim` (POST)
- View: `client/reservations.blade.php`

**Improvements Needed:**
- Background job to expire old reservations (Laravel Scheduler)
- Seller interface to scan/verify codes (QR code reading)

---

### 6. **Product Search & Lazy Loading**

Efficient product discovery without loading entire database:

**Search Capabilities:**
- Search by product name
- Search by dosage
- Search by brand
- Multi-field OR query

**Pagination:**
- 10 products per page (configurable)
- Infinite scroll UI
- Skip-based pagination (offset/limit)

**Implementation:**
```php
// GuestController@productsApi()
$query = Product::query();
if ($search) {
    $query->where(function ($q) use ($search) {
        $q->where('name', 'like', "%$search%")
          ->orWhere('dosage', 'like', "%$search%")
          ->orWhere('brand', 'like', "%$search%");
    });
}
$products = $query->paginate(10, ['*'], 'page', $page);
```

**Status:** ✅ Implemented
- API: `/api/guest/products?page=1&search=term`
- Frontend: JavaScript infinite scroll in `guest/products.blade.php`

---

### 7. **Pharmacy Information & Mapping**

Each pharmacy displays:
- Business name & registration number
- Operating hours (open/close times)
- Address
- Google Maps link with red marker

**Current Issue:**
- Maps embedding has had multiple attempts (coordinates, embed URLs)
- Current workaround: Direct Google Maps links
- **Solution:** Store Google Maps link, display as clickable button

**Implementation:**
- `role_profile['google_maps_link']` stored per pharmacy
- Admin form accepts Google Maps link
- Display: "Open in Google Maps" button

**Status:** ✅ Basic Implementation (link-based)
- Still need: Embedded map with marker on pharmacy detail page

---

## 🛣️ API Routes

### Public Routes (No Auth)
```
GET  /                           Landing/Guest browse
GET  /client-login              Client login form
POST /client-login              Submit credentials
GET  /client-register           Registration form
POST /client-register           Submit registration
GET  /verify-otp                OTP entry page
POST /verify-otp                Verify code
GET  /logout                    Destroy session

GET  /api/guest/products        Lazy-load products (paginated)
```

### Client Routes (Auth Required: role=client)
```
GET  /client/dashboard          Home page
GET  /client/categories         Browse by category
GET  /client/product/{id}       Product details
GET  /client/reservations       Reservation history
GET  /client/account-settings   Profile settings
POST /api/reservations          Create reservation
POST /api/reservations/{id}/confirm   Confirm reservation
POST /api/reservations/claim    Seller verifies code
```

### Owner Routes (Auth Required: role=owner)
```
GET  /owner-login               Login form
POST /owner-login               Submit credentials
GET  /owner/inventory           Product list (table)
POST /owner/inventory           Add new product
GET  /owner/inventory/{id}/edit Edit form
POST /owner/inventory/{id}      Update product
GET  /owner/sellers             Manage sellers
POST /owner/sellers             Add new seller
DELETE /owner/sellers/{id}      Remove seller
```

### Seller Routes (Auth Required: role=seller)
```
GET  /seller-login              Login form
POST /seller-login              Submit credentials
GET  /seller/inventory          POS interface (card grid)
POST /seller/inventory/{id}/decrement   Sell item
GET  /seller/reservations       View codes to verify
POST /seller/reservations/{id}/claim    Verify code
```

### Admin Routes (Auth Required: role=admin)
```
GET  /admin/login               Login form
POST /admin/login               Submit credentials
GET  /admin/dashboard           Pharmacy verification list
GET  /admin/pharmacy/create     Onboard form
POST /admin/pharmacy/store      Save new pharmacy
GET  /admin/pharmacy/{id}       Pharmacy details
GET  /admin/pharmacy/{id}/edit  Edit form
POST /admin/pharmacy/{id}       Update pharmacy
```

---

## 🛠️ Technology Stack

### Backend
| Component | Technology | Version |
|-----------|-----------|---------|
| **Framework** | Laravel | 11.x |
| **Database** | MongoDB | 6.x |
| **ORM** | Laravel MongoDB | latest |
| **Auth** | Laravel Session + Custom Guards | native |
| **Validation** | Laravel Validation | native |

### Frontend
| Component | Technology |
|-----------|-----------|
| **Templating** | Blade (Laravel) | 
| **Styling** | Tailwind CSS v3 |
| **Scripting** | Vanilla JavaScript (ES6+) |
| **Icons** | Emoji + Custom |

### Infrastructure
- **Server:** PHP 8.1+
- **Database:** MongoDB 6.0+
- **Session Storage:** File-based (configurable to Redis/Database)
- **Email:** Console log (can integrate Mailgun/SES/SMTP)

### Development Tools
- **Package Manager:** Composer (PHP), npm (JS)
- **Linter:** PHP built-in (no PSR-12 yet)
- **Testing:** PHPUnit (not yet implemented)
- **Version Control:** Git

---

## 📁 Project Structure

```
pharmacy-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          (login/register/OTP)
│   │   │   ├── AdminController.php         (pharmacy onboarding)
│   │   │   ├── InventoryController.php     (owner/seller inventory)
│   │   │   ├── ReservationController.php   (airlock system)
│   │   │   ├── GuestController.php         (guest browsing)
│   │   │   └── CategoryController.php      (category browsing)
│   │   ├── Middleware/
│   │   │   └── CheckRole.php               (role-based access)
│   │   └── Requests/
│   │       └── RegisterRequest.php         (validation)
│   ├── Models/
│   │   ├── User.php                        (patients, owners, sellers)
│   │   ├── Pharmacy.php                    (pharmacy entities)
│   │   ├── Product.php                     (medications)
│   │   ├── Reservation.php                 (airlock holds)
│   │   ├── Admin.php                       (platform admins)
│   │   └── Category.php                    (medication categories)
│   └── Services/
│       └── VerificationService.php         (OTP generation)
│
├── resources/
│   └── views/
│       ├── admin/                          (admin panel)
│       ├── client/                         (patient pages)
│       ├── dashboard/
│       │   ├── owner/                      (owner inventory mgmt)
│       │   └── seller/                     (seller POS interface)
│       └── guest/                          (public browsing)
│
├── routes/
│   ├── web.php                             (web routes + middleware)
│   └── api.php                             (API routes)
│
├── database/
│   └── seeders/
│       ├── PharmacyTestSeeder.php          (demo data)
│       └── RealPharmaciesSeeder.php        (real pharmacy data)
│
├── config/
│   ├── auth.php                            (auth guards & providers)
│   ├── session.php                         (session config)
│   └── database.php                        (MongoDB connection)
│
├── bootstrap/
│   └── app.php                             (middleware aliases)
│
└── public/
    └── index.php                           (entry point)
```

---

## 🚀 Setup & Testing

### Prerequisites
```bash
✓ PHP 8.1+
✓ MongoDB 6.0+ (running)
✓ Composer
✓ Node.js + npm
```

### Installation

1. **Clone & Install**
```bash
cd /home/pengu/Documents/PFE/software/pharmacy-app
composer install
npm install
```

2. **Environment Setup**
```bash
cp .env.example .env
# Edit .env for MongoDB connection:
# MONGODB_URI=mongodb://127.0.0.1:27017/pharmacy_app
```

3. **Start Services**
```bash
# MongoDB
mongod --dbpath /path/to/data

# Laravel Server
php artisan serve --host=192.168.100.86 --port=8000
```

4. **Seed Database**
```bash
php artisan migrate:fresh --force
php artisan db:seed --class=PharmacyTestSeeder
# or
php artisan db:seed --class=RealPharmaciesSeeder
```

### Test Credentials

| Role | Email | Password | Purpose |
|------|-------|----------|---------|
| **Admin** | admin@example.com | password123 | Verify pharmacies |
| **Owner 1** | owner@pharmacy1.com | password123 | Manage inventory |
| **Owner 2** | owner@pharmacy2.com | password123 | Alt pharmacy |
| **Seller 1** | seller1@pharmacy1.com | password123 | Process sales |
| **Seller 2** | seller2@pharmacy1.com | password123 | Alt seller |
| **Client** | patient@example.com | password123 | Browse & reserve |

### Quick Test Flows

**Guest Browse → Login → Reserve:**
```
1. Go to http://192.168.100.86:8000/
2. View products (no login required)
3. Click product → redirects to /client-login
4. Register or login with patient@example.com
5. View product details
6. Click "Reserve" → creates 24-hour hold
7. Go to /client/reservations to see code
```

**Owner Add Product → Seller Sell:**
```
1. Login as owner@pharmacy1.com
2. Go to /owner/inventory
3. Add product (name, dosage, price, stock)
4. Logout
5. Login as seller1@pharmacy1.com
6. Go to /seller/inventory
7. Click "✓ Sell Item" → stock decrements
8. Refresh owner page → stock updated
```

**Admin Onboard Pharmacy:**
```
1. Login as admin@example.com
2. Go to /admin/dashboard
3. Click "Create Pharmacy"
4. Fill form + paste Google Maps link
5. Submit → pharmacy created + owner account generated
```

---

## 🔧 Key Implementation Details

### 1. **Multi-Tenant Filtering**

All queries automatically scoped to user's pharmacy:

```php
// In InventoryController
public function authorizePharmacyAccess(Request $request)
{
    if ($request->user()->role === 'owner') {
        $pharmacy_id = $request->user()->email; // owner's pharmacy
    } else if ($request->user()->role === 'seller') {
        $pharmacy_id = $request->user()->pharmacy_id;
    }
    return $pharmacy_id;
}

// Usage
$products = Product::where('pharmacy_id', $pharmacy_id)->get();
```

### 2. **Role-Based Middleware**

Routes protected via middleware parameter:

```php
// routes/web.php
Route::middleware('role:owner')->group(function () {
    Route::get('/owner/inventory', [InventoryController::class, 'ownerIndex']);
});

// CheckRole middleware
if (!in_array($userRole, $roles)) {
    return response()->json(['error' => 'Unauthorized'], 403);
}
```

### 3. **Session-Based Authentication**

Web forms use Laravel sessions:

```php
// AuthController@login
if (Hash::check($request->password, $user->password)) {
    Auth::login($user);  // Creates session
    return redirect('/client/dashboard');
}
```

### 4. **OTP Verification Flow**

```
User registers
    ↓
VerificationService generates 6-digit code
    ↓
Code stored in verification_otps array (15-min TTL)
    ↓
Email sent (currently console log)
    ↓
User enters code on /verify-otp
    ↓
AuthController validates & marks is_verified=true
    ↓
User redirected to dashboard
```

### 5. **Lazy-Loading with Pagination**

Frontend infinite scroll + backend pagination:

```javascript
// guest/products.blade.php
fetch(`/api/guest/products?page=${currentPage}&search=${searchTerm}`)
    .then(res => res.json())
    .then(data => {
        renderProducts(data.data);
        hasMore = data.last_page > currentPage;
        currentPage++;
    });
```

---

## ⚠️ Known Issues & TODOs

### Critical (Blocking MVP)
| Issue | Impact | Status |
|-------|--------|--------|
| Stock decrement not atomic | Race conditions on concurrent sales | ⚠️ Needs $inc operator |
| OTP not sent via email | Users can't verify registration | 🔧 Needs Mailgun/SES |
| Product pagination broken? | May not scroll properly | 🔍 Needs testing |
| Maps not embedded | Can't see location visually | 📌 Using links instead |

### Important (Post-MVP)
- [ ] Seller QR code scanning for verification
- [ ] Background job to auto-expire old reservations
- [ ] Admin analytics dashboard
- [ ] Pharmacy location geospatial indexing
- [ ] Payment gateway integration
- [ ] API token authentication (Sanctum)
- [ ] Automated test suite (PHPUnit)
- [ ] Email templates & branding
- [ ] Mobile app consideration

### Low Priority (Nice-to-Have)
- [ ] Product images/photos
- [ ] Customer reviews on pharmacies
- [ ] Inventory forecasting
- [ ] SMS notifications
- [ ] WhatsApp integration
- [ ] Multi-language support

---

## 📞 Support & Maintenance

### Running the App
```bash
# Start MongoDB
mongod

# Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000

# Monitor logs
tail -f storage/logs/laravel.log
```

### Troubleshooting

**"MongoDB connection refused"**
```bash
# Ensure MongoDB is running
mongosh --eval "db.version()"
```

**"419 Page Expired"**
```bash
# Clear sessions
php artisan session:clear
rm -rf storage/framework/sessions/*
```

**"Role not found" 403 errors**
```bash
# Verify middleware is registered
grep CheckRole bootstrap/app.php
```

---

## 📊 Statistics

| Metric | Count |
|--------|-------|
| **Controllers** | 6 |
| **Models** | 6 |
| **Routes** | 47 |
| **Views** | 20+ |
| **API Endpoints** | 15+ |
| **User Roles** | 4 |
| **MongoDB Collections** | 6 |

---

## 🎯 Next Phase

### Immediate (Week 1)
1. Fix atomic stock operations (MongoDB $inc)
2. Integrate email OTP delivery
3. Add QR code generation for reservations
4. Test all user flows end-to-end

### Short-term (Week 2-3)
1. Geospatial indexing on pharmacy coordinates
2. Admin analytics dashboard
3. Seller performance metrics
4. Background job scheduler for auto-expiry

### Medium-term (Month 2)
1. Mobile app (React Native)
2. Payment gateway (Stripe/Payfort)
3. API token auth (Sanctum)
4. Comprehensive test suite

---

**Version:** 1.0 (MVP)  
**Last Updated:** 2026-06-23  
**Status:** 🚀 Ready for Internal Testing

---

*For questions or issues, refer to SETUP_AND_TEST.md or contact the development team.*
