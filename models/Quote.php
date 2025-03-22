<?php
  class Quote {
    // DB Stuff
    private $conn;
    private $table = 'quotes';

    // Properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;
    public $author;
    public $category;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get quotes
    public function read() {
      // Create query
      $query = 'SELECT
        id,
        quote,
      FROM
        ' . $this->table . '
      ORDER BY
        id DESC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Quote
  public function read_single(){
    // Create query
    $query = 'SELECT
          id,
          quote
        FROM
          ' . $this->table . '
      WHERE id = ?
      LIMIT 1';

      //Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $this->id);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if(!$row){
        http_response_code(404);
        echo json_encode(['error' => 'Quote not found']);
        return;
      }
      // set properties
      $this->id = $row['id'];
      $this->quote = $row['quote'];
  }

  // Create Quote
  public function create() {
    // Create Query
    $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id) RETURNING id';


  // Prepare Statement
  $stmt = $this->conn->prepare($query);

  // Clean data
  $this->quote = htmlspecialchars(strip_tags($this->quote));
  $this->author_id = htmlspecialchars(strip_tags($this->author_id));
  $this->category_id = htmlspecialchars(strip_tags($this->category_id));

  // Bind data
  $stmt->bindParam(':quote', $this->quote);
  $stmt->bindParam(':author_id', $this->author_id);
  $stmt->bindParam(':category_id', $this->category_id);
  
  if ($stmt->execute()) {
    // Get the last inserted ID
    $this->id = $stmt->fetchColumn();

    $query = 'SELECT
        q.id,
        q.quote,
        a.author,
        c.category
      FROM
        quotes AS q
      JOIN 
        categories AS c
          ON c.id = q.category_id
      JOIN 
        authors AS a
          ON a.id = q.author_id
      
    WHERE q.id = ?
    LIMIT 1';

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->id);

    // Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row){

    // set properties
    $this->id = $row['id'];
    $this->quote = $row['quote'];
    $this->author = $row['author'];
    $this->category = $row['category'];
    return true;
    }
  }
  return false;
  }

  // Update Quote
  public function update() {
    // Create Query
    $query = 'UPDATE ' .
      $this->table . '
    SET
      quote = :quote,
      author_id = :author_id,
      category_id = :category_id
      WHERE
      id = :id';

  // Prepare Statement
  $stmt = $this->conn->prepare($query);

  // Clean data
  $this->quote = htmlspecialchars(strip_tags($this->quote));
  $this->author_id = htmlspecialchars(strip_tags($this->author_id));
  $this->category_id = htmlspecialchars(strip_tags($this->category_id));
  $this->id = htmlspecialchars(strip_tags($this->id));

  // Bind data
  $stmt-> bindParam(':quote', $this->quote);
  $stmt-> bindParam(':author_id', $this->author_id);
  $stmt-> bindParam(':category_id', $this->category_id);
  $stmt-> bindParam(':id', $this->id);

  if ($stmt->execute()) {

    $query = 'SELECT
        q.id,
        q.quote,
        a.author,
        c.category
      FROM
        quotes AS q
      JOIN 
        categories AS c
          ON c.id = q.category_id
      JOIN 
        authors AS a
          ON a.id = q.author_id
      
    WHERE q.id = ?
    LIMIT 1';

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->id);

    // Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row){

    // set properties
    $this->id = $row['id'];
    $this->quote = $row['quote'];
    $this->author = $row['author'];
    $this->category = $row['category'];
    return true;
    }
  }
  return false;

  }

  // Delete Quote
  public function delete() {
    // Create query
    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // clean data
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind Data
    $stmt-> bindParam(':id', $this->id);

    // Execute query
    $stmt->execute();

      if($stmt->rowCount() > 0) {
        return true; // Row deleted
      } else {
        return false;
      }
    }
  }
