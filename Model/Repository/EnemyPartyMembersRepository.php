<?php
    namespace Model\Repository;

    class EnemyPartyMembersRepository{
        public function addMemberToParty($characterId, $enemyPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'INSERT INTO EnemyPartyMembers(EnemyPartyId, CharacterId)
            VALUES(:enemyPartyId, :characterId)';

            $newMember = [
                'enemyPartyId' => $enemyPartyId,
                'characterId' => $characterId
            ];

            $stmt = $pdo->prepare($sql);
            return $stmt->execute($newMember);
        }

        public function removeMemberFromParty($characterId, $enemyPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'DELETE FROM EnemyPartyMembers
            WHERE EnemyPartyId = :enemyPartyId AND CharacterId = :characterId
            LIMIT 1';

            $targetMember = [
                'enemyPartyId' => $enemyPartyId,
                'characterId' => $characterId,
            ];

            $stmt = $pdo->prepare($sql);
            return $stmt->execute($targetMember);
        }

        public function getMembersCount($enemyPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT COUNT(*) AS membersCount FROM EnemyPartyMembers
            WHERE EnemyPartyId = :enemyPartyId';

            $targetEnemyParty = [
                'enemyPartyId' => $enemyPartyId
            ];

            $stmt = $pdo->prepare($sql);
            $stmt->execute($targetEnemyParty);
            return $stmt->fetch();
        }

        public function getMemberFromEnemyPartyById($characterId, $enemyPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT * FROM EnemyPartyMembers
            WHERE EnemyPartyId = :enemyPartyId AND CharacterId = :characterId';

            $targetMember = [
                'enemyPartyId' => $enemyPartyId,
                'characterId' => $characterId
            ];

            $stmt = $pdo->prepare($sql);
            $stmt->execute($targetMember);
            return $stmt->fetch();
        }

        public function getMembersFromEnemyParty($enemyPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT  * FROM EnemyPartyMembers
            WHERE EnemyPartyId = :enemyPartyId';

            $enemyParty = ['enemyPartyId' => $enemyPartyId];

            $stmt = $pdo->prepare($sql);
            $stmt->execute($enemyParty);
            return $stmt->fetchAll();
        }
    }
?>