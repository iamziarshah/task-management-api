<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
# Task Management API

A production-ready RESTful API for task/todo management built with Laravel, showcasing industry best practices, design patterns, and query optimization.

## 🎯 Project Overview

This project demonstrates:
- **Design Patterns**: Repository Pattern, Service Layer Architecture
- **API Design**: RESTful endpoints with proper HTTP methods and status codes
- **Authentication**: JWT (JSON Web Tokens) for secure API access
- **Query Optimization**: Eager loading, indexed queries, and N+1 prevention
- **Error Handling**: Comprehensive exception handling and validation
- **Documentation**: API documentation with Postman collection included

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Language**: PHP 8.3+
- **Database**: MySQL/PostgreSQL
- **Authentication**: JWT (Tymon/JWT-Auth)
- **API Documentation**: Postman
- **Development**: RESTful API principles

## 📋 Features

- User authentication and authorization
- JWT token-based security
- Complete CRUD operations for tasks
- Task filtering by status and priority
- Upcoming tasks retrieval
- Task statistics and insights
- Bulk operations
- Query optimization with eager loading
- Comprehensive error handling
- Pagination support
- Professional API responses

## 🚀 Quick Start

### Prerequisites

- PHP 8.3 or higher
- Composer
- MySQL/PostgreSQL
- Git

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/task-management-api.git
cd task-management-api
```

2. **Install dependencies**
```bash
composer install
```

3. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**
```bash
# Edit .env with your database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=task_management_api
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations**
```bash
php artisan migrate
```

6. **Generate JWT secret**
```bash
php artisan jwt:secret
```

7. **Start the development server**
```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

## 📚 API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication

All protected endpoints require a JWT token in the Authorization header:
```
Authorization: Bearer YOUR_JWT_TOKEN
```

---

## 🔐 Authentication Endpoints

### Register User
```http
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

---

### Login User
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "Bearer",
    "expires_in": 3600
  }
}
```

---

### Get User Profile
```http
GET /api/auth/me
Authorization: Bearer YOUR_JWT_TOKEN
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

---

### Refresh Token
```http
POST /api/auth/refresh
Authorization: Bearer YOUR_JWT_TOKEN
```

**Response (200):**
```json
{
  "success": true,
  "message": "Token refreshed successfully",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "Bearer",
    "expires_in": 3600
  }
}
```

---

### Logout User
```http
POST /api/auth/logout
Authorization: Bearer YOUR_JWT_TOKEN
```

**Response (200):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

## 📝 Task Endpoints

### Create Task
```http
POST /api/tasks
Authorization: Bearer YOUR_JWT_TOKEN
Content-Type: application/json

{
  "title": "Complete project documentation",
  "description": "Write comprehensive README and API docs",
  "status": "pending",
  "priority": "high",
  "due_date": "2024-12-31"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Task created successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "title": "Complete project documentation",
    "description": "Write comprehensive README and API docs",
    "status": "pending",
    "priority": "high",
    "due_date": "2024-12-31",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

---

### Get All Tasks
```http
GET /api/tasks?page=1&per_page=15
Authorization: Bearer YOUR_JWT_TOKEN
```

**Response (200):**
```json
{
  "success": true,
  "message": "Tasks retrieved successfully",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "title": "Complete project documentation",
      "description": "Write comprehensive README and API docs",
      "status": "pending",
      "priority": "high",
      "due_date": "2024-12-31",
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
      }
    }
  ],
  "pagination": {
    "total": 10,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null
  }
}
```

---

### Get Single Task
```http
GET /api/tasks/{id}
Authorization: Bearer YOUR_JWT_TOKEN
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "title": "Complete project documentation",
    "description": "Write comprehensive README and API docs",
    "status": "pending",
    "priority": "high",
    "due_date": "2024-12-31",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

---

### Update Task
```http
PUT /api/tasks/{id}
Authorization: Bearer YOUR_JWT_TOKEN
Content-Type: application/json

{
  "title": "Updated task title",
  "status": "in_progress",
  "priority": "high"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Task updated successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "title": "Updated task title",
    "description": "Write comprehensive README and API docs",
    "status": "in_progress",
    "priority": "high",
    "due_date": "2024-12-31",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T11:00:00Z",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

---

### Delete Task
```http
DELETE /api/tasks/{id}
Authorization: Bearer YOUR_JWT_TOKEN
```

**Response (200):**
```json
{
  "success": true,
  "message": "Task deleted successfully"
}
```

---

### Change Task Status
```http
PATCH /api/tasks/{id}/status
Authorization: Bearer YOUR_JWT_TOKEN
Content-Type: application/json

