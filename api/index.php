<?php
class periodic_tasks {
    public function __construct($medoo_db_instance) {
        $this->db = $medoo_db_instance;
        echo $this->db->error();
    }
    function json_response($error=true, $data = null) {
        header('Content-Type: application/json');
        echo json_encode([
            'error' => $error,
            'data' => $data
        ]);
    }
    function add($description, $start, $end, $user_id) {
        $this->db->insert('tasks', [
            'user_id' => $user_id,
            'description' => $description,
            'start_date' => $start,
            'end_date' => $end,
        ]);
        return $this->json_response((bool)$this->db->error, [
            'id' => $this->db->id()
        ]);
    }
    
}
