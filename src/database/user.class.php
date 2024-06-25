<?php

declare(strict_types=1);

class User
{
    public int $id;
    public string $name;
    public string $email;
    public string $username;
    public string $password;
    public bool $isAdmin;
    public ?string $phoneNumber = null;
    public ?string $address = null;
    public ?string $description = null; 


    public function __construct(int $id, string $name, string $email, string $username,string $password, bool $isAdmin, ?string $phoneNumber, ?string $address, ?string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->isAdmin = $isAdmin;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
        $this->description = $description;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
    public function setUsername(string $username)
    {
        $this->username = $username;
    }
    public function setPassword(string $password)
    {
        $this->password = $password;
    }
    public function setAdminStatus(bool $isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }
    public function setPhoneNumber(?string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }
    public function setAddress(?string $address)
    {
        $this->address = $address;
    }
    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getAdminStatus(): bool
    {
        return $this->isAdmin;
    }
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }
    public function getAddress(): ?string
    {
        return $this->address;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }

    

    public function getPasswordWithUserId(PDO $db, int $userId): ?string
    {
        $stmt = $db->prepare('
        SELECT Password
        FROM User
        WHERE UserId = ?
    ');
        $stmt->execute(array($userId));
        $user = $stmt->fetch();
        if ($user) {
            return $user['Password'];
        } else {
            return null; // Return null if the user is not found
        }
    }

    static function getUserWithPassword(PDO $db, string $username, string $password)
    {
        $smtm = $db->prepare('
                    SELECT *
                    FROM User
                    WHERE lower(Username) = ?
                ');
        $smtm->execute(array(strtolower($username)));
        $user = $smtm->fetch();
        if ($user && (password_verify($password, $user['Password']))) {
            return new User(
                intval($user['UserId']),
                $user['Name'],
                $user['Email'],
                $user['Username'],$user['Password'],boolval($user['AdminStatus']),$user['PhoneNumber'],$user['Address'],$user['Description']
            );
        } else {
            return null;
        }
    }

    static public function verifyPassword(PDO $db, string $username, string $password): bool
    {
        $smtm = $db->prepare('
                    SELECT Password
                    FROM User
                    WHERE Username = ?
                ');
        $smtm->execute(array($username));
        $user = $smtm->fetch();
        /*
            print_r($user['Password']);
            print_r(password_hash($password, PASSWORD_DEFAULT));
            var_dump($user['Password']);
            die();
            if (!$user){
                echo "Try again.";
            }else{
                echo "cool";
            }
            */
        if (!$user) return false;
            
        if (password_verify($password, $user['Password'])) return true;
        return false;
    }

    static function getUserWithId(PDO $db, int $id): ?User
    {
        $smtm = $db->prepare('
                    SELECT *
                    FROM User
                    WHERE UserId = ?
                ');
        $smtm->execute(array($id));
        $user = $smtm->fetch();
        return new User(
            intval($user['UserId']),
                $user['Name'],
                $user['Email'],
                $user['Username'],$user['Password'],boolval($user['AdminStatus']),$user['PhoneNumber'],$user['Address'],$user['Description']
        );
    }

    static function getUserWithName(PDO $db, string $name): ?User
    {
        $temp = explode(" ", $name);
        $smtm = $db->prepare('
                    SELECT *
                    FROM User
                    WHERE lower(Name) = ?
                ');
        $smtm->execute(array(strtolower($name)));
        $user = $smtm->fetch();
        return new User(
            intval($user['UserId']),
                $user['Name'],
                $user['Email'],
                $user['Username'],$user['Password'],boolval($user['AdminStatus']),$user['PhoneNumber'],$user['Address'],$user['Description']
        );
    }

    static function getUserWithEmail(PDO $db, string $email): ?User
    {
        $smtm = $db->prepare('
                    SELECT *
                    FROM User
                    WHERE Email = ?
                ');
        $smtm->execute(array($email));
        $user = $smtm->fetch();
        return new User(
            intval($user['UserId']),
                $user['Name'],
                $user['Email'],
                $user['Username'],$user['Password'],boolval($user['AdminStatus']),$user['PhoneNumber'],$user['Address'],$user['Description']
        );
    }

    static function isUsernameUsed(PDO $db, string $username)
    {
        $smtm = $db->prepare('
                SELECT *
                FROM User
                WHERE lower(Username) = ?
                ');
        $smtm->execute(array(strtolower($username)));
        if ($smtm->fetch()) {
            return true;
        } else {
            return false;
        }
    }

    static function isEmailUsed(PDO $db, string $email)
    {
        $smtm = $db->prepare('
                SELECT *
                FROM User
                WHERE lower(Email) = ?
                ');
        $smtm->execute(array(strtolower($email)));
        if ($smtm->fetch()) {
            return true;
        } else {
            return false;
        }
    }

    static function createUser(PDO $db, string $name, string $email, string $username, string $password)
    {
        $stmt = $db->prepare('
                    INSERT INTO User(Name, Email, Username, Password) 
                    VALUES(?,?,?,?)
                ');
        $stmt->execute(array($name, $email, $username, $password));
    }

    public function updateUser(PDO $db)
    {
        $stmt = $db->prepare('
            UPDATE User SET Name = ?, Email = ?, Username = ?, PhoneNumber = ?, Address = ?, Description = ?
            WHERE UserId = ?
        ');
        $stmt->execute(array($this->name, $this->email, $this->username, $this->phoneNumber, $this->address, $this->description, $this->id));
    }

    public function alterPassword(PDO $db, string $newPassword)
    {
        $stmt = $db->prepare('
            UPDATE User SET Password = ?
            WHERE  UserId = ?
        ');
        $stmt->execute(array(password_hash($newPassword, PASSWORD_DEFAULT), $this->id));
    }

    /*
        static public function getAllUsers(PDO $db): array {
            $smtm = $db->prepare('
                    SELECT *
                    FROM User
                ');
            $smtm->execute();
            $raw = $smtm->fetchAll();
            $result = [];
            foreach ($raw as $user) {
                $agent = Agent::extractAgentWithId($db, intval($user['id']));
                if (!$agent) {
                    $curr = new User(
                        intval($user['id']),
                        $user['name'],
                        $user['email'],
                        $user['username'],
                    );
                    array_push($result, $curr);
                }
            }
            usort($result, function (User $a, User $b) {
                return strcmp($a->getName(), $b->getName());
            });
            return $result;
        }
        */

    public function checkPassword(PDO $db, string $password): bool{
        $stmt = $db->prepare('
            SELECT Password
            FROM User
            WHERE UserId = ?
        ');
        $stmt->execute(array($this->id));
        $user = $stmt->fetch();
        if (password_verify($password, $user['Password'])) {
            return true;
        } else {
            return false;
        }
    }

    static function getUserItems(PDO $db, int $userId): array
    {
        $stmt = $db->prepare('SELECT * FROM Item WHERE OwnerId = :OwnerId');
        $stmt->bindParam(':OwnerId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        ;
    }
}
