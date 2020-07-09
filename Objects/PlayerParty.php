<?php
    namespace Objects;

    use Controller\PlayerPartyController;
    use Model\Repository\PlayerPartyRepository;
    use Model\Services\PlayerPartyService;
    use Objects\Character;

    class PlayerParty{
        private $playerPartyId;
        private $playerPartyName;
        private $members = array();

        private function __construct($playerPartyName){
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

        public static function createPlayerParty($playerPartyName){
            $playerPartyRepo = new PlayerPartyRepository();
            $playerPartyService = new PlayerPartyService();

            $newPlayerParty = new PlayerParty($playerPartyName);

            if(!$playerPartyRepo->checkPartyName($playerPartyName)){
                $playerPartyService->saveNewParty($playerPartyName);
            }

            $newPlayerPartyId = $playerPartyService->getPartyByName($playerPartyName)['party']['PlayerPartyId'];
            $newPlayerParty->setPlayerPartyId($newPlayerPartyId);

            return $newPlayerParty;
        }

        public function addMemberToParty(Character $character){
            $playerPartyService = new PlayerPartyService();

            $result = $playerPartyService->addMemberToPartyByName($character->getCharacterName(), $this->playerPartyName);

            if($result['success']){
                array_push($this->members, $character);
            }

            return $result;
        }

        public function removeMemberFromParty(Character $character){
            $playerPartyService = new PlayerPartyService();

            $result = $playerPartyService->removeMemberFromParty($character->getCharacterName(), $this->playerPartyName);

            if($result['success']){
                $characterMemberKey = (int)array_search($character, $this->members);
                $this->members = array_splice($this->members, $characterMemberKey, 1);
            }

            return $result;
        }
    }
?>