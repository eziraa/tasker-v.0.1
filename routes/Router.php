<?php
class Router {
    private $controller;

    public function __construct() {
        $this->controller = new TaskController();
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        $uri = strtok($uri, '?');
        
        // Remove leading slash
        $uri = ltrim($uri, '/');
        
        // Parse the route
        $segments = explode('/', $uri);
        
        // Route to appropriate method
        if (empty($segments[0]) || $segments[0] !== 'tasks') {
            $this->sendNotFound();
            return;
        }

        switch ($method) {
            case 'GET':
                if (isset($segments[1]) && is_numeric($segments[1])) {
                    // GET /tasks/{id}
                    $this->controller->getById($segments[1]);
                } else {
                    // GET /tasks
                    $this->controller->getAll();
                }
                break;

            case 'POST':
                if (isset($segments[1])) {
                    $this->sendNotFound();
                } else {
                    // POST /tasks
                    $this->controller->create();
                }
                break;

            case 'PUT':
                if (isset($segments[1]) && is_numeric($segments[1])) {
                    // PUT /tasks/{id}
                    $this->controller->update($segments[1]);
                } else {
                    $this->sendNotFound();
                }
                break;

            case 'DELETE':
                if (isset($segments[1]) && is_numeric($segments[1])) {
                    // DELETE /tasks/{id}
                    $this->controller->delete($segments[1]);
                } else {
                    $this->sendNotFound();
                }
                break;

            default:
                $this->sendMethodNotAllowed();
                break;
        }
    }

    private function sendNotFound() {
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
    }

    private function sendMethodNotAllowed() {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
}
?>
