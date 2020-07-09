<?php
    namespace Objects;

    use Controller\PlayerPartyController;
    use Model\Repository\PlayerPartyRepository;
use Model\Services\CharacterService;
use Model\Services\PlayerPartyService;
    use Objects\Character;

    class PlayerParty{
        private $playerPartyId;
        private $playerPartyName;
        public $members = array();

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

            $characterService = new CharacterService();

            if($playerPartyRepo->checkPartyName($playerPartyName)){
                $playerPartyMembers = $playerPartyService->getPlayerPartyMembers($playerPartyName);
                foreach($playerPartyMembers as $ppm){
                    $character = $characterService->getCharacterById($ppm['characterId']);

                    $tmpCharacter = Character::createCharacter($character['character']);

                    array_push($newPlayerParty->members, $tmpCharacter);
                }
            }
            else{
                $playerPartyService->saveNewParty($playerPartyName);
            }

            $newPlayerPartyId = (int)$playerPartyService->getPartyByName($playerPartyName)['party']['playerPartyId'];
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
                array_splice($this->members, $characterMemberKey, 1);
            }

            return $result;
        }
    }
?>