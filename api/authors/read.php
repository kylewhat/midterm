<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate author object
  $author = new Author($db);

  // Author read query
  $result = $author->read();
  
  // Get row count
  $num = $result->rowCount();

  // Check if any categories
  if($num > 0) {
        // Cat array
        $cat_arr = [];

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          $cat_item = array(
            'id' => $id,
            'author' => $author
          );

          // Push to "data"
          $cat_arr[] = $cat_item;
        }

        // Turn to JSON & output
        echo json_encode($cat_arr);

  } else {
        echo json_encode(
          array('message' => 'No Authors Found')
        );
  }
