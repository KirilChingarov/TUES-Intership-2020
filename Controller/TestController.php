<?php
    namespace Controller;

    use Model\Services\CharacterService;
    use Model\Objects\Character;
    use Model\Objects\PlayerParty;
    use Model\Objects\EnemyParty;
    use Model\Services\PlayerPartyService;
    use Model\Services\EnemyPartyService;

class TestController{
        public function createCharacter(){
            $character = [
                'characterName' => 'Goemon',
                'characterHealth' => 150,
                'characterAttackDamage' => 20,
                'characterMana' => 30
            ];

            $testCharacter = Character::createCharacter($character);

            var_dump($testCharacter);
        }

        public function characterTakeDamage(){
            $character = [
                'characterName' => 'Goemon',
                'characterHealth' => 150,
                'characterAttackDamage' => 20,
                'characterMana' => 30
            ];

            $testCharacter = Character::createCharacter($character);

            var_dump($testCharacter);

            $damage = $_POST['damage'];

            $testCharacter->takeDamage($damage);

            var_dump($testCharacter);
        }

        public function createPlayerParty(){
            $playerPartyName = $_POST['playerPartyName'];

            $playerPartyService = new PlayerPartyService();
            $playerParty = $playerPartyService->getPartyByName($playerPartyName)['party'];

            var_dump($playerParty);
        }

        public function addMemberToPlayerParty(){
            $playerPartyName = $_POST['playerPartyName'];
            
            $playerPartyService = new PlayerPartyService();
            $playerParty = $playerPartyService->getPartyByName($playerPartyName)['party'];

            $characterService = new CharacterService();

            $characterName = $_POST['characterName'];
            $character = $characterService->getCharacterByName($characterName)['character'];

            $result = $playerParty->addMemberToParty($character);

            var_dump($result);
            var_dump($playerParty->members);
        }

        public function removeMemberFromPlayerParty(){
            $playerPartyName = $_POST['playerPartyName'];

            $playerPartyService = new PlayerPartyService();
            $playerParty = $playerPartyService->getPartyByName($playerPartyName)['party'];

            $characterService = new CharacterService();

            $characterName = $_POST['characterName'];
            $character = $characterService->getCharacterByName($characterName)['character'];

            $result = $playerParty->removeMemberFromParty($character);

            var_dump($result);
            var_dump($playerParty->members);
        }

        public function createEnemyParty(){
            $enemyPartyName = $_POST['enemyPartyName'];
            
            $enemyPartyService = new EnemyPartyService();
            $enemyParty = $enemyPartyService->getEnemyPartyByName($enemyPartyName)['party'];

            var_dump($enemyParty);
        }

        public function addMemberToEnemyParty(){
            $enemyPartyName = $_POST['enemyPartyName'];
            
            $enemyPartyService = new EnemyPartyService();
            $enemyParty = $enemyPartyService->getEnemyPartyByName($enemyPartyName)['party'];

            $characterService = new CharacterService();

            $characterName = $_POST['characterName'];
            $character = $characterService->getCharacterByName($characterName)['character'];

            $result = $enemyParty->addMemberToEnemyParty($character);

            var_dump($result);
            var_dump($enemyParty->members);
        }

        public function removeMemberFromEnemyParty(){
            $enemyPartyName = $_POST['enemyPartyName'];
            
            $enemyPartyService = new EnemyPartyService();
            $enemyParty = $enemyPartyService->getEnemyPartyByName($enemyPartyName)['party'];

            $characterService = new CharacterService();

            $characterName = $_POST['characterName'];
            $character = $characterService->getCharacterByName($characterName)['character'];

            $result = $enemyParty->removeMemberFromEnemyParty($character);

            var_dump($result);
            var_dump($enemyParty->members);
        }
    }
?>