{
  "status": "completed"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Task status updated successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "title": "Complete project documentation",
    "status": "completed",
    "priority": "high",
    "due_date": "2024-12-31",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T11:30:00Z",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

---

### Filter Tasks
```http
GET /api/tasks/filter?status=pending&priority=high
Authorization: Bearer YOUR_JWT_TOKEN
```

**Response (200):**
```json
{
  "success": true,
  "message": "Filtered tasks retrieved successfully",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "title": "Complete project documentation",
      "status": "pending",
      "priority": "high",
      "due_date": "2024-12-31",
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
      }
    }
  ]
}
```

---

### Get Upcoming Tasks
```http
GET /api/tasks/upcoming?days=7
Authorization: Bearer YOUR_JWT_TOKEN
```

**Response (200):**
```json
{
  "success": true,
  "message": "Upcoming tasks retrieved successfully",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "title": "Complete project documentation",
      "status": "pending",
      "priority": "high",
      "due_date": "2024-01-22",
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
      }
    }
  ]
}
```

---

### Get Task Statistics
```http
GET /api/tasks/statistics
Authorization: Bearer YOUR_JWT_TOKEN
```

**Response (200):**
```json
{
  "success": true,
  "message": "Task statistics retrieved successfully",
  "data": {
    "total": 10,
    "completed": 3,
    "pending": 5,
    "in_progress": 2
  }
}
```

---

### Bulk Update Task Status
```http
PATCH /api/tasks/bulk-status
Authorization: Bearer YOUR_JWT_TOKEN
Content-Type: application/json

{
  "task_ids": [1, 2, 3],
  "status": "completed"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Updated 3 task(s) successfully",
  "data": {
    "updated_count": 3
  }
}
```

---

## 🏗️ Architecture & Design Patterns

### Repository Pattern
The application uses the Repository Pattern to abstract data access logic:

```
TaskRepository extends BaseRepository
├── getTasksByUser()
├── paginateUserTasks()
├── getFilteredTasks()
├── getUpcomingTasks()
├── getTaskStatistics()
└── bulkUpdateStatus()
```

**Benefits:**
- Decouples business logic from data access
- Easy to test and maintain
- Simple to swap data sources

### Service Layer
Services contain business logic and use repositories for data access:

```
TaskService
├── getUserTasks()
├── getTask()
├── createTask()
├── updateTask()
├── deleteTask()
├── changeTaskStatus()
└── bulkUpdateTaskStatuses()
```

### JWT Authentication
Secure authentication with JWT tokens:
- Token generation on login
- Token validation via middleware
- Automatic token refresh
- Token expiration handling

### Query Optimization
- Eager loading with `with()` to prevent N+1 queries
- Database indexes on frequently queried columns
- Composite indexes for common filter combinations
- Pagination for large datasets

## 📁 Project Structure

```
task-management-api/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   └── TaskController.php
│   ├── Models/
│   │   ├── User.php
│   │   └── Task.php
│   ├── Services/
│   │   ├── AuthService.php
│   │   └── TaskService.php
│   ├── Repositories/
│   │   ├── RepositoryInterface.php
│   │   ├── BaseRepository.php
│   │   └── TaskRepository.php
│   ├── Exceptions/
│   │   └── ApiException.php
│   └── Middleware/
│       └── JwtMiddleware.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   └── 2024_01_01_000002_create_tasks_table.php
│   ├── factories/
│   └── seeders/
├── routes/
│   └── api.php
├── config/
├── storage/
├── tests/
├── .env.example
├── composer.json
└── README.md
```

## 🧪 Testing

### Test Task Creation
```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Task",
    "description": "Test Description",
    "status": "pending",
    "priority": "high",
    "due_date": "2024-12-31"
  }'
```

### Test Task Retrieval
```bash
curl -X GET http://localhost:8000/api/tasks \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

## 📋 Valid Values

### Task Statuses
- `pending`
- `in_progress`
- `completed`

### Task Priorities
- `low`
- `medium`
- `high`

## 🔒 Security Features

1. **JWT Authentication**: Secure token-based authentication
2. **Request Validation**: Comprehensive input validation
3. **Authorization**: User ownership verification
4. **Password Hashing**: Bcrypt password hashing
5. **Error Messages**: Safe error messages without sensitive data
6. **CORS Ready**: Ready for frontend integration

## 🚦 Error Handling

All errors return standardized responses:

```json
{
  "success": false,
  "message": "Error message",
  "error": "Additional error details (if applicable)"
}
```

### Common HTTP Status Codes
- `200`: Success
- `201`: Created
- `400`: Bad Request
- `401`: Unauthorized
- `404`: Not Found
- `422`: Validation Error
- `500`: Server Error

## 📦 Postman Collection

A Postman collection is included in the repository for easy API testing. Import `Task_Management_API.postman_collection.json` into Postman to get started quickly.

## 🔄 Development Workflow

1. **Feature Branch**: Create feature branches from `main`
2. **Pull Requests**: Submit PRs for code review
3. **Testing**: Run tests before merging
4. **Documentation**: Update docs with new features

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is open source and available under the MIT License.

## 👨‍💼 Author

**Mansoor**
- Full Stack Engineer at Dice Analytics
- 8+ years of Laravel experience
- Specialized in API design and system architecture

## 📞 Support

For issues and questions, please open an issue on GitHub.

## 🎓 Learning Resources

- [Laravel Documentation](https://laravel.com/docs)
- [JWT Authentication](https://jwt.io)
- [RESTful API Design](https://restfulapi.net)
- [Repository Pattern](https://medium.com/design-patterns/repository-pattern-bfb0f3f1e9e8)
- [Database Query Optimization](https://en.wikipedia.org/wiki/Query_optimization)

---

**Last Updated**: January 2024
**Laravel Version**: 11.x
**PHP Version**: 8.3+
