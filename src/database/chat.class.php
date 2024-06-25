<?php

class Chat
{
    public int $chatId;
    public int $sellerId;
    public int $buyerId;
    public float $buyerMoney;
    public float $sellerMoney;

    public function __construct(int $chatId, int $sellerId, int $buyerId, float $buyerMoney, float $sellerMoney)
    {
        $this->chatId = $chatId;
        $this->sellerId = $sellerId;
        $this->buyerId = $buyerId;
        $this->buyerMoney = $buyerMoney;
        $this->sellerMoney = $sellerMoney;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getSellerId(): int
    {
        return $this->sellerId;
    }

    public function getBuyerId(): int
    {
        return $this->buyerId;
    }

    public function getBuyerMoney(): float
    {
        return $this->buyerMoney;
    }

    public function getSellerMoney(): float
    {
        return $this->sellerMoney;
    }
    static function createChatWithUserId(PDO $db, int $sellerId, int $buyerId): ?Chat
    {
        $stmt = $db->prepare('
        SELECT ChatId
        FROM Chat
        WHERE (SellerId = ? AND BuyerId = ?) OR (SellerId = ? AND BuyerId = ?)
        LIMIT 1
        ');
        $stmt->execute([$sellerId, $buyerId, $buyerId, $sellerId]);
        $existingChat = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingChat) {
            return Chat::extractChatWithChatId($db, $existingChat['ChatId']);
        }

        $stmt = $db->prepare('
        INSERT INTO Chat (SellerId, BuyerId, BuyerMoney, SellerMoney)
        VALUES (?, ?, 0, 0)
        ');

        $stmt->execute(array($sellerId, $buyerId));
        $chatId = $db->lastInsertId();
        return Chat::extractChatWithChatId($db, $chatId);
    }

    static function extractChatWithChatId(PDO $db, int $chatId): ?Chat
    {
        $stmt = $db->prepare('
                SELECT *
                FROM Chat
                WHERE ChatId = ?
            ');
        $stmt->execute(array($chatId));
        $curr = $stmt->fetch();

        if ($curr) {
            return new Chat(
                intval($curr['ChatId']),
                intval($curr['SellerId']),
                intval($curr['BuyerId']),
                floatval($curr['BuyerMoney']),
                floatval($curr['SellerMoney'])
            );
        }
        return null;
    }

    static function getChatsWithSellerId(PDO $db, int $sellerId): array
    {
        // Modify the SQL query to join with the Message table and order by the most recent message time
        $stmt = $db->prepare('
    SELECT Chat.*
    FROM Chat
    LEFT JOIN (
        SELECT ChatId, MAX(MessageTime) as LastMessageTime
        FROM Message
        GROUP BY ChatId
    ) LastMessages ON Chat.ChatId = LastMessages.ChatId
    WHERE Chat.SellerId = ?
    ORDER BY LastMessages.LastMessageTime DESC
');

        $stmt->execute(array($sellerId));
        $chats = array();

        while ($curr = $stmt->fetch()) {
            $chats[] = new Chat(
                intval($curr['ChatId']),
                intval($curr['SellerId']),
                intval($curr['BuyerId']),
                floatval($curr['BuyerMoney']),
                floatval($curr['SellerMoney'])
            );
        }

        return $chats;
    }

    static function getChatsWithBuyerId(PDO $db, int $buyerId): array
    {
        // SQL query to select chats by BuyerId and order by ChatId
        $stmt = $db->prepare('
    SELECT Chat.*
    FROM Chat
    LEFT JOIN (
        SELECT ChatId, MAX(MessageTime) as LastMessageTime
        FROM Message
        GROUP BY ChatId
    ) LastMessages ON Chat.ChatId = LastMessages.ChatId
    WHERE Chat.BuyerId = ?
    ORDER BY LastMessages.LastMessageTime DESC
');

        $stmt->execute(array($buyerId));
        $chats = array();

        while ($curr = $stmt->fetch()) {
            $chats[] = new Chat(
                intval($curr['ChatId']),
                intval($curr['SellerId']),
                intval($curr['BuyerId']),
                floatval($curr['BuyerMoney']),
                floatval($curr['SellerMoney'])
            );
        }

        return $chats;
    }

    static function getChatWithBuyerAndSellerId(PDO $db, int $buyerId, int $sellerId): ?Chat
    {
        $stmt = $db->prepare('
        SELECT *
        FROM Chat
        WHERE (SellerId = ? AND BuyerId = ?)
        LIMIT 1
        ');

        $stmt->execute([$sellerId, $buyerId]);
        $chat = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($chat) {
            return new Chat(
                intval($chat['ChatId']),
                intval($chat['SellerId']),
                intval($chat['BuyerId']),
                floatval($chat['BuyerMoney']),
                floatval($chat['SellerMoney'])
            );
        }

        return null;
    }
}
