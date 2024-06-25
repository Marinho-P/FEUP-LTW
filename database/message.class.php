<?php

class Message{
    public int $id;
    public int $senderId;
    public int $chatId;
    public string $content;
    public string $timeSent; // Can be DateTime object

    public function __construct(int $id, int $senderId, int $chatId, string $content, string $timeSent)
    {
        $this->id = $id;
        $this->senderId = $senderId;
        $this->chatId = $chatId;
        $this->content = $content;
        $this->timeSent = $timeSent;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSenderId(): int
    {
        return $this->senderId;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getTimeSent(): string
    {
        return $this->timeSent;
    }
    

     static function getMessagesByChatId(PDO $DB, int $chatId): array
    {
        $stmt = $DB->prepare('SELECT * FROM Message WHERE ChatId = :ChatId');
        $stmt->bindParam(':ChatId', $chatId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Message');
    }

    static function getMessagesBySenderId(PDO $DB, int $senderId): array
    {
        $stmt = $DB->prepare('SELECT * FROM Message WHERE MessengerId = :MessengerId');
        $stmt->bindParam(':MessengerId', $senderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Message');
    }
    
    static function getMostRecentMessageByChatId(PDO $DB, int $chatId): ?Message
    {
        try {
            $stmt = $DB->prepare('SELECT MessageId, MessengerId, ChatId, MessageText, MessageTime FROM Message WHERE ChatId = :ChatId ORDER BY MessageTime DESC LIMIT 1');
            $stmt->bindParam(':ChatId', $chatId, PDO::PARAM_INT);
            $stmt->execute();
    
            // Bind the result columns to variables
            $stmt->bindColumn('MessageId', $messageId);
            $stmt->bindColumn('MessengerId', $messengerId);
            $stmt->bindColumn('ChatId', $chatId);
            $stmt->bindColumn('MessageText', $messageText);
            $stmt->bindColumn('MessageTime', $messageTime);
    
            // Fetch the result
            $stmt->fetch(PDO::FETCH_BOUND);
    
            // Create a new instance of the Message class using the fetched data
            return new Message($messageId, $messengerId, $chatId, $messageText, $messageTime);
        } catch (PDOException $e) {
            // Handle any PDOExceptions (database errors)
            echo "Error: " . $e->getMessage();
            return null; // Return null on error
        }
    }
    static function getAllMessagesByChatId(PDO $DB, int $chatId): ?array
{
    try {
        $query = 'SELECT MessageId, MessengerId, ChatId, MessageText, MessageTime FROM Message WHERE ChatId = :ChatId ORDER BY MessageTime DESC';
        $stmt = $DB->prepare($query);
        $stmt->bindParam(':ChatId', $chatId, PDO::PARAM_INT);
        $stmt->execute();

        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = new Message(
                intval($row['MessageId']),
                intval($row['MessengerId']),
                intval($row['ChatId']),
                $row['MessageText'],
                $row['MessageTime']
            );
        }

        return $messages;
    } catch (PDOException $e) {
        // Handle any PDOExceptions (database errors)
        echo "Error: " . $e->getMessage();
        return null; // Return null on error
    }
}

static function addNewMessage(PDO $DB, int $chatId, int $senderId, string $content): bool
{
    try {
        date_default_timezone_set('Europe/Lisbon');
        $stmt = $DB->prepare('INSERT INTO Message (MessengerId, ChatId, MessageText, MessageTime) VALUES (:MessengerId, :ChatId, :MessageText, :MessageTime)');
        $stmt->bindParam(':MessengerId', $senderId, PDO::PARAM_INT);
        $stmt->bindParam(':ChatId', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':MessageText', $content, PDO::PARAM_STR);
        
        // Get the current timestamp
        $currentTime = date('Y-m-d H:i:s');
        $stmt->bindParam(':MessageTime', $currentTime);
        
        // Execute the statement
        $stmt->execute();
        
        // Return true if the execution was successful
        return true;
    } catch (PDOException $e) {
        // Handle any PDOExceptions (database errors)
        echo "Error: " . $e->getMessage();
        
        // Return false if an error occurred
        return false;
    }
}
    static function seeIfMostRecentMessageIsFromOtherUser(PDO $DB, int $chatId, int $userId): bool
    {
        try {
            $stmt = $DB->prepare('SELECT MessageId, MessengerId, ChatId, MessageText, MessageTime FROM Message WHERE ChatId = :ChatId ORDER BY MessageTime DESC LIMIT 1');
            $stmt->bindParam(':ChatId', $chatId, PDO::PARAM_INT);
            $stmt->execute();
    
            // Bind the result columns to variables
            $stmt->bindColumn('MessageId', $messageId);
            $stmt->bindColumn('MessengerId', $messengerId);
            $stmt->bindColumn('ChatId', $chatId);
            $stmt->bindColumn('MessageText', $messageText);
            $stmt->bindColumn('MessageTime', $messageTime);
    
            // Fetch the result
            $stmt->fetch(PDO::FETCH_BOUND);
    
            // Return true if the most recent message is from the other user
            return $messengerId !== $userId;
        } catch (PDOException $e) {
            // Handle any PDOExceptions (database errors)
            echo "Error: " . $e->getMessage();
            return false; // Return false on error
        }
       
    }
    
}