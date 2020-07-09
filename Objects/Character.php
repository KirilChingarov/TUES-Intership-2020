<?php
    namespace Objects;

use Model\Services\CharacterService;

class Character{
        private $characterId;
        private $characterName;
        private $characterHealth;
        private $characterAttackDamage;
        private $characterMana;
        private bool $isDead = false;

        private function __construct($characterName, $characterHealth, $characterAttackDamage, $characterMana){
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
            $newCharacter = new Character($character['characterName'], $character['characterHealth'], 
            $character['characterAttackDamage'], $character['characterMana']);

            $characterService = new CharacterService();

            $result = $characterService->getCharacterByName($character['characterName']);

            if($result['success'] === true){
                $newCharacter->setCharacterId($result['character']['characterId']);
                $newCharacter->setCharacterHealth($result['character']['characterHealth']);
                $newCharacter->setCharacterAttackDamage($result['character']['characterAttackDamage']);
                $newCharacter->setCharacterMana($result['character']['characterMana']);
            }
            else{
                $characterService->saveCharacter($character['characterName'], $character['characterHealth'], 
                $character['characterAttackDamage'], $character['characterMana']);

                $result = $characterService->getCharacterByName($character['characterName']);

                $newCharacter->setCharacterId($result['character']['characterId']);
            }

            return $newCharacter;
        }

        public function takeDamage(INT $damage){
            $this->characterHealth -= $damage;

            if($this->characterHealth <= 0){
                $this->isDead = true;
            }
        }

        public function isCharacterDead(){
            return $this->isDead;
        }

        public function updateCharacterInfo(){
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