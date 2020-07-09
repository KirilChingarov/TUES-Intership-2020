<?php
    namespace Controller;

use Model\Services\CharacterService;
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
            $playerPartyName = $_POST['playerPartyName'];
            $playerParty = PlayerParty::createPlayerParty($playerPartyName);

            echo json_encode(var_dump($playerParty), JSON_PRETTY_PRINT);
        }

        public function addMemberToPlayerParty(){
            $playerPartyName = $_POST['playerPartyName'];
            $playerParty = PlayerParty::createPlayerParty($playerPartyName);

            $characterService = new CharacterService();

            $characterName = $_POST['characterName'];
            $character = $characterService->getCharacterByName($characterName)['character'];
            $character = Character::createCharacter($character);

            $result = $playerParty->addMemberToParty($character);

            echo json_encode(var_dump($result), JSON_PRETTY_PRINT);
            echo json_encode(var_dump($playerParty->members), JSON_PRETTY_PRINT);
        }

        public function removeMemberFromPlayerParty(){
            $playerPartyName = $_POST['playerPartyName'];
            $playerParty = PlayerParty::createPlayerParty($playerPartyName);

            $characterService = new CharacterService();

            $characterName = $_POST['characterName'];
            $character = $characterService->getCharacterByName($characterName)['character'];
            $character = Character::createCharacter($character);

            $result = $playerParty->removeMemberFromParty($character);

            echo json_encode(var_dump($result), JSON_PRETTY_PRINT);
            echo json_encode(var_dump($playerParty->members), JSON_PRETTY_PRINT);
        }
    }
?>