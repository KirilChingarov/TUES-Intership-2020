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
    }
?>