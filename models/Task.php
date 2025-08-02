<?php
class Task {
    private $db;
    private $connection;

    public function __construct() {
        $this->db = new Database();
        $this->connection = $this->db->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO tasks (title, description, status, created_at, updated_at) 
                VALUES (:title, :description, :status, datetime('now'), datetime('now'))";
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':status', $data['status']);
            
            if ($stmt->execute()) {
                $id = $this->connection->lastInsertId();
                return $this->getById($id);
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception('Failed to create task: ' . $e->getMessage());
        }
    }

    public function getAll($status = null) {
        $sql = "SELECT * FROM tasks";
        $params = [];

        if ($status) {
            $sql .= " WHERE status = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY created_at DESC";

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(params: $params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception('Failed to fetch tasks: ' . $e->getMessage());
        }
    }

    public function getById($id) {
        $sql = "SELECT * FROM tasks WHERE id = :id";
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $task = $stmt->fetch();
            return $task ? $task : null;
        } catch (PDOException $e) {
            throw new Exception('Failed to fetch task: ' . $e->getMessage());
        }
    }

    public function update($id, $data) {
        $sql = "UPDATE tasks SET title = :title, description = :description, 
                status = :status, updated_at = datetime('now') WHERE id = :id";
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':status', $data['status']);
            
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                return $this->getById($id);
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception('Failed to update task: ' . $e->getMessage());
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM tasks WHERE id = :id";
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute() && $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception('Failed to delete task: ' . $e->getMessage());
        }
    }

    public function validateTaskData($data, $isUpdate = false) {
        $errors = [];

        // Title validation
        if (!$isUpdate || isset($data['title'])) {
            if (empty($data['title']) || !is_string($data['title'])) {
                $errors[] = 'Title is required and must be a string';
            } elseif (strlen($data['title']) > 255) {
                $errors[] = 'Title must not exceed 255 characters';
            }
        }

        // Status validation
        if (!$isUpdate || isset($data['status'])) {
            $validStatuses = ['pending', 'in-progress', 'completed'];
            if (empty($data['status']) || !in_array($data['status'], $validStatuses)) {
                $errors[] = 'Status must be one of: ' . implode(', ', $validStatuses);
            }
        }

        // Description validation (optional)
        if (isset($data['description']) && !is_string($data['description'])) {
            $errors[] = 'Description must be a string';
        }

        return $errors;
    }
}
?>
