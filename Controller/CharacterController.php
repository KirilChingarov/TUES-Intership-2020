<?php
    namespace Controller;

use Model\Services\CharacterService;

class CharacterController{
        public function addCharacter(){
            $result = [
                'success' => false
            ];

            $characterName = $_POST['characterName'] ?? '';
            $characterHealth = $_POST['characterHealth'] ?? 0;
            $characterAttackDamage = $_POST['characterAttackDamage'] ?? 0;
            $characterMana = $_POST['characterMana'] ?? 0;

            if(!$this->validateCharacterName($characterName)
            || !$this->validateCharacterStat($characterHealth)
            || !$this->validateCharacterStat($characterAttackDamage)
            || !$this->validateCharacterStat($characterMana)){
                $result['msg'] = 'Invalid character parameters';

                echo json_encode($result, JSON_PRETTY_PRINT);
                return $result;
            }

            $characterService = new CharacterService();
            $result = $characterService->saveCharacter(
                $characterName, $characterHealth, $characterAttackDamage, $characterMana
            );

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        public function getCharacterByName(){
            $result = [
                'success' => false
            ];

            $characterName = $_POST['characterName'];

            if(!$this->validateCharacterName($characterName)){
                $result['msg'] = 'Invalid character parameters';

                echo json_encode($result, JSON_PRETTY_PRINT);
                return $result;
            }

            $characterService = new CharacterService();
            $result = $characterService->getCharacterByName($characterName);

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        private function validateCharacterName($characterName){
            return !empty($characterName);
        }

        private function validateCharacterStat($characterStat){
            return $characterStat > 0;
        }
    }
?>