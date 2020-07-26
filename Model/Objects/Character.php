<?php
    namespace Model\Objects;

    use JsonSerializable;
    use Model\Services\CharacterService;

    class Character implements JsonSerializable{
        private $characterId;
        private $characterName;
        private $characterHealth;
        private $characterAttackDamage;
        private $characterMana;
        private bool $isDead = false;

        public function __construct($characterName, $characterHealth, $characterAttackDamage, $characterMana){
            $this->characterName = $characterName;
            $this->characterHealth = $characterHealth;
            $this->characterAttackDamage = $characterAttackDamage;
            $this->characterMana = $characterMana;
            if($this->characterHealth > 0) $this->isDead = false;
            else $this->isDead = true;
        }

        public function jsonSerialize(){
            $character['characterId'] = $this->characterId;
            $character['characterName'] = $this->characterName;
            $character['characterHealth'] = $this->characterHealth;
            $character['characterAttackDamage'] = $this->characterAttackDamage;
            $character['characterMana'] = $this->characterMana;
            $character['characterIsDead'] = $this->isDead;

            return $character;
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
            $newCharacter->setCharacterId($character['characterId']);

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