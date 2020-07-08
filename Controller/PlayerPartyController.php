<?php
    namespace Controller;

use Model\Services\PlayerPartyService;

class PlayerPartyController{
        public function addNewPlayerParty(){
            $result = [
                'success' => false
            ];

            $playerPartyName = $_POST['playerPartyName'] ?? '';

            if(!$this->validateName($playerPartyName)){
                $result['msg'] = 'Invalid party name';

                echo json_encode($result, JSON_PRETTY_PRINT);
                return $result;
            }

            $playerPartyService = new PlayerPartyService();
            $result = $playerPartyService->saveNewParty($playerPartyName);

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;

        }

        public function getPlayerPartyByName(){
            $result = [
                'success' => false
            ];

            $playerPartyName = $_POST['playerPartyName'] ?? '';

            if(!$this->validateName($playerPartyName)){
                $result['msg'] = 'Invalid party name';

                echo json_encode($result, JSON_PRETTY_PRINT);
                return $result;
            }

            $playerPartyService = new PlayerPartyService();
            $result = $playerPartyService->getPartyByName($playerPartyName);

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        public function addMemberToPlayerPartyByName(){
            $result = [
                'success' => false
            ];

            $characterName = $_POST['characterName'] ?? '';
            $playerPartyName = $_POST['playerPartyName'] ?? '';

            if(!$this->validateName($characterName)
            || !$this->validateName($playerPartyName)){
                $result['msg'] = 'Invalid input parameters';

                echo json_encode($result, JSON_PRETTY_PRINT);
                return $result;
            }

            $playerPartyService = new PlayerPartyService();
            $result = $playerPartyService->addMemberToPartyByName($characterName, $playerPartyName);

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        public function removeMemberFromPlayerPartyByName(){
            $result = [
                'success' => false
            ];

            $characterName = $_POST['characterName'] ?? '';
            $playerPartyName = $_POST['playerPartyName'] ?? '';

            if(!$this->validateName($characterName)
            || !$this->validateName($playerPartyName)){
                $result['msg'] = 'Invalid input parameters';

                echo json_encode($result, JSON_PRETTY_PRINT);
                return $result;
            }

            $playerPartyService = new PlayerPartyService();
            $result = $playerPartyService->removeMemberFromParty($characterName, $playerPartyName);

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        private function validateName($playerPartyName){
            return !empty($playerPartyName);
        }
    }
?>