<?php
    namespace Model\Repository;

    class PlayerPartyRepository{
        public function saveNewParty($newParty){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'INSERT INTO PlayerParty(Name)
            VALUES (:name)';

            $stmt = $pdo->prepare($sql);
            return $stmt->execute($newParty);
        }

        public function getPartyByName($partyName){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT * FROM PlayerParty
            WHERE Name = ?';

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$partyName]);
            return $stmt->fetch();
        }

        public function checkPartyName($partyName){
            return $this->getPartyByName($partyName);
        }
    }
?>