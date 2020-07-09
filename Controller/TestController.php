<?php
    namespace Controller;

use Model\Services\PlayerPartyService;
use Objects\Character;
use Objects\PlayerParty;

class TestController{
        public function createCharacter(){
            $character = [
                'characterName' => 'Goemon',
                'characterHealth' => 150,
                'characterAttackDamage' => 20,
                'characterMana' => 30
            ];

            $testCharacter = Character::createCharacter($character);

            echo json_encode(var_dump($testCharacter), JSON_PRETTY_PRINT);
        }

        public function characterTakeDamage(){
            $character = [
                'characterName' => 'Goemon',
                'characterHealth' => 150,
                'characterAttackDamage' => 20,
                'characterMana' => 30
            ];

            $testCharacter = Character::createCharacter($character);

            echo json_encode(var_dump($testCharacter), JSON_PRETTY_PRINT);

            $damage = $_POST['damage'];

            $testCharacter->takeDamage($damage);

            echo json_encode(var_dump($testCharacter), JSON_PRETTY_PRINT);
        }

        public function createPlayerParty(){
            $playerPartyService = new PlayerPartyService();

            $playerPartyName = $_POST['playerPartyName'];

            $playerPartyService->getPlayerPartyMembers($playerPartyName);

            $playerParty = PlayerParty::createPlayerParty($playerPartyName);

            echo json_encode(var_dump($playerParty), JSON_PRETTY_PRINT);
        }
    }
?>