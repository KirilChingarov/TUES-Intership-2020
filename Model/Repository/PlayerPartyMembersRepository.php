<?php
    namespace Model\Repository;

    class PlayerPartyMembersRepository{
        public function addMemberToParty($characterId, $playerPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'INSERT INTO PlayerPartyMembers(PlayerPartyId, CharacterId)
            VALUES(?, ?)';

            $stmt = $pdo->prepare($sql);
            return $stmt->execute(array($playerPartyId, $characterId));
        }

        public function removeMemberFromParty($characterId, $playerPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'DELETE FROM PlayerPartyMembers
            WHERE PlayerPartyId = ? AND CharacterId = ?';

            $stmt = $pdo->prepare($sql);
            return $stmt->execute(array($playerPartyId, $characterId));
        }

        public function getMembersCount($playerPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT COUNT(*) FROM PlayerPartyMembers
            WHERE PlayerPartyId = ?';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($playerPartyId));
            return $stmt->fetch();
        }

        public function getMemberFromPartyById($characterId, $playerPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT * FROM PlayerPartyMembers
            WHERE PlayerPartyId = ? AND CharacterId = ?';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($playerPartyId, $characterId));
            return $stmt->fetch();
        }
    }
?>