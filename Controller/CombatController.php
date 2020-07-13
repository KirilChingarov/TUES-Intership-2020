<?php
    namespace Controller;

use core\View;
use Model\Services\EnemyPartyService;
    use Model\Services\PlayerPartyService;

    class CombatController{
        public function combatSetUp(){
            $playerPartyService = new PlayerPartyService();
            $enemyPartyService = new EnemyPartyService();

            $playerPartyName = $_POST['playerPartyName'];
            $enemyPartyName = $_POST['enemyPartyName'];

            $playerParty = $playerPartyService->getPartyByName($playerPartyName);
            $enemyParty = $enemyPartyService->getEnemyPartyByName($enemyPartyName);

            if($playerParty['success']) $playerParty = $playerParty['party'];
            if($enemyParty['success']) $enemyParty = $enemyParty['party'];

            $combatInfo = [
                'playerParty' => $playerParty,
                'enemyParty' => $enemyParty,
                'turns' => $this->generateTurns()
            ];

            return $combatInfo;
        }

        public function generateTurns(){
            $turns = [];

            for($i = 0;$i < MAX_PARTY_MEMBERS_COUNT;$i++){
                // get playerParty member
                $turns[] = ['p', $i];

                // get enemyParty member
                $turns[] = ['e', $i];
            }

            return $turns;
        }

        public function combat(){
            session_start();

            $combatInfo = $this->combatSetUp();
            //var_dump($combatInfo);

            $_SESSION['combatInfo'] = $combatInfo;

            View::render('combatInfo');
        }
    }
?>