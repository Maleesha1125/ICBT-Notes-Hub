<?php
class Note {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function uploadNote($user_id, $module_id, $title, $description, $content_type, $file_path, $department_id, $program_id) {
        $query = "INSERT INTO content (uploaded_by, module_id, title, description, content_type, file_path, department_id, program_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("iissssii", $user_id, $module_id, $title, $description, $content_type, $file_path, $department_id, $program_id);
            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    public function updateNote($content_id, $title, $description, $content_type, $file_path = null) {
        if ($file_path) {
            $query = "UPDATE content SET title = ?, description = ?, content_type = ?, file_path = ? WHERE content_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssi", $title, $description, $content_type, $file_path, $content_id);
        } else {
            $query = "UPDATE content SET title = ?, description = ?, content_type = ? WHERE content_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssi", $title, $description, $content_type, $content_id);
        }
        return $stmt->execute();
    }

    public function deleteNote($content_id) {
        $query = "DELETE FROM content WHERE content_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $content_id);
        return $stmt->execute();
    }

    public function getNotesByModuleId($module_id) {
        $query = "SELECT * FROM content WHERE module_id = ? ORDER BY upload_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $module_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getNoteById($content_id) {
        $query = "SELECT * FROM content WHERE content_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $content_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>