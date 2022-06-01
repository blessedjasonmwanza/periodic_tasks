<?php
require __DIR__.'/config/db.php';
require __DIR__.'/module.php';

$module = new PeriodicTasks($db);

if(isset($_GET['action'])) {
  $action = $_GET['action'];
  if(function_exists('match')){
    $api_response = match($action){
    'add' => $module->add($_POST['description'], $_POST['start'], $_POST['end'], $_POST['user_id']),
    'user_tasks' => $module->user_tasks($_POST['user_id']),
    'delete' => $module->delete($_POST['task_id']),
    'past_due' => $module->past_due($_POST['user_id']),
    'due_this_week' => $module->due_this_week($_POST['user_id']),
    'due_next_week' => $module->due_next_week($_POST['user_id']),
    'between' => $module->between($_POST['user_id'], $_POST['start'], $_POST['end']),
    'mail_reminder' => $module->mail_reminder($_POST['user_id']),
    'default' => $module->json_response(false, ['error' => 'Invalid action'])
    };
  }else{
    switch($action) {
      case 'add':
        $api_response = $module->add($_POST['description'], $_POST['start'], $_POST['end'], $_POST['user_id']);
        break;
      case 'user_tasks':
        $api_response = $module->user_tasks($_POST['user_id']);
        break;
      case 'delete':
        $api_response = $module->delete($_POST['task_id']);
        break;
      case 'past_due':
        $api_response = $module->past_due($_POST['user_id']);
        break;
      case 'due_this_week':
        $api_response = $module->due_this_week($_POST['user_id']);
      break;
      case 'due_next_week':
        $api_response = $module->due_next_week($_POST['user_id']);
      break;
      case 'between':
        $api_response = $module->between($_POST['user_id'], $_POST['start'], $_POST['end']);
        break;
      case 'mail_reminder':
        $api_response = $module->mail_reminder($_POST['user_id']);
        break;
      default:
        $api_response = $module->json_response(false, ['error' => 'Invalid action']);
        break;
    }
  }
}else{
    $api_response = $module->json_response(false, ['error' => 'No action specified']);
}
exit($api_response);
