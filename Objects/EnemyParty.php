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
    }
?>