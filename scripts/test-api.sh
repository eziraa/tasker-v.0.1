#!/bin/bash

# Task Manager API Test Script
# This script tests all API endpoints

API_URL="http://localhost:8080"
TASK_ID=""

echo "🚀 Starting Task Manager API Tests..."
echo "API URL: $API_URL"
echo "=================================="

# Function to make HTTP requests and display results
make_request() {
    local method=$1
    local endpoint=$2
    local data=$3
    local description=$4
    local expected_status=$5
    
    echo ""
    echo "📋 Test: $description"
    echo "Method: $method"
    echo "Endpoint: $endpoint"
    
    if [ -n "$data" ]; then
        echo "Data: $data"
        response=$(curl -s -w "\nHTTP_STATUS:%{http_code}" -X $method "$API_URL$endpoint" \
            -H "Content-Type: application/json" \
            -d "$data")
    else
        response=$(curl -s -w "\nHTTP_STATUS:%{http_code}" -X $method "$API_URL$endpoint")
    fi
    
    # Extract HTTP status and body
    http_status=$(echo "$response" | grep "HTTP_STATUS:" | cut -d: -f2)
    body=$(echo "$response" | sed '/HTTP_STATUS:/d')
    
    if [[ "$http_status" -ne "$expected_status" ]]; then
        echo "❌ Unexpected status: $http_status (Expected: $expected_status)"
    else
        echo "✅ Status matches expected: $http_status"
    fi
    echo "Status: $http_status"
    echo "Response: $body"
    
    # Extract task ID from create response
    if [[ "$method" == "POST" && "$endpoint" == "/tasks" && "$http_status" == "201" ]]; then
        TASK_ID=$(echo "$body" | grep -o '"id":[0-9]*' | cut -d: -f2)
        echo "✅ Task created with ID: $TASK_ID"
    fi
    
    echo "---"
}

# Test 1: Create a new task
make_request "POST" "/tasks" '{
    "title": "Complete API testing",
    "description": "Test all endpoints of the Task Manager API",
    "status": "pending"
}' "Create a new task" "201"

# Test 2: Get all tasks
make_request "GET" "/tasks" "" "Get all tasks" "200"

# Test 3: Get task by ID (using the created task ID)
if [ -n "$TASK_ID" ]; then
    make_request "GET" "/tasks/$TASK_ID" "" "Get task by ID ($TASK_ID)" "200"
fi

# Test 4: Update the task
if [ -n "$TASK_ID" ]; then
    make_request "PUT" "/tasks/$TASK_ID" '{
        "title": "Complete API testing - Updated",
        "description": "Test all endpoints of the Task Manager API - Updated description",
        "status": "in-progress"
    }' "Update task ($TASK_ID)" "200"
fi

# Test 5: Create another task for filtering test
make_request "POST" "/tasks" '{
    "title": "Second task",
    "description": "This task is for testing filters",
    "status": "completed"
}' "Create second task" "201"

# Test 6: Filter tasks by status
make_request "GET" "/tasks?status=completed" "" "Filter tasks by status (completed)" "200"

# Test 7: Filter tasks by status (in-progress)
make_request "GET" "/tasks?status=in-progress" "" "Filter tasks by status (in-progress)" "200"  

# Test 8: Test invalid status filter
make_request "GET" "/tasks?status=invalid" "" "Test invalid status filter" "400"

# Test 9: Test getting non-existent task
make_request "GET" "/tasks/999" "" "Get non-existent task"  "404"

# Test 10: Test invalid task creation
make_request "POST" "/tasks" '{
    "description": "Task without title",
    "status": "invalid-status"
}' "Create task with validation errors" "400"

# Test 11: Delete the first task
if [ -n "$TASK_ID" ]; then
    make_request "DELETE" "/tasks/$TASK_ID" "" "Delete task ($TASK_ID)" "200"
fi

# Test 12: Try to get deleted task
if [ -n "$TASK_ID" ]; then
    make_request "GET" "/tasks/$TASK_ID" "" "Try to get deleted task ($TASK_ID)"  "404"
fi

# Test 13: Test invalid endpoints
make_request "GET" "/invalid" "" "Test invalid endpoint" "404"

# Test 14: Test invalid method
make_request "PATCH" "/tasks" "" "Test invalid HTTP method" "405"
    
echo ""
echo "🎉 API Testing Complete!"
echo "=================================="
echo "Review the results above to ensure all endpoints are working correctly."
echo ""
echo "Expected results:"
echo "- Task creation should return 201"
echo "- GET requests should return 200"
echo "- Updates should return 200"
echo "- Deletes should return 200"
echo "- Invalid requests should return 400/404/405"
echo ""
