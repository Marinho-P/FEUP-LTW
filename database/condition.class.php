<?php

class Condition{
    public int $conditionId;
    public string $name;

    public function __construct(int $conditionId, string $name)
    {
        $this->conditionId = $conditionId;
        $this->name = $name;
    }

    public function getConditionId(): int
    {
        return $this->conditionId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    static function getConditions(PDO $db): array
    {
        $stmt = $db->prepare('SELECT * FROM Condition');
        $stmt->execute();
        $conditions = $stmt->fetchAll();
        $conditionArray = array();
        foreach($conditions as $condition){
            $conditionArray[] = new Condition($condition['ConditionId'], $condition['Name']);
        }
        return $conditionArray;
    }

    static function extractConditionWithConditionId(PDO $db, int $conditionId): ?Condition
    {
        $stmt = $db->prepare('
                SELECT *
                FROM Condition
                WHERE ConditionId = ?
            ');
        $stmt->execute(array($conditionId));
        $curr = $stmt->fetch();

        if ($curr) {
            return new Condition(intval($curr['ConditionId']), 
                                    $curr['Name']);
        }
        return null;
    }
    
    static function getConditionIdByName(PDO $db, ?string $name): ?int
    {
        if ($name == null) {
            return null;
        }
        $stmt = $db->prepare('
                SELECT ConditionId
                FROM Condition
                WHERE Name = ?
            ');
        $stmt->execute(array($name));
        $curr = $stmt->fetch();

        if ($curr) {
            return intval($curr['ConditionId']);
        }
        return null;
    }

    static function existsConditionById(PDO $db, int $conditionId): bool
    {
        $stmt = $db->prepare('
            SELECT *
            FROM Condition
            WHERE ConditionId = ?
            ');
        $stmt->execute(array($conditionId));
        return $stmt->fetch() != null;
    }
}