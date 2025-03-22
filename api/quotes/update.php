<?php
  // Headers

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $quote = new Quote($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  if(!$data?->id || !$data?->quote || !$data?->author_id || !$data?->category_id){
    echo json_encode(['message' => 'Missing Required Parameters']);
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
