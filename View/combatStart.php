<html>
    <head>
        <title>Combat start</title>
        <link rel="stylesheet" href="View/css/combatStart.css" type="text/css">
    </head>
    <body>
        <h1>RPG Combat Demo</h1>
        <h2>Input the two parties for the combat demo</h2>
        <form class="form" action="index.php?target=combat&action=combat" method="POST">
            <label>Player party name</label>
            <input type="text" name="playerPartyName" required>
            <br><br>
            <label>Enemy party name</label>
            <input type="text" name="enemyPartyName" required>
            <br><br>
            <button type="submit">Start battle</button>
        </form>
    </body>
</html>