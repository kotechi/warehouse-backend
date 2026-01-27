# API Documentation - Penanggung Jawab Aset

## Overview

API untuk mengelola data Penanggung Jawab Aset (Asset Responsible Person) dalam sistem warehouse management.

## Base URL

```
GET|POST /api/v1/penanggung-jawab-aset
```

## Authentication

Semua endpoint memerlukan autentikasi menggunakan Sanctum token.

```
Authorization: Bearer {your-token}
```

## Endpoints

### 1. List Penanggung Jawab Aset

**GET** `/api/v1/penanggung-jawab-aset`

Mengambil daftar penanggung jawab aset dengan filtering, sorting, dan pagination.

**Query Parameters:**

- `unit_eselon_ii_id` (integer, optional) - Filter berdasarkan unit eselon II
- `status` (string, optional) - Filter berdasarkan status: `aktif|tidak_aktif`
- `search` (string, optional) - Pencarian berdasarkan nama_pic, nip, jabatan, email, telepon
- `sort_by` (string, optional) - Field untuk sorting, default: `created_at`
- `sort_direction` (string, optional) - Arah sorting: `asc|desc`, default: `desc`
- `per_page` (integer|string, optional) - Jumlah data per halaman, default: 15, gunakan "all" untuk semua data

**Response Success (200):**

```json
{
  "message": "Penanggung Jawab Aset retrieved successfully",
  "data": [...],
  "meta": {
    "total": 50,
    "per_page": 15,
    "current_page": 1,
    "last_page": 4,
    "from": 1,
    "to": 15
  },
  "links": {
    "first": "http://localhost:8000/api/v1/penanggung-jawab-aset?page=1",
    "last": "http://localhost:8000/api/v1/penanggung-jawab-aset?page=4",
    "prev": null,
    "next": "http://localhost:8000/api/v1/penanggung-jawab-aset?page=2"
  }
}
```

### 2. Create Penanggung Jawab Aset

**POST** `/api/v1/penanggung-jawab-aset`

Membuat data penanggung jawab aset baru.

**Request Body:**

```json
{
    "user_id": 1, // optional, integer
    "unit_eselon_ii_id": 1, // required, integer
    "nama_pic": "John Doe", // required, string, max 255
    "nip": "123456789", // optional, string, max 50, unique
    "jabatan": "Manager", // optional, string, max 150
    "telepon": "081234567890", // optional, string, max 20
    "email": "john@example.com", // optional, email, max 255, unique
    "status": "aktif" // required, enum: aktif|tidak_aktif
}
```

**Response Success (201):**

```json
{
  "message": "Penanggung Jawab Aset created successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "unit_eselon_ii_id": 1,
    "nama_pic": "John Doe",
    "nip": "123456789",
    "jabatan": "Manager",
    "telepon": "081234567890",
    "email": "john@example.com",
    "status": "aktif",
    "created_at": "2026-01-27T10:00:00.000000Z",
    "updated_at": "2026-01-27T10:00:00.000000Z",
    "user": {...},
    "unit_eselon_ii": {...}
  }
}
```

**Response Error (422):**

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "nama_pic": ["Nama PIC harus diisi."],
        "unit_eselon_ii_id": ["Unit Eselon II harus dipilih."]
    }
}
```

### 3. Show Penanggung Jawab Aset

**GET** `/api/v1/penanggung-jawab-aset/{id}`

Mengambil detail penanggung jawab aset berdasarkan ID.

**Response Success (200):**

```json
{
  "message": "Penanggung Jawab Aset retrieved successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "unit_eselon_ii_id": 1,
    "nama_pic": "John Doe",
    "nip": "123456789",
    "jabatan": "Manager",
    "telepon": "081234567890",
    "email": "john@example.com",
    "status": "aktif",
    "created_at": "2026-01-27T10:00:00.000000Z",
    "updated_at": "2026-01-27T10:00:00.000000Z",
    "user": {...},
    "unit_eselon_ii": {...},
    "asets": [...],
    "asets_count": 5
  }
}
```

**Response Error (404):**

```json
{
    "message": "No query results for model [App\\Models\\PenanggungJawabAset] {id}"
}
```

### 4. Update Penanggung Jawab Aset

**PUT/PATCH** `/api/v1/penanggung-jawab-aset/{id}`

Memperbarui data penanggung jawab aset.

**Request Body:** (sama dengan create)

**Response Success (200):**

```json
{
    "message": "Penanggung Jawab Aset updated successfully",
    "data": {
        // updated data...
    }
}
```

### 5. Delete Penanggung Jawab Aset

**DELETE** `/api/v1/penanggung-jawab-aset/{id}`

Soft delete penanggung jawab aset.

**Response Success (200):**

```json
{
    "message": "Penanggung Jawab Aset deleted successfully"
}
```

**Response Error (400):**

```json
{
    "message": "Cannot delete penanggung jawab aset with existing assets",
    "errors": {
        "asets": "Penanggung jawab masih memiliki aset yang terkait"
    }
}
```

### 6. List Trashed Penanggung Jawab Aset

**GET** `/api/v1/penanggung-jawab-aset/trashed`

Mengambil daftar penanggung jawab aset yang telah dihapus (soft delete).

**Query Parameters:** (sama dengan list endpoint)

**Response Success (200):** (struktur sama dengan list endpoint)

### 7. Restore Penanggung Jawab Aset

**PATCH** `/api/v1/penanggung-jawab-aset/{id}/restore`

Mengembalikan penanggung jawab aset yang telah dihapus.

**Response Success (200):**

```json
{
    "message": "Penanggung Jawab Aset restored successfully",
    "data": {
        // restored data...
    }
}
```

### 8. Force Delete Penanggung Jawab Aset

**DELETE** `/api/v1/penanggung-jawab-aset/{id}/force`

Menghapus permanen penanggung jawab aset.

**Response Success (200):**

```json
{
    "message": "Penanggung Jawab Aset permanently deleted successfully"
}
```

## Error Responses

### 401 Unauthorized

```json
{
    "message": "Unauthenticated."
}
```

### 403 Forbidden

```json
{
    "message": "This action is unauthorized."
}
```

### 500 Internal Server Error

```json
{
    "message": "Failed to create Penanggung Jawab Aset",
    "error": "Database connection failed"
}
```

## Example Usage

### Using cURL

```bash
# List dengan filter dan pagination
curl -X GET "http://localhost:8000/api/v1/penanggung-jawab-aset?unit_eselon_ii_id=1&status=aktif&search=john&per_page=10" \
  -H "Authorization: Bearer your-token" \
  -H "Accept: application/json"

# Create
curl -X POST "http://localhost:8000/api/v1/penanggung-jawab-aset" \
  -H "Authorization: Bearer your-token" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "unit_eselon_ii_id": 1,
    "nama_pic": "John Doe",
    "nip": "123456789",
    "jabatan": "Manager",
    "telepon": "081234567890",
    "email": "john@example.com",
    "status": "aktif"
  }'

# Update
curl -X PUT "http://localhost:8000/api/v1/penanggung-jawab-aset/1" \
  -H "Authorization: Bearer your-token" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "unit_eselon_ii_id": 1,
    "nama_pic": "John Doe Updated",
    "status": "tidak_aktif"
  }'

# Delete
curl -X DELETE "http://localhost:8000/api/v1/penanggung-jawab-aset/1" \
  -H "Authorization: Bearer your-token" \
  -H "Accept: application/json"
```
