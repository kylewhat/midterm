<?php
  // Headers

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

  if(!isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)){
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

  if($category->category){
    echo json_encode(['message' => 'category_id Not Found']);
    return;
  }

  // Set ID to UPDATE
  $quote->id = $data->id;
  $quote->quote = $data->quote;
  $quote->author_id = $data->author_id;
  $quote->category_id = $data->category_id;

  // Update post
  if($quote->update()) {
    echo json_encode([
      'id' => $quote->id,
      'quote' => $quote->quote,
      'author_id' => $quote->author_id,
      'category_id' => $quote->category_id
    ]);
  } else {
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  }
