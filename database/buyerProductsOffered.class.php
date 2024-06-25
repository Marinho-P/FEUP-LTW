<?php

class BuyerProductsOffered{
    public int $productId;
    public int $chatId;
    public int $quantity;

    public function __construct(int $productId, int $chatId, int $quantity)
    {
        $this->productId = $productId;
        $this->chatId = $chatId;
        $this->quantity = $quantity;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    static function extractBuyerProductsOfferedWithChatId(PDO $db, int $chatId): ?array
    {
        $stmt = $db->prepare('
                SELECT *
                FROM BuyerProductsOffered
                WHERE ChatId = ?
            ');
        $stmt->execute(array($chatId));
        $result = array();
        while ($curr = $stmt->fetch()) {
            $result[] = new BuyerProductsOffered(intval($curr['ProductId']), 
                                intval($curr['ChatId']), 
                                intval($curr['Quantity']));
        }
        return $result;
    }


}