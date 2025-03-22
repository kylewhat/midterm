<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Quoteization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';
  include_once '../../models/Author.php';
  include_once '../../models/Category.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $quote = new Quote($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  if(!$data?->quote || !$data?->author_id || !$data?->category_id){
    echo json_encode(['message' => 'Missing Required Parameters']);
    return;
  }

  $author = new Author($db);
  $author->id = $data->author_id;
  $authorResult = $author->read_single();

  if(!$author->author){
    echo json_encode(['message' => 'author_id Not Found']);
    return false;
  }

  $category = new Category($db);
  $category->id = $data->category_id;
  $categoryResult = $category->read_single();

  if($categoryResult < 1){
    echo json_encode(['message' => 'category_id Not Found']);
    return;
  }

  $quote->quote = $data->quote;
  $quote->author_id = $data->author_id;
  $quote->category_id = $data->category_id;


  // Create quote
  if($quote->create()) {

  // Create array
  $quote_arr = array(
    'id' => $quote->id,
    'quote' => $quote->quote,
    'author_id' => $quote->author_id,
    'category_id' => $quote->category_id
  );

  // Make JSON
 echo json_encode($quote_arr);
  } else {
    echo json_encode(
      array('message' => 'Quote Not Created')
    );
  }
