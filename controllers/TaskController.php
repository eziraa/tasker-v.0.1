<?php
class TaskController {
    private $task;

    public function __construct() {
        $this->task = new Task();
    }

    public function create() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendResponse(400, ['error' => 'Invalid JSON input']);
            return;
        }

        // Set default values
        $data = [
            'title' => $input['title'] ?? '',
            'description' => $input['description'] ?? '',
            'status' => $input['status'] ?? 'pending'
        ];

        // Validate input
        $errors = $this->task->validateTaskData($data);
        if (!empty($errors)) {
            $this->sendResponse(400, [
                'error' => 'Validation failed',
                'details' => $errors
            ]);
            return;
        }

        try {
            $newTask = $this->task->create($data);
            if ($newTask) {
                $this->sendResponse(201, [
                    'message' => 'Task created successfully',
                    'task' => $newTask
                ]);
            } else {
                $this->sendResponse(500, ['error' => 'Failed to create task']);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    public function getAll() {
        $status = $_GET['status'] ?? null;
        
        // Validate status if provided
        if ($status) {
            $validStatuses = ['pending', 'in-progress', 'completed'];
            if (!in_array($status, $validStatuses)) {
                $this->sendResponse(400, [
                    'error' => 'Invalid status filter',
                    'valid_statuses' => $validStatuses
                ]);
                return;
            }
        }

        try {
            $tasks = $this->task->getAll($status);
            $this->sendResponse(200, [
                'tasks' => $tasks,
                'count' => count($tasks)
            ]);
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    public function getById($id) {
        if (!is_numeric($id) || $id <= 0) {
            $this->sendResponse(400, ['error' => 'Invalid task ID']);
            return;
        }

        try {
            $task = $this->task->getById($id);
            if ($task) {
                $this->sendResponse(200, ['task' => $task]);
            } else {
                $this->sendResponse(404, ['error' => 'Task not found']);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    public function update($id) {
        if (!is_numeric($id) || $id <= 0) {
            $this->sendResponse(400, ['error' => 'Invalid task ID']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendResponse(400, ['error' => 'Invalid JSON input']);
            return;
        }

        // Check if task exists
        try {
            $existingTask = $this->task->getById($id);
            if (!$existingTask) {
                $this->sendResponse(404, ['error' => 'Task not found']);
                return;
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
            return;
        }

        // Prepare data with existing values as defaults
        $data = [
            'title' => $input['title'] ?? $existingTask['title'],
            'description' => $input['description'] ?? $existingTask['description'],
            'status' => $input['status'] ?? $existingTask['status']
        ];

        // Validate input
        $errors = $this->task->validateTaskData($data, true);
        if (!empty($errors)) {
            $this->sendResponse(400, [
                'error' => 'Validation failed',
                'details' => $errors
            ]);
            return;
        }

        try {
            $updatedTask = $this->task->update($id, $data);
            if ($updatedTask) {
                $this->sendResponse(200, [
                    'message' => 'Task updated successfully',
                    'task' => $updatedTask
                ]);
            } else {
                $this->sendResponse(500, ['error' => 'Failed to update task']);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    public function delete($id) {
        if (!is_numeric($id) || $id <= 0) {
            $this->sendResponse(400, ['error' => 'Invalid task ID']);
            return;
        }

        try {
            // Check if task exists
            $existingTask = $this->task->getById($id);
            if (!$existingTask) {
                $this->sendResponse(404, ['error' => 'Task not found']);
                return;
            }

            $deleted = $this->task->delete($id);
            if ($deleted) {
                $this->sendResponse(200, ['message' => 'Task deleted successfully']);
            } else {
                $this->sendResponse(500, ['error' => 'Failed to delete task']);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
?>
