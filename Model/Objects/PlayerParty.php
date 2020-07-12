<?php
    namespace Model\Objects;

    use Model\Objects\Character;

    class PlayerParty{
        private $playerPartyId;
        private $playerPartyName;
        public $members = array();

        public function __construct($playerPartyName){
            $this->playerPartyName = $playerPartyName;
        }

        public function getPlayerPartyId(){
            return $this->playerPartyId;
        }

        public function setPlayerPartyId($playerPartyId){
            $this->playerPartyId = $playerPartyId;
        }
        
        public function getPlayerPartyName(){
            return $this->playerPartyName;
        }

        public function setPlayerPartyName($playerPartyName){
            $this->playerPartyName = $playerPartyName;
        }

        public static function createPlayerParty($playerPartyName, $playerPartyId){
            $newPlayerParty = new PlayerParty($playerPartyName, $playerPartyId);

            return $newPlayerParty;
        }

        public function addMemberToParty(Character $character){
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
            $result['msg'] = 'Character has been add to ' . $this->playerPartyName;
            return $result;
        }

        public function removeMemberFromParty(Character $character){
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