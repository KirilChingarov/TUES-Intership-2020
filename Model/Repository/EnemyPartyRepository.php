<?php
    namespace Model\Repository;

    class EnemyPartyRepository{
        public function saveNewEnemyParty($newEnemyParty){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'INSERT INTO EnemyParty(Name)
            VALUES (:name)';

            $stmt = $pdo->prepare($sql);
            return $stmt->execute($newEnemyParty);
        }

        public function getEnemyPartyByName($enemyPartyName){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT * FROM EnemyParty
            WHERE Name = :enemyPartyName';

            $targetParty = [
                'enemyPartyName' => $enemyPartyName
            ];

            $stmt = $pdo->prepare($sql);
            $stmt->execute($targetParty);
            return $stmt->fetch();
        }

        public function checkEnemyPartyName($enemyPartyName){
            return $this->getEnemyPartyByName($enemyPartyName);
        }
    }
?>