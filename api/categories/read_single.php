<?php

  // Headers

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();
  // Instantiate blog category object
  $category = new Category($db);

  // Get ID
  $category->id = isset($_GET['id']) ? (int)$_GET['id'] : die();

  // Get post
  $category->read_single();

  if(!$category->category){
    return;
  }
  // Create array
  $category_arr = array(
    'id' => $category->id,
    'category' => $category->category
  );

  // Make JSON
 echo json_encode($category_arr);
