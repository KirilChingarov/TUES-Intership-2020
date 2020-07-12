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
            WHERE Name = :playerPartyName';

            $targetParty = [
                'playerPartyName' => $partyName
            ];

            $stmt = $pdo->prepare($sql);
            $stmt->execute($targetParty);
            return $stmt->fetch();
        }

        public function checkPartyName($partyName){
            return $this->getPartyByName($partyName);
        }

        public function getPlayerPartyCount(){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT COUNT(*) AS playerPartyCount FROM PlayerParty';

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return (int)$stmt->fecth()['playerPartyCount'];
        }
    }
?>