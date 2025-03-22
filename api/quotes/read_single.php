<?php

  // Headers

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();
  // Instantiate blog author object
  $author = new Author($db);

  // Get ID
  $author->id = isset($_GET['id']) ? (int)$_GET['id'] : die();

  // Get post
  $author->read_single();

  if(!$author->author){
    return;
  }
  // Create array
  $author_arr = array(
    'id' => $author->id,
    'author' => $author->author
  );

  // Make JSON
 echo json_encode($author_arr);
