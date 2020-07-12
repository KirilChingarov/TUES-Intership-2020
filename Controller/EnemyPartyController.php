<?php
    namespace Controller;

    use Model\Services\EnemyPartyService;

    class EnemyPartyController{
        public function addNewEnemyParty(){
            $result = [
                'success' => false
            ];

            $enemyPartyName = $_POST['enemyPartyName'] ?? '';

            if(!$this->validateName($enemyPartyName)){
                $result['msg'] = 'Invalid party name';

                echo json_encode($result, JSON_PRETTY_PRINT);
                return $result;
            }

            $enemyPartyService = new EnemyPartyService();
            $result = $enemyPartyService->saveNewEnemyParty($enemyPartyName);

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        private function validateName($playerPartyName){
            return !empty($playerPartyName);
        }

        public function getEnemyPartyByName(){
            $result = [
                'success' => false
            ];

            $enemyPartyName = $_POST['enemyPartyName'] ?? '';

            if(!$this->validateName($enemyPartyName)){
                $result['msg'] = 'Invalid party name';

                echo json_encode($result, JSON_PRETTY_PRINT);
                return $result;
            }

            $enemyPartyService = new EnemyPartyService();
            $result = $enemyPartyService->getEnemyPartyByName($enemyPartyName);

            var_dump($result);
            return $result;
        }

        public function addMemberToEnemyPartyByName(){
            $result = [
                'success' => false
            ];

            $characterName = $_POST['characterName'] ?? '';
            $enemyPartyName = $_POST['enemyPartyName'] ?? '';

            if(!$this->validateName($characterName)
            || !$this->validateName($enemyPartyName)){
                $result['msg'] = 'Invalid input parameters';

                echo json_encode($result, JSON_PRETTY_PRINT);
                return $result;
            }

            $EnemyPartyService = new EnemyPartyService();
            $result = $EnemyPartyService->addMemberToPartyByName($characterName, $enemyPartyName);

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        public function removeMemberFromEnemyPartyByName(){
            $result = [
                'success' => false
            ];

            $characterName = $_POST['characterName'] ?? '';
            $enemyPartyName = $_POST['enemyPartyName'] ?? '';

            if(!$this->validateName($characterName)
            || !$this->validateName($enemyPartyName)){
                $result['msg'] = 'Invalid input parameters';

                echo json_encode($result, JSON_PRETTY_PRINT);
                return $result;
            }

            $enemyPartyService = new EnemyPartyService();
            $result = $enemyPartyService->removeMemberFromParty($characterName, $enemyPartyName);

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }
    }
?>