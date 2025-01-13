# Library System API Documentation

## Authentication
All authenticated routes require a JWT token in the Authorization header:
```
Authorization: Bearer <your_jwt_token>
```

## Endpoints

### Authentication Routes
Base path: `/auth`

#### 1. Register User
**Endpoint:** `POST /auth/register`

**Request Body:**
```json
{
    "username": "testusername",
    "email": "testemail@mail.com",
    "password": "testpassword"
}
```

**Response:**
```json
{
    "message": "Registration successful.",
    "token": "<jwt_token>"
}
```

#### 2. Login
**Endpoint:** `POST /auth/login`

**Request Body:**
```json
{
    "email": "testemail@mail.com",
    "password": "testpassword"
}
```

**Response:**
```json
{
    "message": "Login successful.",
    "token": "<jwt_token>",
    "user": {
        "id": 1,
        "username": "testusername",
        "email": "testemail@mail.com",
        "role": "Member"
    }
}
```

#### 3. Delete Account
**Endpoint:** `DELETE /auth/delete`
**Authentication:** Required

**Response:**
```json
{
    "message": "Account deleted successfully."
}
```

### Loan Management Routes
Base path: `/loans`

#### 1. Create Loan
**Endpoint:** `POST /loans/create`
**Authentication:** Required

**Request Body:**
```json
{
    "book_id": "123",
    "loan_start_date": "2025-01-13",
    "loan_due_date": "2025-01-20"
}
```

**Response:**
```json
{
    "message": "Loan successfully created."
}
```

#### 2. Update Overdue Statuses
**Endpoint:** `PUT /loans/update-status`

**Response:**
```json
{
    "message": "X loans updated to overdue status."
}
```

#### 3. Return Book
**Endpoint:** `PUT /loans/return/{loan_id}`
**Authentication:** Required

**Response:**
```json
{
    "message": "Book returned and penalty updated."
}
```

#### 4. Get User's Loans
**Endpoint:** `GET /loans/user/{user_id}`

**Response:**
```json
[
    {
        "loan_id": 1,
        "user_id": 123,
        "book_id": 456,
        "loan_start_date": "2025-01-13",
        "loan_due_date": "2025-01-20",
        "loan_returned_date": null,
        "status": "active",
        "penalty": 0
    }
]
```

#### 5. Get User's Active Loans
**Endpoint:** `GET /loans/active/{user_id}`

**Response:** Same format as Get User's Loans

#### 6. Get All Active Loans
**Endpoint:** `GET /loans/activeAll`

**Response:** Same format as Get User's Loans

#### 7. Delete Loan
**Endpoint:** `DELETE /loans/delete/{loan_id}`
**Authentication:** Required

**Response:**
```json
{
    "message": "Loan successfully deleted."
}
```

### User Email Route

#### Get User Email
**Endpoint:** `GET /user/email/{user_id}`

**Response:**
```json
{
    "status": "success",
    "email": "user@example.com"
}
```

## Error Responses
All endpoints may return the following error responses:

```json
// 400 Bad Request
{
    "error": "Invalid request data"
}

// 401 Unauthorized
{
    "error": "Invalid or expired token"
}

// 403 Forbidden
{
    "error": "You are not authorized to perform this action"
}

// 404 Not Found
{
    "error": "Resource not found"
}

// 500 Internal Server Error
{
    "error": "Server error message"
}
```

## Notes
- All dates should be in ISO 8601 format (YYYY-MM-DD)
- Penalties are calculated at 0.5 per day for overdue books
- Active loans cannot be created for books that are already borrowed
- Only the user who borrowed a book can return it
