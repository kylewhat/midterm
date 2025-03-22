Control<?php
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
      'author' => $quote->author,
      'category' => $quote->category
    ]);
  } else {
    http_response_code(400);
    echo json_encode(
      array('error' => 'Quote not updated')
    );
  }
