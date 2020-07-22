<?php
    use Model\Objects\CombatInfo;
    use Model\Objects\EnemyParty;
    use Model\Objects\PlayerParty;

    $combatInfo = json_decode($_SESSION['combatInfo'], true);
    
    $playerParty = PlayerParty::jsonCreatePlayerParty($combatInfo['playerParty']);
    $enemyParty = EnemyParty::jsonCreateEnemyParty($combatInfo['enemyParty']);
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
        <script>
            var targetId;
            var currentTurn = <?= $currentTurn?>;
            var combatInfo = <?= $combatInfo?>;

            function attackEnemy(){
                var data = new FormData();
                data.append('targetId', targetId);
                data.append('attackerId', '<?= $currentTurn?>');
                data.append('combatInfo', '<?= $combatInfo?>');

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function(){
                    //alert(this.status);
                    if(this.readyState == 4 && this.status == 200) parseResponse(this.responseText);
                }
                xhttp.open("POST", "index.php?target=combat&action=battle", true);
                xhttp.send(data);
            }

            function parseResponse(responseText){
                $("#p-combat-info").text(responseText);
                var responseObj = jQuery.parseJSON(responseText);
            };
        </script>
    </head>
    <body>
        <div class="parties-container">
            <div class="party-container">
                <h2>Player Party</h2>
                <?php
                    echo '<h3>Name: ' . $playerParty->getPlayerPartyName() . '</h3>';
                    echo '<p>Members:</p>';
                    $playerPartyMembers = $playerParty->members;
                    $ppmNum = 0;
                    echo '<div class="members">';
                    foreach($playerPartyMembers as $ppm){
                        $cssClass = '';
                        if($turns[$currentTurn][0] === 'p' && $turns[$currentTurn][1] === $ppmNum){
                            $cssClass = 'character-turn';
                        }
                        else{
                            $cssClass = 'character';
                        }
                        if($ppm->isCharacterDead()){
                            $cssClass = 'character-dead';
                        }
                        echo '<div class="' . $cssClass . '">';
                        echo '<h4 class="character-name">' . $ppm->getCharacterName() . '</h4>';

                        echo '<div class="character-stats">';
                        echo '<p>Health: ' . $ppm->getCharacterHealth() . '</p>';
                        echo '<p>Mana: ' . $ppm->getCharacterMana() . '</p>';
                        echo '<p>AttackDamage: ' . $ppm->getCharacterAttackDamage() . '</p>';
                        echo '</div>';

                        echo '</div>';
                        $ppmNum++;
                    }
                    echo '</div>';
                ?>
            </div>
            
            
            <div class="party-container">
                <h2>Enemy Party</h2>
                <?php
                    echo '<h3>Name: ' . $enemyParty->getEnemyPartyName() . '</h3>';
                    echo '<p>Members:</p>';
                    $enemyPartyMembers = $enemyParty->members;
                    $epmNum = 0;
                    echo '<div class="members">';
                    foreach($enemyPartyMembers as $epm){
                        $cssClass = '';
                        if($turns[$currentTurn+1][1] === $epmNum){
                            $cssClass = 'enemy character-next-turn';
                        }
                        else{
                            $cssClass = 'character enemy';
                        }
                        if($epm->isCharacterDead()){
                            $cssClass = 'character-dead';
                        }
                        echo '<div class="' . $cssClass . '">';
                        echo '<h4 class="character-name">' . $epm->getCharacterName() . '</h2>';

                        echo '<div class="character-stats">';
                        echo '<p>Health: ' . $epm->getCharacterHealth() . '</p>';
                        echo '<p>Mana: ' . $epm->getCharacterMana() . '</p>';
                        echo '<p>AttackDamage: ' . $epm->getCharacterAttackDamage() . '</p>';
                        echo '</div>';

                        ?>
                            <!-- <form action="index.php?target=combat&action=combatBattle" method="POST">
                                <input type="hidden" name="targetId" value="<//?= $epmNum?>">
                                <input type="hidden" name="attackerId" value="<//?= $turns[$currentTurn][1]?>">
                                <textarea maxlength="1500" name="combatInfo" style="display: none;"><//?= $combatInfo ?></textarea>
                                <input class="attack-button" type="submit" value="Attack">
                            </form> -->
                            <button id="<?= $epmNum?>" class="attack-button" onclick="targetId = <?= $epmNum?>; attackEnemy();">Attack</button>
                        <?php

                        echo '</div>';
                        $epmNum++;
                    }
                    echo '</div>';
                ?>
            </div>
        </div>
        <p id="p-combat-info"></p>
    </body>
</html>