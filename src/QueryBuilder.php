<?php

namespace App;
require_once('/Applications/MAMP/htdocs/PHP_POO/chat_project/src/Connection.php');
use App\Connection;

// QueryBuilder abstract car, cette class va initier toutes les requetes sql qui ne seront jamais changé
// init des query sql
abstract class QueryBuilder {
    
    private $connection;
    protected $table;

    public function __construct() 
    {
        $this->connection = Connection::getMyPDO();
    }

    public function select(string $order = "date DESC") 
    {
        $sql = "SELECT * from {$this->table}";
        if($order) {
            $sql .= " ORDER BY $order";
        }
        return $sql;
    }

    public function insert(string $author, string $contents)
    {
        $query = $this->connection->prepare("INSERT INTO messages SET author = :author, content = :content, created_at = NOW()");
        $query->execute(compact('author', 'content'));
    }

    public function delete()
    {
        $query = $this->connection->prepare("DELETE FROM messages");
        $query->execute();
    }

    
}
