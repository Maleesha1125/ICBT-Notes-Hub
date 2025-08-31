<?php
class Content {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function uploadFile($title, $description, $new_file_name, $file_type, $user_id, $program_id, $module_id) {
        $sql = "INSERT INTO content (title, description, file_path, file_type, user_id, program_id, module_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("sssssii", $title, $description, $new_file_name, $file_type, $user_id, $program_id, $module_id);
        return $stmt->execute();
    }
    public function uploadLink($title, $url, $description, $user_id, $program_id, $module_id) {
        $sql = "INSERT INTO content (title, description, file_path, user_id, program_id, module_id, created_at, file_type) VALUES (?, ?, ?, ?, ?, ?, NOW(), 'link')";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("sssiii", $title, $description, $url, $user_id, $program_id, $module_id);
        return $stmt->execute();
    }
    public function getNotesByModule($module_id) {
        $sql = "SELECT * FROM content WHERE module_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("i", $module_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $notes = [];
        while ($row = $result->fetch_assoc()) {
            $notes[] = $row;
        }
        return $notes;
    }
}
?>