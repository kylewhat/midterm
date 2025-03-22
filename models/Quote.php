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
        q.id,
        q.quote,
        a.author,
        c.category
      FROM ' . $this->table . ' AS q
      JOIN 
        categories AS c
          ON c.id = q.category_id
      JOIN 
        authors AS a
          ON a.id = q.author_id';

      if($this->id || $this->author_id || $this->category_id){
        $query .= " WHERE 1=1";

        if($this->id){
          $query .= " AND q.id = :quote_id";
        }
        if($this->author_id){
          $query .= " AND a.id = :author_id";
        }
        if($this->category_id){
          $query .= " AND c.id = :category_id";
        }
      }
      
      // Prepare statement
      $stmt = $this->conn->prepare($query);
      
      $this->id = $this->id ? htmlspecialchars(strip_tags($this->id)) : null;
      $this->author_id = $this->author_id ? htmlspecialchars(strip_tags($this->author_id)) : null;
      $this->category_id = $this->category_id ? htmlspecialchars(strip_tags($this->category_id)) : null;
    
      // Bind data
      if($this->id){
        $stmt->bindParam(':quote_id', $this->id);
      }
      if($this->author_id){
        $stmt->bindParam(':author_id', $this->author_id);

      }
      if($this->category_id){
        $stmt->bindParam(':category_id', $this->category_id);
      }
      $stmt->execute();
      // Execute query
      return $stmt;
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
