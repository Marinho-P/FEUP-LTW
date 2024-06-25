<?php

    declare(strict_types=1);

    function generateRandomToken() {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }

    class Session {
        private array $messages;
        public function __construct() {
            session_start();
            if (!isset($_SESSION['csrf'])) {
                $_SESSION['csrf'] = generateRandomToken();
            }
            $this->messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : array();
            unset($_SESSION['messages']);
        }

        public function isLoggedIn(): bool {
            return isset($_SESSION['id']);
        }

        public function logout() {
            session_destroy();
        }

        public function getId(): ?int {
            return isset($_SESSION['id']) ? $_SESSION['id'] : null;
        }

        public function getName(): ?string {
            return isset($_SESSION['Name']) ? $_SESSION['Name'] : null;
        }

        private function setId(int $id) {
            $_SESSION['id'] = $id;
        }

        private function setName(string $name) {
            $_SESSION['name'] = $name;
        }

        public function addMessage(string $type, string $text) {
            $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
        }

        public function getMessages() {
            return $this->messages;
        }

        
        private function setClearance(string $clearance) {
            $_SESSION['clearance'] = $clearance;
        }

        public function getClearance(): ?string {
            return $_SESSION['clearance'];
        }

        public function updateSessionOnAgent(User $client) {
            $this->setId($client->id);
            $this->setName($client->name);
            if ($client->isAdmin) {
                $this->setClearance("admin");
            } else {
                $this->setClearance("agent");
            }
        }

        public function updateSessionOnClient(User $client) {
            $this->setId($client->id);
            $this->setName($client->getName());
            $this->setClearance("client");
        }

        public function updateSession(Client $client) {
            $this->setId($client->id);
            $this->setName($client->getName());
        }

        public function setArray(array $array, string $arrayName, string $flag) {
            $_SESSION[$arrayName] = $array;
            $_SESSION[$flag] = true;
        }

        public function removeArray(string $arrayName, string $flag) {
            unset($_SESSION[$arrayName]);
            unset($_SESSION[$flag]);
        }
        
    }
?>