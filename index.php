<?php
    spl_autoload_register(function ($class) {
        $class = str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
        require_once $class;
    });

    use Model\Services\CharacterService;

    $characterService = new CharacterService();

    $result = $characterService->saveCharacter('Arsene', 130, 10, 40);

    echo var_dump($result) . "<br><br>";

    $result = $characterService->getCharacterByName('Arsene');

    echo var_dump($result);
?>