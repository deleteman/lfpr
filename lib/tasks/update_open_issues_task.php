<?php
class UpdateOpenIssuesTask {
  public function  run() {
    Makiavelo::puts("Starting update of approval ratings");
    $projects = list_project(null, null, "published = 1");
    foreach($projects as $proj) {
      $last_delta = list_project_delta('id desc', 1, 'project_id = ' . $proj->id);
      $last_delta = $last_delta[0];
      Makiavelo::puts("Updating project " . $proj->name . " old value: " . $proj->open_issues . " new value: " . $last_delta->open_issues);
      $proj->open_issues = $last_delta->open_issues;
      save_project($proj); 
    } 
  }
}

?>
