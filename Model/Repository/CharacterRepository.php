<?php
    namespace Model\Repository;

    class CharacterRepository{
        public function getCharacterByName($characterName){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT * FROM Characters
            WHERE Name = ?';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($characterName));
            return $stmt->fetch();
        }

        public function saveCharacter($character){
            $pdo = DBManager::getInstance()->getConnection();

            if(self::checkCharacterName($character['name'])){
                return "Character already saved";
            }

            $sql = 'INSERT INTO Characters(Name, Health, AttackDamage, Mana)
            VALUES (:name, :health, :attackDamage, :mana)';

            $stmt = $pdo->prepare($sql);
            return $stmt->execute($character);
        }

        private static function checkCharacterName($characterName){
            $pdo = DBManager::getInstance()->getConnection();

            $sql = 'SELECT * FROM Characters
            WHERE Name = ?';

            $stmt = $pdo->prepare($sql);
            return $stmt->execute(array($characterName));
        }
    }
?>