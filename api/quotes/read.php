<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate Quote object
  $quote = new Quote($db);

  $quote->id = isset($_GET['id']) ? (int)$_GET['id'] : null;
  $quote->author_id = isset($_GET['author_id']) ? (int)$_GET['author_id'] : null;
  $quote->category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;

  $isSingleQuoteSearch = false;

  if($quote->id && !$quote->author_id && !$quote->category_id){
    $isSingleQuoteSearch = true;
  }
  // quotes read query
  $result = $quote->read();

  // Check if any quotes
  $num = $result->rowCount();

  // Check if any categories
  if($num > 0) {
        // Cat array
        $cat_arr = [];

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          $cat_item = array(
            'id' => $id,
            'category' => $category,
            'quote' => $quote,
            'author' => $author
          );

          // Push to "data"
          $cat_arr[] = $cat_item;
        }

        // if they're only search id, return a single quote

        if($isSingleQuoteSearch){
          $cat_arr = $cat_arr[0];
        }
        // Turn to JSON & output
        echo json_encode($cat_arr);

  } else {
        echo json_encode(
          array('message' => 'No Quotes Found')
        );
  }
