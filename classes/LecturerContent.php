<?php
class LecturerContent {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addContent($module_id, $lecturer_id, $title, $description, $type, $file_path, $url) {
        $final_path = $file_path ? $file_path : $url;
        $stmt = $this->db->prepare("INSERT INTO content (module_id, uploaded_by, title, description, content_type, file_path, upload_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iissss", $module_id, $lecturer_id, $title, $description, $type, $final_path);
        return $stmt->execute();
    }

    public function getModuleContents($module_id) {
        $stmt = $this->db->prepare("SELECT * FROM content WHERE module_id = ? AND status != 'rejected' ORDER BY upload_date DESC");
        $stmt->bind_param("i", $module_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function updateContent($content_id, $title, $description, $type, $file_path, $url, $reviewer_id, $review_notes) {
        $final_path = $file_path ? $file_path : $url;
        $status = 'pending';
        $stmt = $this->db->prepare("UPDATE content SET title = ?, description = ?, content_type = ?, file_path = ?, status = ?, reviewed_by = ?, review_date = NOW(), review_notes = ? WHERE id = ?");
        $stmt->bind_param("sssssisi", $title, $description, $type, $final_path, $status, $reviewer_id, $review_notes, $content_id);
        return $stmt->execute();
    }

    public function deleteContent($content_id, $reviewer_id, $review_notes) {
        $status = 'rejected';
        $stmt = $this->db->prepare("UPDATE content SET status = ?, reviewed_by = ?, review_date = NOW(), review_notes = ? WHERE id = ?");
        $stmt->bind_param("sisi", $status, $reviewer_id, $review_notes, $content_id);
        return $stmt->execute();
    }
    
    public function getContentById($content_id) {
        $stmt = $this->db->prepare("SELECT * FROM content WHERE id = ?");
        $stmt->bind_param("i", $content_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>