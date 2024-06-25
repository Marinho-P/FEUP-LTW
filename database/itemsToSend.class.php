<?php
    declare(strict_types=1);
class ItemsToSend
{
    public int $orderId;
    public int $itemId;
    public int $quantity;

    public function __construct($orderId, $itemId, $quantity)
    {
        $this->orderId = $orderId;
        $this->itemId = $itemId;
        $this->quantity = $quantity;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function getItemId()
    {
        return $this->itemId;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
    public static function createItemToSend($db, $orderId, $itemId, $quantity)
    {
        $stmt = $db->prepare('INSERT INTO ItemsToSend (OrderId, ItemId, Quantity) VALUES (?, ?, ?)');
        $stmt->execute([$orderId, $itemId, $quantity]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $db->lastInsertId();
    }
    public static function getItemsToSendByOrderIdAndItemId($db, $orderId, $itemId)
    {
        $stmt = $db->prepare('SELECT * FROM ItemsToSend WHERE OrderId = ? AND ItemId = ?');
        $stmt->execute([$orderId, $itemId]);
        $curr = $stmt->fetch();
        if (!$curr) return null;
        return new ItemsToSend($curr['OrderId'], $curr['ItemId'], $curr['Quantity']);
    }
    public static function UpdateQuantity($db, $orderId, $itemId, $quantity)
    {
        $stmt = $db->prepare('UPDATE ItemsToSend SET Quantity = ? WHERE OrderId = ? AND ItemId = ?');
        $stmt->execute([$quantity, $orderId, $itemId]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $db->lastInsertId();
    }
}