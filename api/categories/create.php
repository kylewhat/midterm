<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
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
  if(!isset($data->category)){
    echo json_encode(['message' => 'Missing Required Parameters']);
    return;
  }
  $category->category = $data->category;

  // Create Category
  if($category->create()) {

  // Create array
  $category_arr = array(
    'id' => $category->id,
    'category' => $category->category
  );

  // Make JSON
 echo json_encode($category_arr);
  } else {
    echo json_encode(
      array('message' => 'Category Not Created')
    );
  }
