<?php
    namespace Model\Services;

    use Model\Repository\CharacterRepository;

    class CharacterService{
        public function saveCharacter($name, $health, $attackDamage, $mana)
        {
            $result = [
                'success' => false,
                'msg' => 'Character has not been saved'
            ];

            $repo = new CharacterRepository();

            $character = [
                'name' => $name,
                'health' => $health,
                'attackDamage' => $attackDamage,
                'mana' => $mana
            ];

            if($repo->saveCharacter($character)){
                $result['success'] = true;
                $result['msg'] = 'Character saved succesfully';
            }

            return $result;
        }
    }
?>