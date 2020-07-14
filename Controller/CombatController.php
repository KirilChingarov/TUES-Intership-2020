<?php
    namespace Controller;

    use core\View;
    use Model\Services\EnemyPartyService;
    use Model\Services\PlayerPartyService;
    use Model\Objects\CombatInfo;
    use Model\Objects\EnemyParty;
    use Model\Objects\PlayerParty;

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

            /*$combatInfo = [
                'playerParty' => $playerParty,
                'enemyParty' => $enemyParty,
                'turns' => $this->generateTurns()
            ];*/
            $combatInfo = new CombatInfo($playerParty, $enemyParty, $this->generateTurns(), 0);
            
            //echo json_encode($combatInfo);

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

            $_SESSION['combatInfo'] = json_encode($combatInfo);

            View::render('combatInfo');

            session_write_close();
        }

        public function combatBattle(){
            session_start();

            $targetId = (int)$_POST['targetId'];
            $attackerId = (int)$_POST['attackerId'];
            $combatInfo = json_decode($_POST['combatInfo'], true);

            //print_r($combatInfo);

            $playerParty = PlayerParty::jsonCreatePlayerParty($combatInfo['playerParty']);
            $enemyParty = EnemyParty::jsonCreateEnemyParty($combatInfo['enemyParty']);
            $turns = $combatInfo['turns'];
            $currentTurn = $combatInfo['currentTurn'];

            //var_dump($playerParty);

            $characterDamage = $playerParty->members[$attackerId]->getCharacterAttackDamage();
            $enemyParty->members[$targetId]->takeDamage($characterDamage);
            //$currentTurn += 2;

            $combatInfo = new CombatInfo($playerParty, $enemyParty, $turns, $currentTurn);
            
            $_SESSION['combatInfo'] = json_encode($combatInfo);

            View::render('combatInfo');

            session_write_close();
        }
    }
?>