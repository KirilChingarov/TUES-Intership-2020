<?php
    namespace Controller;

    use core\View;
    use Model\Services\EnemyPartyService;
    use Model\Services\PlayerPartyService;
    use Model\Objects\CombatInfo;

    define('MAX_PARTY_MEMBERS_COUNT', 4);
    define('LAST_PLAYER_TURN', 6);
    define('NEXT_PLAYER_TURN', 2);
    define('LAST_ENEMY_TURN', 7);
    define('CHARACTER_TURN', 1);
    define('TARGET_NOT_FOUND', -1);
    define('NEW_ROUND', 0);
    

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
                View::render('combatLose');
                session_write_close();

                return;
            }

            while(TRUE){
                if($currentTurn >= LAST_PLAYER_TURN){
                    $currentTurn = NEW_ROUND;
                }
                else{
                    $currentTurn += NEXT_PLAYER_TURN;
                }

                $character = $playerParty->members[$turns[$currentTurn][CHARACTER_TURN]];
                
                if(!$character->isCharacterDead()){
                    break;
                }
                else{
                    $enemyTurn = $turns[$currentTurn][CHARACTER_TURN];
                    if(!$enemyParty->members[$enemyTurn]->isCharacterDead()){
                        $enemyDamage = $enemyParty->members[$enemyTurn]->getCharacterAttackDamage();
                        $playerParty->members[rand(0, 3)]->takeDamage($enemyDamage);
                    }
                }
                if($this->checkParty($playerParty)){
                    View::render('combatLose');
    
                    return;
                }
            };

            $combatInfo = new CombatInfo($playerParty, $enemyParty, $turns, $currentTurn);

            echo json_encode($combatInfo);
        }

        public function attackEnemy(){
            $targetId = (int)$_POST['targetId'];
            $attackerId = (int)$_POST['attackerId'];
            $combatInfo = json_decode($_POST['combatInfo'], true);

            $playerParty = PlayerPartyService::jsonCreatePlayerParty($combatInfo['playerParty']);
            $enemyParty = EnemyPartyService::jsonCreateEnemyParty($combatInfo['enemyParty']);
            $currentTurn = $combatInfo['currentTurn'];

            // player attack
            $characterDamage = $playerParty->members[$attackerId]->getCharacterAttackDamage();
            $enemyParty->members[$targetId]->takeDamage($characterDamage);
            $currentTurn++;

            // check if enemy party is dead
            if($this->checkParty($enemyParty)){
                View::render('combatWin');

                return;
            }

            $combatInfo = new CombatInfo($playerParty, $enemyParty, $combatInfo['turns'], $currentTurn);

            echo json_encode($combatInfo);
        }

        public function getEnemyTargetId(){
            $combatInfo = json_decode($_POST['combatInfo'], true);
            
            $playerParty = PlayerPartyService::jsonCreatePlayerParty($combatInfo['playerParty']);

            $targetId = 0;

            while(TRUE){
                $targetId = rand(0, 3);
                if(!$playerParty->members[$targetId]->isCharacterDead()){
                    // wait 1 sec for "enemy" decision phase
                    sleep(1);
                    echo $targetId;
                    return;
                }
                if($this->checkParty($playerParty)){
                    break;
                }
            }

            // wait 1 sec for "enemy" decision phase
            sleep(1);
            echo TARGET_NOT_FOUND;
        }

        public function attackPlayer(){
            $targetId = (int)$_POST['enemyTargetId'];
            $attackerId = (int)$_POST['enemyAttackerId'];
            $combatInfo = json_decode($_POST['combatInfo'], true);
            
            $playerParty = PlayerPartyService::jsonCreatePlayerParty($combatInfo['playerParty']);
            $enemyParty = EnemyPartyService::jsonCreateEnemyParty($combatInfo['enemyParty']);
            $currentTurn = $combatInfo['currentTurn'];
            $turns = $combatInfo['turns'];

            // enemy attack
            if(!$enemyParty->members[$attackerId]->isCharacterDead()){
                $enemyDamage = $enemyParty->members[$attackerId]->getCharacterAttackDamage();
                $playerParty->members[$targetId]->takeDamage($enemyDamage);
            }
            $currentTurn++;

            if($currentTurn >= LAST_ENEMY_TURN){
                $currentTurn = NEW_ROUND;
            }

            // check if player party is dead
            if($this->checkParty($playerParty)){
                View::render('combatLose');

                return;
            }

            $combatInfo = new CombatInfo($playerParty, $enemyParty, $turns, $currentTurn);

            echo json_encode($combatInfo);
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