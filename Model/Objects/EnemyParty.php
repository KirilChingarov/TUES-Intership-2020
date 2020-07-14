<?php
    namespace Model\Objects;

    use JsonSerializable;
    use Model\Objects\Character;

    class EnemyParty implements JsonSerializable{
        private $enemyPartyId;
        private $enemyPartyName;
        public $members = array();
        const MAX_PARTY_MEMBERS_COUNT = 4;

        public function __construct($enemyPartyName, $enemyPartyId){
            $this->enemyPartyName = $enemyPartyName;
            $this->enemyPartyId = $enemyPartyId;
        }

        public function jsonSerialize(){
            $enemyParty['enemyPartyId'] = $this->enemyPartyId;
            $enemyParty['enemyPartyName'] = $this->enemyPartyName;
            $enemyParty['members'] = $this->members;

            return $enemyParty;
        }

        public static function jsonCreateEnemyParty($json_decoded){
            $enemyPartyId = $json_decoded['enemyPartyId'];
            $enemyPartyName = $json_decoded['enemyPartyName'];

            $enemyParty = new EnemyParty($enemyPartyName, $enemyPartyId);
            
            foreach($json_decoded['members'] as $member){
                $character = Character::jsonCreateCharacter($member);

                $enemyParty->addMemberToEnemyParty($character);
            }

            return $enemyParty;
        }

        public function getEnemyPartyId(){
            return $this->enemyPartyId;
        }

        public function setEnemyPartyId($enemyPartyId){
            $this->enemyPartyId = $enemyPartyId;
        }
        
        public function getEnemyPartyName(){
            return $this->enemyPartyName;
        }

        public function setEnemyPartyName($enemyPartyName){
            $this->enemyPartyName = $enemyPartyName;
        }

        public static function createEnemyParty($enemyPartyName, $enemyPartyId){
            $newEnemyParty = new EnemyParty($enemyPartyName, $enemyPartyId);

            return $newEnemyParty;
        }

        public function addMemberToEnemyParty(Character $character){
            $result = [
                'success' => false
            ];
            
            if(count($this->members) >= self::MAX_PARTY_MEMBERS_COUNT){
                $result['msg'] = $this->enemyPartyName . ' is full';
                return $result;
            }

            $this->members[] = $character;

            $result['success'] = true;
            $result['msg'] = 'Character has been add to ' . $this->enemyPartyName;
            return $result;
        }

        public function removeMemberFromEnemyParty(Character $character){
            $result = [
                'success' => false
            ];

            $characterMemberKey = (int)array_search($character, $this->members);

            if($characterMemberKey !== false){
                array_splice($this->members, $characterMemberKey, 1);

                $result['success'] = true;
                $result['msg'] = $character->getCharacterName() . ' has been removed from ' . $this->playerPartyName;
            }
            else{
                $result['msg'] = $character->getCharacterName() . ' has not been found in ' . $this->playerPartyName;
            }

            return $result;
        }
    }
?>