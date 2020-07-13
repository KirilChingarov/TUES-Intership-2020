<html>
    <head>
        <title>Combat start</title>
    </head>
    <body>
        <h2>Input the two parties for the combat demo</h2>
        <form action="index.php?target=combat&action=combat" method="POST">
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