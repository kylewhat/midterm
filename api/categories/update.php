<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $category = new Category($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  if(!isset($data->category) || !isset($data->id)){
    echo json_encode(['message' => 'Missing Required Parameters']);
    return;
  }
  // Set ID to UPDATE
  $category->id = $data->id;

  $category->category = $data->category;
  // Update post
  if($category->update()) {
    echo json_encode([
      'id' => $category->id,
      'category' => $category->category
    ]);
  } else {
    echo json_encode(
      array('error' => 'Category not updated')
    );
  }
