<?php
    namespace Model\Repository;

    class EnemyPartyMembersRepository{
        public function addMemberToParty($characterId, $enemyPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'INSERT INTO EnemyPartyMembers(EnemyPartyId, CharacterId)
            VALUES(?, ?)';

            $stmt = $pdo->prepare($sql);
            return $stmt->execute(array($enemyPartyId, $characterId));
        }

        public function removeMemberFromParty($characterId, $enemyPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'DELETE FROM EnemyPartyMembers
            WHERE EnemyPartyId = ? AND CharacterId = ?
            LIMIT 1';

            $stmt = $pdo->prepare($sql);
            return $stmt->execute(array($enemyPartyId, $characterId));
        }

        public function getMembersCount($enemyPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT COUNT(*) FROM EnemyPartyMembers
            WHERE EnemyPartyId = ?';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($enemyPartyId));
            return $stmt->fetch();
        }

        public function getMemberFromEnemyPartyById($characterId, $enemyPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT * FROM EnemyPartyMembers
            WHERE EnemyPartyId = ? AND CharacterId = ?';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($enemyPartyId, $characterId));
            return $stmt->fetch();
        }
    }
?>