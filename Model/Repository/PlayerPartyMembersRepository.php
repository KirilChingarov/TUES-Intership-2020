<?php
    namespace Model\Repository;

    class PlayerPartyMembersRepository{
        public function addMemberToParty($characterId, $playerPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'INSERT INTO PlayerPartyMembers(PlayerPartyId, CharacterId)
            VALUES(:playerPartyId, :characterId)';

            $newMember = [
                'playerPartyId' => $playerPartyId,
                'characterId' => $characterId
            ];

            $stmt = $pdo->prepare($sql);
            return $stmt->execute($newMember);
        }

        public function removeMemberFromParty($characterId, $playerPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'DELETE FROM PlayerPartyMembers
            WHERE PlayerPartyId = :playerPartyId AND CharacterId = :characterId';

            $targetMember = [
                'playerPartyId' => $playerPartyId,
                'characterId' => $characterId
            ];

            $stmt = $pdo->prepare($sql);
            return $stmt->execute($targetMember);
        }

        public function getMembersCount($playerPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT COUNT(*) AS membersCount FROM PlayerPartyMembers
            WHERE PlayerPartyId = :playerPartyId';

            $targetParty = [
                'playerPartyId' => $playerPartyId
            ];

            $stmt = $pdo->prepare($sql);
            $stmt->execute($targetParty);
            return $stmt->fetch();
        }

        public function getMemberFromPartyById($characterId, $playerPartyId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT * FROM PlayerPartyMembers
            WHERE PlayerPartyId = :playerPartyId AND CharacterId = :characterId';

            $targetMember = [
                'playerPartyId' => $playerPartyId,
                'characterId' => $characterId
            ];

            $stmt = $pdo->prepare($sql);
            $stmt->execute($targetMember);
            return $stmt->fetch();
        }
    }
?>