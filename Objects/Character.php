<?php
    namespace Objects;

use Model\Services\CharacterService;

class Character{
        private $characterId;
        private $characterName;
        private $characterHealth;
        private $characterAttackDamage;
        private $characterMana;

        private function __construct($characterId, $characterName, $characterHealth, $characterAttackDamage, $characterMana){
            $this->characterId = $characterId;
            $this->characterName = $characterName;
            $this->characterHealth = $characterHealth;
            $this->characterAttackDamage = $characterAttackDamage;
            $this->characterMana = $characterMana;
        }

        public function getCharacterName(){
            return $this->characterName;
        }

        public function setCharacterName($characterName){
            $this->characterName = $characterName;
        }
        
        public function getCharacterHealth(){
            return $this->characterHealth;
        }

        public function setCharacterHealth($characterHealth){
            $this->characterHealth = $characterHealth;
        }
        
        public function getCharacterAttackDamage(){
            return $this->characterAttackDamage;
        }

        public function setCharacterAttackDamage($characterAttackDamage){
            $this->characterAttackDamage = $characterAttackDamage;
        }
        
        public function getCharacterMana(){
            return $this->characterMana;
        }

        public function setCharacterMana($characterMana){
            $this->characterMana = $characterMana;
        }

        public function getCharacterId(){
            return $this->characterId;
        }

        public function setCharacterId($characterId){
            $this->characterId = $characterId;
        }

        public static function createCharacter($character){
            $newCharacter = new Character($character['characterId'], $character['characterName'], 
                                        $character['characterHealth'], $character['characterAttackDamage'], 
                                        $character['characterMana']);

            return $newCharacter;
        }

        public function takeDamage(INT $damage){
            $this->characterHealth -= $damage;

            $characterService = new CharacterService();

            $character = [
                'characterId' => $this->characterId,
                'characterName' => $this->characterName,
                'characterHealth' => $this->characterHealth,
                'characterAttackDamage' => $this->characterAttackDamage,
                'characterMana' => $this->characterMana
            ];

            $result = $characterService->updateCharacter($character);

            return $result;
        }
    }
?>