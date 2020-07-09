<?php
    namespace Model\Repository;

    class CharacterRepository{
        public function getCharacterByName($characterName){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT * FROM Characters
            WHERE Name = :name';

            $targetCharacter = [
                'name' => $characterName
            ];

            $stmt = $pdo->prepare($sql);
            $stmt->execute($targetCharacter);
            return $stmt->fetch();
        }

        public function getCharacterById($characterId){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT * FROM Characters
            WHERE CharacterId = :Id';

            $targetCharacter = [
                'Id' => $characterId
            ];

            $stmt = $pdo->prepare($sql);
            $stmt->execute($targetCharacter);
            return $stmt->fetch();
        }

        public function saveCharacter($character){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'INSERT INTO Characters(Name, Health, AttackDamage, Mana)
            VALUES (:name, :health, :attackDamage, :mana)';

            $stmt = $pdo->prepare($sql);
            return $stmt->execute($character);
        }

        public function checkCharacterName($characterName){
            return $this->getCharacterByName($characterName);
        }

        public function updateCharacter($character){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'UPDATE 
                Characters
            SET 
                Name = :characterName, 
                Health = :characterHealth, 
                AttackDamage = :characterAttackDamage, 
                Mana = :characterMana
            WHERE 
                CharacterId = :characterId';

            $stmt = $pdo->prepare($sql);
            return $stmt->execute($character);
        }
    }
?>