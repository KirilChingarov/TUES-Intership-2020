<?php
    $combatInfo = $_SESSION['combatInfo'];

    $playerParty = $combatInfo['playerParty'];
    $enemyParty = $combatInfo['enemyParty'];
    $turns = $combatInfo['turns'];
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
                    foreach($playerPartyMembers as $ppm){
                        echo '<div class="character">';
                        echo '<h4 class="character-name">' . $ppm->getCharacterName() . '</h2>';

                        echo '<div class="character-stats">';
                        echo '<p>Health: ' . $ppm->getCharacterHealth() . '</p>';
                        echo '<p>Mana: ' . $ppm->getCharacterMana() . '</p>';
                        echo '</div>';

                        echo '</div>';
                    }
                ?>
            </div>
            
            
            <div class="party-container">
                <h2>Enemy Party</h2>
                <?php
                    echo '<h3>Name: ' . $enemyParty->getEnemyPartyName() . '</h3>';
                    echo '<p>Members:</p>';
                    $enemyPartyMembers = $enemyParty->members;
                    foreach($enemyPartyMembers as $epm){
                        echo '<div class="character">';
                        echo '<h4 class="character-name">' . $epm->getCharacterName() . '</h2>';

                        echo '<div class="character-stats">';
                        echo '<p>Health: ' . $epm->getCharacterHealth() . '</p>';
                        echo '<p>Mana: ' . $epm->getCharacterMana() . '</p>';
                        echo '</div>';

                        echo '</div>';
                    }
                ?>
            </div>
        </div>
    </body>
</html>