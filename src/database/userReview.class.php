<?php

declare(strict_types=1);
class UserReview{
    public int $reviewerId;
    public int $reviewedId;
    public int $stars;
    public string $comment;

    public function __construct(int $reviewerId, int $reviewedId, int $stars, string $comment)
    {
        $this->reviewerId = $reviewerId;
        $this->reviewedId = $reviewedId;
        $this->stars = $stars;
        $this->comment = $comment;
    }

    public function getReviewerId(): int
    {
        return $this->reviewerId;
    }

    public function getReviewedId(): int
    {
        return $this->reviewedId;
    }

    public function getStars(): int
    {
        return $this->stars;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    static function getReviewsByReviewedId(PDO $db, $reviewedId): array {
        $stmt = $db->prepare('SELECT * FROM UserReview WHERE ReviewedId = ?');
        $stmt->execute([$reviewedId]);
        
        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $review = new UserReview($row['ReviewerId'], $row['ReviewedId'], $row['StarsNumber'], $row['Description']);
            $reviews[] = $review;
        }
        
        return $reviews;
    }

}