<?php
namespace App\Controller;
require_once('/Applications/MAMP/htdocs/PHP_POO/chat_project/src/Entity/Message.php');
require_once('/Applications/MAMP/htdocs/PHP_POO/chat_project/src/Entity/User.php');
require_once('/Applications/MAMP/htdocs/PHP_POO/chat_project/src/Repository/MessageRepository.php');

use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;

// Cette class controller va s'occuper de recevoir les données entrées par l'user puis les envoyer dans la BDD (function postMessages())
// Il va aussi s'occuper d'afficher les message stocké dans la BDD avec la function getMessages()
// la function clearDB() va clear la base de donnée
class MessageController 
{
    private MessageRepository $messageRepository;

    public function __construct()
    {
        $this->messageRepository = new MessageRepository();
    }

    public function getMessages()
    {

        $result = $this->messageRepository->findAll("date DESC");
        $messages = [];
        foreach($result as $message) {
            $messages[] = (new Message())
                    ->setContent($message['message'])
                    ->setAuthor($message['author']);
        }
        return $messages;
    }

    public function handleRequest()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['clear_all'])){
                $this->messageRepository->clearDB();
            }
            else if(!empty($_POST['message']) and !empty($_POST['user']))
            {
                $user = new User(); 
                $message = new Message();

                $user->setName($_POST['user']);
                $userName = $user->getName();
               
                
                $message->setContent($_POST['message']);
                $message->setAuthor($userName);
                $messageContent = $message->getContent();
                $this->postMessages();
            }else{
                echo "<script>alert(\"Please fill all the form\")</script>";
            }
        }
    }

    private function postMessages()
    {
        return $this->messageRepository->addMessages($_POST['message'], $_POST['user']);
    }

}