<?php
    namespace Controller;

    use core\View;
    use Model\Services\EnemyPartyService;
    use Model\Services\PlayerPartyService;
    use Model\Objects\CombatInfo;

    define('MAX_PARTY_MEMBERS_COUNT', 4);

    class CombatController{
        public function combatSetUp(){
            $playerPartyService = new PlayerPartyService();
            $enemyPartyService = new EnemyPartyService();

            $playerPartyName = $_POST['playerPartyName'];
            $enemyPartyName = $_POST['enemyPartyName'];

            $playerParty = $playerPartyService->getPartyByName($playerPartyName);
            $enemyParty = $enemyPartyService->getEnemyPartyByName($enemyPartyName);

            if($playerParty['success']){
                $playerParty = $playerParty['party'];
            }
            if($enemyParty['success']){
                $enemyParty = $enemyParty['party'];
            }

            $combatInfo = new CombatInfo($playerParty, $enemyParty, $this->generateTurns(), 0);

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

        public function battle(){
            session_start();

            $targetId = (int)$_POST['targetId'];
            $attackerId = (int)$_POST['attackerId'];
            $combatInfo = json_decode($_POST['combatInfo'], true);

            $playerParty = PlayerPartyService::jsonCreatePlayerParty($combatInfo['playerParty']);
            $enemyParty = EnemyPartyService::jsonCreateEnemyParty($combatInfo['enemyParty']);
            $turns = $combatInfo['turns'];
            $currentTurn = $combatInfo['currentTurn'];

            // player attack
            $characterDamage = $playerParty->members[$attackerId]->getCharacterAttackDamage();
            $enemyParty->members[$targetId]->takeDamage($characterDamage);

            // check if enemy party is dead
            if($this->checkParty($enemyParty)){
                echo "combatWin$";
                View::render('combatWin');
                session_write_close();

                return;
            }

            // enemy attack
            $enemyTurn = $turns[$currentTurn][1];
            if(!$enemyParty->members[$enemyTurn]->isCharacterDead()){
                $enemyDamage = $enemyParty->members[$enemyTurn]->getCharacterAttackDamage();
                $playerParty->members[rand(0, 3)]->takeDamage($enemyDamage);
            }

            // check if player party is dead
            if($this->checkParty($playerParty)){
                echo "combatLose$";
                View::render('combatLose');
                session_write_close();

                return;
            }

            while(TRUE){
                if($currentTurn >= 6){
                    $currentTurn = 0;
                }
                else{
                    $currentTurn += 2;
                }

                $character = $playerParty->members[$turns[$currentTurn][1]];
                
                if(!$character->isCharacterDead()){
                    break;
                }
                else{
                    $enemyTurn = $turns[$currentTurn][1];
                    if(!$enemyParty->members[$enemyTurn]->isCharacterDead()){
                        $enemyDamage = $enemyParty->members[$enemyTurn]->getCharacterAttackDamage();
                        $playerParty->members[rand(0, 3)]->takeDamage($enemyDamage);
                    }
                }
                if($this->checkParty($playerParty)){
                    echo "combatLose$";
                    View::render('combatLose');
                    session_write_close();
    
                    return;
                }
            };

            $combatInfo = new CombatInfo($playerParty, $enemyParty, $turns, $currentTurn);
            
            $_SESSION['combatInfo'] = json_encode($combatInfo);

            echo json_encode($combatInfo);
            session_write_close();

            return;
        }

        private function checkParty($party){
            $deadCharacters = 0;
            for($i = 0;$i < MAX_PARTY_MEMBERS_COUNT;$i++){
                if($party->members[$i]->isCharacterDead()){
                    $deadCharacters++;
                }
            }

            if($deadCharacters >= MAX_PARTY_MEMBERS_COUNT){
                return true;
            }
            return false;
        }
    }
?>