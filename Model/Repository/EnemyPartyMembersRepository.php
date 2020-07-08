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
            WHERE EnemyPartyId = ? AND CharacterId = ?';

            $stmt = $pdo->prepare($sql);
            return $stmt->execute(array($enemyPartyId, $characterId));
        }
    }
?>