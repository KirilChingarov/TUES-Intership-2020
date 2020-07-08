<?php
    namespace Model\Repository;

    class CharacterRepository{
        public function getCharacterByName($characterName){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT * FROM Characters
            WHERE Name = ?';

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$characterName]);
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
    }
?>