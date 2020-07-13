<?php
    $combatInfo = $_SESSION['combatInfo'];

    $playerParty = $combatInfo['playerParty'];
    $enemyParty = $combatInfo['enemyParty'];
    $turns = $combatInfo['turns'];
?>
<html>
    <head>
        <title>Combat Info</title>
    </head>
    <body>
        <h2>Player Party</h2>
        <?php
            //echo '<h2>Player Party</h2><br>';
            echo '<h3>Name: ' . $playerParty->getPlayerPartyName() . '</h3>';
            echo '<p>Members:</p>';
            $playerPartyMembers = $playerParty->members;
            foreach($playerPartyMembers as $ppm){
                echo '<li>' . $ppm->getCharacterName() . '</li>';
            }
        ?>
        <p>-----------------------------</p><br>
        
        <h2>Enemy Party</h2>
        <?php
            //echo '<h2>Player Party</h2><br>';
            echo '<h3>Name: ' . $enemyParty->getEnemyPartyName() . '</h3>';
            echo '<p>Members:</p>';
            $enemyPartyMembers = $enemyParty->members;
            foreach($enemyPartyMembers as $epm){
                echo '<li>' . $epm->getCharacterName() . '</li>';
            }
        ?>
    </body>
</html>