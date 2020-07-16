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
                ?>
            </div>
            
            
            <div class="party-container">
                <h2>Enemy Party</h2>
                <?php
                    echo '<h3>Name: ' . $enemyParty->getEnemyPartyName() . '</h3>';
                    echo '<p>Members:</p>';
                    $enemyPartyMembers = $enemyParty->members;
                    $epmNum = 0;
                    foreach($enemyPartyMembers as $epm){
                        $cssClass = '';
                        if($turns[$currentTurn+1][1] === $epmNum){
                            $cssClass = 'character-next-turn';
                        }
                        else{
                            $cssClass = 'character';
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
                            <form action="index.php?target=combat&action=combatBattle" method="POST">
                                <input type="hidden" name="targetId" value="<?= $epmNum?>">
                                <input type="hidden" name="attackerId" value="<?= $turns[$currentTurn][1]?>">
                                <textarea maxlength="1500" name="combatInfo" style="display: none;"><?= $combatInfo ?></textarea>
                                <input type="submit" value="Attack">
                            </form>
                        <?php

                        echo '</div>';
                        $epmNum++;
                    }
                ?>
            </div>
        </div>
    </body>
</html>