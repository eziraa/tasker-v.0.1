# Task Manager API

A simple REST API for managing tasks built with pure PHP and SQLite. This project demonstrates core PHP skills including OOP, PDO database interaction, and MVC architecture.

## Features

- ✅ Full CRUD operations for tasks
- ✅ RESTful API endpoints
- ✅ SQLite database with automatic table creation
- ✅ Input validation and error handling
- ✅ Status filtering
- ✅ Docker support for easy deployment
- ✅ Comprehensive API testing scripts

## Project Structure

```
task-manager-api/
├── controllers/
│   └── TaskController.php    # Handles API logic
├── database/
│   └── Database.php          # Database connection and setup
├── models/
│   └── Task.php              # Task model with CRUD operations
├── routes/
│   └── Router.php            # Request routing
├── scripts/
│   └── test-api.sh           # API testing script
├── data/                     # SQLite database storage
├── index.php                 # Main entry point
├── Dockerfile                # Docker configuration
├── docker-compose.yml        # Docker Compose setup
├── apache-config.conf        # Apache configuration
├── .htaccess                 # URL rewriting rules
├── postman-collection.json   # Postman API collection
└── README.md                 # This file
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /tasks | Create a new task |
| GET | /tasks | Get all tasks |
| GET | /tasks/{id} | Get a specific task |
| PUT | /tasks/{id} | Update a task |
| DELETE | /tasks/{id} | Delete a task |
| GET | /tasks?status={status} | Filter tasks by status |

## Task Schema

```json
{
  "id": 1,
  "title": "Task title",
  "description": "Task description",
  "status": "pending|in-progress|completed",
  "created_at": "2024-01-01 12:00:00",
  "updated_at": "2024-01-01 12:00:00"
}
```

## Installation & Setup

### Option 1: Using Docker (Recommended)

1. **Clone the repository**
   ```bash
   git clone https://github.com/eziraa/tasker-v.0.1
   cd task-manager-api
   ```

2. **Build and run with Docker Compose**
   ```bash
   docker-compose up --build
   ```

3. **Access the API**
   The API will be available at \`http://localhost:8080\`

### Option 2: Local PHP Server

1. **Prerequisites**
   - PHP 8.0+ with SQLite extension
   - Apache or Nginx (optional)

2. **Setup**
   ```bash
   git clone https://github.com/eziraa/tasker-v.0.1
   cd task-manager-api
   
   # Create data directory
   mkdir -p data
   chmod 755 data
   
   # Start PHP built-in server
   php -S localhost:8080
   ```

## API Usage Examples

### Create a Task
```bash
curl -X POST http://localhost:8080/tasks \\
  -H "Content-Type: application/json" \\
  -d '{
    "title": "Complete project",
    "description": "Finish the task manager API",
    "status": "pending"
  }'
```

**Response:**
```json
{
  "message": "Task created successfully",
  "task": {
    "id": 1,
    "title": "Complete project",
    "description": "Finish the task manager API",
    "status": "pending",
    "created_at": "2024-01-01 12:00:00",
    "updated_at": "2024-01-01 12:00:00"
  }
}
```

### Get All Tasks
```bash
curl -X GET http://localhost:8080/tasks
```

**Response:**
```json
{
  "tasks": [
    {
      "id": 1,
      "title": "Complete project",
      "description": "Finish the task manager API",
      "status": "pending",
      "created_at": "2024-01-01 12:00:00",
      "updated_at": "2024-01-01 12:00:00"
    }
  ],
  "count": 1
}
```

### Get Task by ID
```bash
curl -X GET http://localhost:8080/tasks/1
```

### Update Task
```bash
curl -X PUT http://localhost:8080/tasks/1 \\
  -H "Content-Type: application/json" \\
  -d '{
    "title": "Complete project",
    "description": "Finish the task manager API - Updated",
    "status": "completed"
  }'
```

### Delete Task
```bash
curl -X DELETE http://localhost:8080/tasks/1
```

### Filter Tasks by Status
```bash
curl -X GET "http://localhost:8080/tasks?status=completed"
```

## Testing

### Automated Testing Script
Run the comprehensive test script:
```bash
chmod +x scripts/test-api.sh
./scripts/test-api.sh
```

### Postman Collection
Import the \`postman-collection.json\` file into Postman for interactive API testing.

## Error Handling

The API returns appropriate HTTP status codes and error messages:

- \`200\` - Success
- \`201\` - Created
- \`400\` - Bad Request (validation errors)
- \`404\` - Not Found
- \`405\` - Method Not Allowed
- \`500\` - Internal Server Error

**Error Response Format:**
```json
{
  "error": "Error message",
  "details": ["Validation error details"]
}
```

## Validation Rules

- \`title\`: Required, max 255 characters
- \`description\`: Optional, text field
- \`status\`: Must be one of: \`pending\`, \`in-progress\`, \`completed\`

## Docker Details

### Dockerfile Features
- PHP 8.2 with Apache
- SQLite extension enabled
- Proper file permissions
- URL rewriting configured

### Docker Compose Features
- Port mapping (8080:80)
- Volume mounting for database persistence
- Environment variables
- Restart policy

## Development Notes

### Architecture
- **MVC Pattern**: Clear separation of concerns
- **OOP Style**: All classes follow PHP OOP best practices
- **PDO**: Secure database interactions with prepared statements
- **RESTful**: Follows REST API conventions

### Security Features
- Input sanitization
- SQL injection prevention via PDO
- CORS headers for cross-origin requests
- Proper error handling without exposing internals

### Database
- SQLite for simplicity and portability
- Automatic table creation
- Proper indexing on primary key
- Timestamp tracking

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open source and available under the [MIT License](LICENSE).

## Contact

For questions or support, please open an issue in the repository.
```

This Task Manager API demonstrates:
- ✅ Pure PHP with OOP principles
- ✅ MVC architecture
- ✅ PDO for secure database operations
- ✅ Complete CRUD functionality
- ✅ Input validation and error handling
- ✅ Docker containerization
- ✅ Comprehensive testing tools
- ✅ Professional documentation

The API is production-ready and can be easily deployed using Docker or any PHP hosting environment.
```

I've created a complete Task Manager API that meets all your requirements! Here's what I've built:

## 🚀 **Key Features Implemented:**

### **Core API Endpoints:**
- ✅ `POST /tasks` - Create new tasks
- ✅ `GET /tasks` - Get all tasks  
- ✅ `GET /tasks/{id}` - Get specific task
- ✅ `PUT /tasks/{id}` - Update tasks
- ✅ `DELETE /tasks/{id}` - Delete tasks

### **Technical Implementation:**
- ✅ **Pure PHP** with OOP architecture
- ✅ **SQLite database** with PDO
- ✅ **MVC structure** (models/, controllers/, routes/, database/)
- ✅ **Input validation** and error handling
- ✅ **Docker support** with Dockerfile and docker-compose.yml

### **Bonus Features:**
- ✅ **Status filtering**: `/tasks?status=completed`
- ✅ **API test script** (bash script with curl)
- ✅ **Postman collection** for interactive testing
- ✅ **Comprehensive README** with setup instructions

## 🐳 **Quick Start with Docker:**

```bash
# Clone and run
git clone https://github.com/eziraa/tasker-v.0.1
cd task-manager-api
docker-compose up --build

# API available at http://localhost:8080
