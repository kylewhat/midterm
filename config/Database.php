<?php 
  class Database {
    // DB Params
    private string $host;
    private string $dbname;
    private string $username;
    private string $password;
    private int $port;
    private $conn;

    public function __construct(){
      $this->host = getenv('HOST');
      $this->dbname = getenv('DBNAME');
      $this->username = getenv('USERNAME');
      $this->password = getenv('PASSWORD');
      $this->port = getenv('PORT');
    }
    // DB Connect
    public function connect() {
      if($this->conn){
        return $this->conn;
      } else {
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname};";
        try { 
          $this->conn = new PDO($dsn, $this->username, $this->password);
          $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $this->conn;
        } catch(PDOException $e) {
          echo 'Connection Error: ' . $e->getMessage();
        }
      }
    }
  }