<?php
    spl_autoload_register(function ($class) {
        $class = str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
        require_once $class;
    });

    use Model\Services\CharacterService;
    use Model\Services\PlayerPartyService;

    $characterService = new CharacterService();

    $result = $characterService->saveCharacter('Captian Kidd', 200, 8, 42);
    echo var_dump($result) . "<br>";
    $result = $characterService->saveCharacter('Carmen', 150, 10, 60);
    echo var_dump($result) . "<br>"; 
    $result = $characterService->saveCharacter('Zoro', 140, 11, 70);
    echo var_dump($result) . "<br>";  
    $result = $characterService->saveCharacter('Izanagi', 180, 15, 40);
    echo var_dump($result) . "<br>";
    echo "<br>";

    $playerPartyService = new PlayerPartyService();

    $result = $playerPartyService->saveNewParty('Party1');
    echo var_dump($result) . "<br>";
    $result = $playerPartyService->getPartyByName('Party1');
    echo var_dump($result['party']) . "<br>";
    echo "<br>";

    $result = $playerPartyService->addMemberToPartyByName('Izanagi', 'Party1');
    echo $result['msg'] . "<br>";
    $result = $playerPartyService->addMemberToPartyByName('Captian Kidd', 'Party1');
    echo $result['msg'] . "<br>";
    $result = $playerPartyService->addMemberToPartyByName('Zoro', 'Party1');
    echo $result['msg'] . "<br>";
    $result = $playerPartyService->addMemberToPartyByName('Carmen', 'Party1');
    echo $result['msg'] . "<br>";
    $result = $playerPartyService->addMemberToPartyByName('Jack Frost', 'Party1');
    echo $result['msg'] . "<br>";
    $result = $playerPartyService->removeMemberFromParty('Jack Frost', 'Party1');
    echo $result['msg'] . "<br>";
    echo "<br>";
?>