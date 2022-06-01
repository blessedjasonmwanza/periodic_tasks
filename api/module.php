<?php
class PeriodicTasks
{
  protected $db;
  public function __construct($medoo_db_instance)
  {
    $this->db = $medoo_db_instance;
    echo $this->db->error();
  }
  function json_response($error = true, $data = null)
  {
    header('Content-Type: application/json');
    echo json_encode([
      'error' => $error,
      'data' => $data
    ]);
  }
  function send_email($email, $subject, $message)
  {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <periodic.tasks@example.com>' . "\r\n";
    mail($email, $subject, $message, $headers);
  }


  function add($description, $start, $end, $user_id)
  {
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
  function user_tasks($user_id)
  {
    $tasks = $this->db->select('tasks', '*', [
      'user_id' => $user_id
    ]);
    return $this->json_response((bool)$this->db->error, $tasks);
  }
  function delete($task_id)
  {
    $this->db->delete('tasks', [
      'id' => $task_id
    ]);
    return $this->json_response((bool)$this->db->error, $this->db->errorInfo);
  }
  function past_due($user_id)
  {
    $tasks = $this->db->select('tasks', '*', [
      'user_id' => $user_id,
      'start_date[<=]' => date('Y-m-d')
    ]);
    return $this->json_response((bool)$this->db->error, $tasks);
  }
  function due_this_week($user_id)
  {
    
    $tasks = $this->db->select('tasks', '*', [
      'user_id' => $user_id,
      'end_date[>=]' => date('Y-m-d', strtotime('monday this week')),
      'end_date[<=]' => date('Y-m-d', strtotime('sunday this week'))
    ]);
    return $this->json_response((bool)$this->db->error, $tasks);
  }
  function due_next_week($user_id)
  {
    $tasks = $this->db->select('tasks', '*', [
      'user_id' => $user_id,
      'end_date[>=]' => date('Y-m-d', strtotime("monday next week")),
      'end_date[<=]' => date('Y-m-d', strtotime("sunday next week"))
    ]);
    return $this->json_response((bool)$this->db->error, $tasks);
  }
  function between($user_id, $start, $end)
  {
    $tasks = $this->db->select('tasks', '*', [
      'user_id' => $user_id,
      'start_date[>=]' => date('Y-m-d', strtotime($start)),
      'end_date[<=]' => date('Y-m-d', strtotime($end))
    ]);
    return $this->json_response((bool)$this->db->error, $tasks);
  }
  function mail_reminder($user_id)
  {
    $due_tasks_msg = '';
    $this->db->select(
      'tasks',
      ['description', 'end_date'],
      [
        'user_id' => $user_id,
        'start_date[<=]' => date('Y-m-d')
      ],
      function ($data) {
        global $due_tasks_msg;
        $due_tasks_msg .= $data['description'] . ' where due on ' . $data['end_date'] . '<br>';
      }
    );
    $client_email = $this->db->get('users', 'email', ['id' => $user_id]);
    if (strlen($due_tasks_msg) > 0) {
      $this->send_email($client_email, 'Task Reminder', $due_tasks_msg);
    }
    return $this->json_response((bool)$this->db->error, $this->db->errorInfo);
  }
}
