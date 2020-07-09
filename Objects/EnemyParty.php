<?php
    namespace Objects;

    use Model\Repository\EnemyPartyRepository;
use Model\Services\CharacterService;
use Model\Services\EnemyPartyService;

    class EnemyParty{
        private $enemyPartyId;
        private $enemyPartyName;
        public $members = array();

        private function __construct($enemyPartyName){
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

        public static function createEnemyParty($enemyPartyName){
            $enemyPartyRepo = new EnemyPartyRepository();
            $enemyPartyService = new EnemyPartyService();
            $characterService = new CharacterService();

            $newEnemyParty = new EnemyParty($enemyPartyName);

            if($enemyPartyRepo->checkEnemyPartyName($enemyPartyName)){
                $enemyPartyMembers = $enemyPartyService->getEnemyPartyMembers($enemyPartyName);
                foreach($enemyPartyMembers as $epm){
                    $character = $characterService->getCharacterById($epm['characterId']);

                    $tmpCharacter = Character::createCharacter($character['character']);

                    array_push($newEnemyParty->members, $tmpCharacter);
                }
            }
            else{
                $enemyPartyService->saveNewEnemyParty($enemyPartyName);
            }

            $newEnemyPartyId = (int)$enemyPartyService->getEnemyPartyByName($enemyPartyName)['party']['enemyPartyId'];
            $newEnemyParty->setEnemyPartyId($newEnemyPartyId);

            return $newEnemyParty;
        }

        public function addMemberToEnemyParty(Character $character){
            $enemyPartyService = new EnemyPartyService();

            $result = $enemyPartyService->addMemberToPartyByName($character->getCharacterName(), $this->enemyPartyName);

            if($result['success']){
                array_push($this->members, $character);
            }

            return $result;
        }

        public function removeMemberFromEnemyParty(Character $character){
            $enemyPartyService = new EnemyPartyService();

            $result = $enemyPartyService->removeMemberFromParty($character->getCharacterName(), $this->enemyPartyName);

            if($result['success']){
                $characterMemberKey = (int)array_search($character, $this->members);
                array_splice($this->members, $characterMemberKey, 1);
            }

            return $result;
        }
    }
?>