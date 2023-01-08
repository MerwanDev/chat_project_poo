<?php
namespace App\Repository;
require_once('/Applications/MAMP/htdocs/PHP_POO/chat_project/src/Connection.php');
require_once('/Applications/MAMP/htdocs/PHP_POO/chat_project/src/QueryBuilder.php');

use App\Connection;
use App\QueryBuilder;

// class
class MessageRepository extends QueryBuilder
{
    private $db;
    protected $table = "messages";

    public function __construct() 
    {
        $this->db = Connection::getMyPDO();
    }
    
    public function findAll(?string $order = ""): array
    {
        $sql = $this->select($order);
        return $this->db->query($sql)->fetchAll();

    }

    public function findById(int $id): array
    {
        $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $query->execute(['id' => $id]);
        $item = $query->fetch();
        return $item;
    }

    public function addMessages(string $message, string $author)
    {
        $query = $this->db->prepare("INSERT INTO {$this->table} (message, author, date) VALUES (:message, :author, NOW())");
        $query->execute(['message' => $message, 'author' => $author]);
        header('Location: index.php');
        exit();
    }
    public function clearDB()
    {
        $query = $this->db->prepare("DELETE FROM messages");
        $query->execute();
        header('Location: index.php');
        exit();
    }
}