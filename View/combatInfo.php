<?php
    use Model\Objects\CombatInfo;
    use Model\Services\EnemyPartyService;
    use Model\Services\PlayerPartyService;

    $combatInfo = json_decode($_SESSION['combatInfo'], true);
    
    $playerParty = PlayerPartyService::jsonCreatePlayerParty($combatInfo['playerParty']);
    $enemyParty = EnemyPartyService::jsonCreateEnemyParty($combatInfo['enemyParty']);
    $turns = $combatInfo['turns'];
    $currentTurn = $combatInfo['currentTurn'];

    $combatInfo = new CombatInfo($playerParty, $enemyParty, $turns, $currentTurn);
    $combatInfo = json_encode($combatInfo);

?>
<html>
    <head>
        <title>Combat Info</title>
        <link rel="stylesheet" href="View/css/combatInfo.css" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script>
            var targetId;
            var currentTurn = <?= $currentTurn?>;
            var combatInfo = <?= $combatInfo?>;
            var attackerId = combatInfo.turns[currentTurn][1];
            var turns = combatInfo.turns;
            var enemyTargetId;
            const LAST_PLAYER_TURN = 6;
            const LAST_ENEMY_TURN = 7;
            const NEW_ROUND = 0;

            function attackEnemy(){
                var data = new FormData();
                data.append('targetId', targetId);
                data.append('attackerId', attackerId);
                data.append('combatInfo', JSON.stringify(combatInfo));

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        takeDamage(targetId);
                        if(parseResponse(this.responseText)){
                            enemyAttack();
                        }
                    }
                }
                xhttp.open("POST", "index.php?target=combat&action=attackEnemy", true);
                xhttp.send(data);
            }

            function enemyAttack(){
                var data = new FormData();
                data.append('combatInfo', JSON.stringify(combatInfo));

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        enemyTargetId = this.responseText;
                        attackPlayer();
                    }
                }
                xhttp.open("POST", "index.php?target=combat&action=getEnemyTargetId", true);
                xhttp.send(data);
            }

            function attackPlayer(){
                var data = new FormData();
                data.append('enemyTargetId', enemyTargetId);
                data.append('enemyAttackerId', attackerId);
                data.append('combatInfo', JSON.stringify(combatInfo));

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        parseResponse(this.responseText);
                        checkNextTurn();
                    }
                }
                xhttp.open("POST", "index.php?target=combat&action=attackPlayer");
                xhttp.send(data);
            }

            
            function checkNextTurn(){
                playerPartyMembers = combatInfo.playerParty.members;

                if(playerPartyMembers[turns[currentTurn][1]].characterIsDead){
                    if(currentTurn > LAST_PLAYER_TURN){
                        currentTurn = NEW_ROUND;
                    }
                    else{
                        currentTurn++;
                    }
                    attackerId = turns[turns[currentTurn][1]];

                    enemyAttack();
                }
            }

            function parseResponse(responseText){
                if(!checkEndBattle(responseText)){
                    combatInfo = jQuery.parseJSON(responseText);
                    turns = combatInfo.turns;
                    currentTurn = combatInfo.currentTurn;
                    attackerId = combatInfo.turns[currentTurn][1];

                    updatePlayerPartyMembers(combatInfo.playerParty.members);
                    updateEnemyPartyMembers(combatInfo.enemyParty.members);

                    return true;
                }
                else{
                    return false;
                }
            };

            function checkEndBattle(responseText){
                try {
                    jQuery.parseJSON(responseText);
                    return false;
                } catch (error) {
                    document.write(responseText);
                    return true;
                }
            }

            function updatePlayerPartyMembers(playerPartyMembers){

                for(var i = 0;i < 4;i++){
                    var playerStats = "#player-" + i;
                    if(playerPartyMembers[i].characterIsDead){
                        $(playerStats).parent().attr('class', 'character-dead');
                    }
                    else{
                        if(turns[currentTurn][0] == 'p' && turns[currentTurn][1] == i){
                            $(playerStats).parent().attr('class', 'character-turn');
                        }
                        else if(currentTurn < LAST_PLAYER_TURN && turns[currentTurn + 1][0] == 'p' && turns[currentTurn + 1][1] == i){
                            $(playerStats).parent().attr('class', 'character-next-turn');
                        }
                        else{
                            $(playerStats).parent().attr('class', 'character');
                        }
                        $(playerStats + ">.health").text("Health: " + playerPartyMembers[i].characterHealth);
                    }
                }
            }

            function updateEnemyPartyMembers(enemyPartyMembers){
                
                for(var i = 0;i < 4;i++){
                    var enemyStats = "#enemy-" + i;
                    if(enemyPartyMembers[i].characterIsDead){
                        //$(enemyStats).parent().attr('class', 'character-dead');
                        $(enemyStats).parent().effect("explode", {pieces: 49}, 400, callback(enemyStats));
                        //$(enemyStats).parent().hide();
                    }
                    else{
                        if(turns[currentTurn][0] == 'e' && turns[currentTurn][1] == i){
                            $(enemyStats).parent().attr('class', 'character-turn');
                        }
                        else if(currentTurn < LAST_ENEMY_TURN && turns[currentTurn + 1][0] == 'e' && turns[currentTurn + 1][1] == i){
                            $(enemyStats).parent().attr('class', 'character-next-turn');
                        }
                        else{
                            $(enemyStats).parent().attr('class', 'character');
                        }
                        $(enemyStats + ">.health").text("Health: " + enemyPartyMembers[i].characterHealth);
                    }
                }
            }

            function takeDamage(buttonId){
                $("#" + buttonId).parent().effect("pulsate");
            }

            function callback(target){
                setTimeout(function(){
                    $(target).removeAttr("style").hide();
                }, 1000);
            }
        </script>
    </head>
    <body>
        <div class="parties-container">
            <!-- Player Party ########################################################################################## -->
            <div class="party-container">
                <h2>Player Party</h2>
                <h3>Name: <?=$playerParty->getPlayerPartyName()?></h3>
                <p>Members:</p>
                <?php
                    $playerPartyMembers = $playerParty->members;
                    $ppmNum = 0;
                ?>
                <div class="members">
                    <?php
                        foreach($playerPartyMembers as $ppm){
                            $cssClass;
                            if($turns[$currentTurn][0] === 'p' && $turns[$currentTurn][1] === $ppmNum){
                                $cssClass = 'character-turn';
                            }
                            else{
                                $cssClass = 'character';
                            }
                            if($ppm->isCharacterDead()){
                                $cssClass = 'character-dead';
                            }
                            ?>
                            <div class="<?= $cssClass?>">
                                <h4 class="character-name"><?= $ppm->getCharacterName()?></h4>
                                <div id="player-<?= $ppmNum?>" class="character-stats">
                                    <p class="health">Health: <?= $ppm->getCharacterHealth()?></p>
                                    <p class="mana">Mana: <?= $ppm->getCharacterMana()?></p>
                                    <p class="attack-damage">ATK: <?= $ppm->getCharacterAttackDamage()?></p>
                                </div>
                            </div>
                            <?php
                            $ppmNum++;
                        }
                    ?>
                </div>
            </div>
            <!-- Enemy Party ########################################################################################## -->
            <div class="party-container">
                <h2>Enemy Party</h2>
                <h3>Name: <?= $enemyParty->getEnemyPartyName()?></h3>
                <p>Members:</p>
                <?php
                    $enemyPartyMembers = $enemyParty->members;
                    $epmNum = 0;
                ?>
                <div class="members">
                    <?php
                        foreach($enemyPartyMembers as $epm){
                            $cssClass;
                            if($turns[$currentTurn+1][1] === $epmNum){
                                $cssClass = 'character-next-turn';
                            }
                            else{
                                $cssClass = 'character';
                            }
                            if($epm->isCharacterDead()){
                                $cssClass = 'character-dead';
                            }
                            ?>
                            <div class="<?= $cssClass?>">
                                <h4 class="character-name"><?= $epm->getCharacterName()?></h4>
                                <div id="enemy-<?= $epmNum?>" class="character-stats">
                                    <p class="health">Health: <?= $epm->getCharacterHealth()?></p>
                                    <p class="mana">Mana: <?= $epm->getCharacterMana()?></p>
                                    <p class="attack-damage">ATK: <?= $epm->getCharacterAttackDamage()?></p>
                                </div>
                                <button id="<?= $epmNum?>" class="attack-button" onclick="targetId = <?= $epmNum?>; attackEnemy();">Attack</button>
                            </div>
                            <?php
                            $epmNum++;
                        }
                    ?>
                </div>
            </div>
        </div>
        <p id="p-combat-info"></p>
    </body>
</html>