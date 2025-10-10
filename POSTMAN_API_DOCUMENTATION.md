# API Documentation - Reservasi Ruangan

## Base URL

```
http://127.0.0.1:8000/api
```

## Authentication

API menggunakan JWT (JSON Web Token) untuk autentikasi. Token harus disertakan dalam header `Authorization` dengan format:

```
Authorization: Bearer {your_token_here}
```

---

## Endpoints

### 1. Login

**POST** `/api/login`

#### Request Body (JSON):

```json
{
    "email": "test@example.com",
    "password": "password"
}
```

#### Success Response (200):

```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "user": {
            "id": 13,
            "name": "Test User",
            "email": "test@example.com",
            "floor": "Lantai 1",
            "nim_nip": "TEST001",
            "program": "Test Program",
            "profile_picture": null,
            "created_at": "2025-10-10T03:14:52.000000Z",
            "updated_at": "2025-10-10T03:14:52.000000Z"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

#### Error Response (401):

```json
{
    "success": false,
    "message": "Email atau password salah",
    "error": "Unauthorized"
}
```

---

### 2. Register

**POST** `/api/register`

#### Request Body (JSON):

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "floor": "Lantai 2",
    "nim_nip": "2021007",
    "program": "Teknik Informatika",
    "profile_picture": null
}
```

#### Success Response (201):

```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": {
        "user": {
            "id": 14,
            "name": "John Doe",
            "email": "john@example.com",
            "floor": "Lantai 2",
            "nim_nip": "2021007",
            "program": "Teknik Informatika",
            "profile_picture": null,
            "created_at": "2025-10-10T03:20:00.000000Z",
            "updated_at": "2025-10-10T03:20:00.000000Z"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

---

### 3. Get Profile

**GET** `/api/profile`

#### Headers:

```
Authorization: Bearer {your_token_here}
```

#### Success Response (200):

```json
{
    "success": true,
    "message": "Data profil berhasil diambil",
    "data": {
        "user": {
            "id": 13,
            "name": "Test User",
            "email": "test@example.com",
            "floor": "Lantai 1",
            "nim_nip": "TEST001",
            "program": "Test Program",
            "profile_picture": null,
            "created_at": "2025-10-10T03:14:52.000000Z",
            "updated_at": "2025-10-10T03:14:52.000000Z"
        }
    }
}
```

---

### 4. Logout

**POST** `/api/logout`

#### Headers:

```
Authorization: Bearer {your_token_here}
```

#### Success Response (200):

```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

---

## Test Accounts

### Admin

-   **Email**: `admin@reservasi.com`
-   **Password**: `admin123`
-   **Role**: `admin`
-   **Permissions**: All permissions (view, edit, delete users; view, create, edit, delete, approve reservations; view, create, edit, delete rooms)

### Dosen

-   **Email**: `ahmad.wijaya@university.ac.id`
-   **Password**: `dosen123`
-   **Role**: `dosen`
-   **Permissions**: view, create, edit, approve reservations; view rooms

### Staff

-   **Email**: `bambang.sutrisno@university.ac.id`
-   **Password**: `staff123`
-   **Role**: `staff`
-   **Permissions**: view users; view, create, edit, approve reservations; view, create, edit rooms

### Mahasiswa

-   **Email**: `rizki.muhammad@student.university.ac.id`
-   **Password**: `mahasiswa123`
-   **Role**: `student`
-   **Permissions**: view, create, edit reservations; view rooms

### Test User

-   **Email**: `test@example.com`
-   **Password**: `password`
-   **Role**: `student`
-   **Permissions**: view, create, edit reservations; view rooms

---

## Postman Collection Setup

### Import Collection & Environment

1. **Import Collection**: Import file `Reservasi_Ruangan_API.postman_collection.json`
2. **Import Environment**: Import file `Reservasi_Ruangan_Environment.postman_environment.json`
3. **Select Environment**: Pilih "Reservasi Ruangan Environment" di dropdown environment

### Manual Setup (Alternative)

#### 1. Login Request

-   **Method**: POST
-   **URL**: `{{base_url}}/api/login`
-   **Headers**:
    -   `Content-Type: application/json`
    -   `Accept: application/json`
-   **Body** (raw JSON):

```json
{
    "email": "{{test_email}}",
    "password": "{{test_password}}"
}
```

#### 2. Profile Request

-   **Method**: GET
-   **URL**: `{{base_url}}/api/profile`
-   **Headers**:
    -   `Authorization: Bearer {{token}}`
    -   `Accept: application/json`

#### 3. Logout Request

-   **Method**: POST
-   **URL**: `{{base_url}}/api/logout`
-   **Headers**:
    -   `Authorization: Bearer {{token}}`
    -   `Accept: application/json`

### Environment Variables

-   `base_url`: `http://127.0.0.1:8000`
-   `token`: (akan diisi otomatis setelah login)
-   `admin_email`: `admin@reservasi.com`
-   `admin_password`: `admin123`
-   `test_email`: `test@example.com`
-   `test_password`: `password`

---

## Role-Based Access Control (RBAC)

### Roles

1. **Admin**: Full access to all features
2. **Staff**: Administrative access with user management
3. **Dosen**: Can approve reservations and manage rooms
4. **Student**: Basic access to create and view reservations

### Permissions

#### User Management

-   `view users`: View user list
-   `edit users`: Edit user information
-   `delete users`: Delete users

#### Reservation Management

-   `view reservations`: View reservation list
-   `create reservations`: Create new reservations
-   `edit reservations`: Edit existing reservations
-   `delete reservations`: Delete reservations
-   `approve reservations`: Approve pending reservations

#### Room Management

-   `view rooms`: View room list
-   `create rooms`: Create new rooms
-   `edit rooms`: Edit room information
-   `delete rooms`: Delete rooms

### Using Middleware

#### Role Middleware

```php
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    // Admin only routes
});

Route::middleware(['auth:api', 'role:admin,staff'])->group(function () {
    // Admin or Staff routes
});
```

#### Permission Middleware

```php
Route::middleware(['auth:api', 'permission:delete users'])->group(function () {
    // Users with delete users permission
});

Route::middleware(['auth:api', 'permission:approve reservations'])->group(function () {
    // Users with approve reservations permission
});
```

---

## Error Codes

-   **200**: Success
-   **201**: Created (Register)
-   **401**: Unauthorized (Invalid credentials or token)
-   **403**: Forbidden (Insufficient permissions)
-   **422**: Validation Error
-   **500**: Server Error

---

## Notes

1. Token expires in 1 hour (3600 seconds)
2. All timestamps are in UTC format
3. Password minimum length is 6 characters
4. Email must be unique
5. NIM/NIP must be unique
6. Roles and permissions are managed through Spatie Laravel Permission
7. All API responses include user roles and permissions
8. Use JWT token in Authorization header: `Bearer {token}`
