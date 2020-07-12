<?php
    namespace Model\Objects;

    use Model\Objects\Character;

    class EnemyParty{
        private $enemyPartyId;
        private $enemyPartyName;
        public $members = array();

        public function __construct($enemyPartyName){
            $this->enemyPartyName = $enemyPartyName;
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
            
            if(in_array($character, $this->members, TRUE)){
                $result['msg'] = 'Character already in party';
                return $result;
            }
            if(count($this->members) >= MAX_PARTY_MEMBERS_COUNT){
                $result['msg'] = $this->playerPartyName . ' is full';
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