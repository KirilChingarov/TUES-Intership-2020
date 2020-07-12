<?php
    namespace Model\Services;

    use Model\Repository\CharacterRepository;
    use Model\Objects\Character;

    class CharacterService{
        public function saveCharacter($name, $health, $attackDamage, $mana)
        {
            $result = [
                'success' => false,
                'msg' => 'Character has not been saved'
            ];

            $repo = new CharacterRepository();

            if($repo->checkCharacterName($name)){
                $result['msg'] = 'Character is already saved';
                return $result;
            }

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

        public function getCharacterByName($characterName){
            $result = [
                'success' => false,
                'msg' => 'Character has not been found',
            ];

            $repo = new CharacterRepository();
            $characterRes = $repo->getCharacterByName($characterName);

            if($characterRes){
                $result['success'] = true;
                $result['msg'] = 'Character has been found';

                $characterInfo = [
                    'characterId' => (int)$characterRes['CharacterId'],
                    'characterName' => $characterRes['Name'],
                    'characterHealth' => (int)$characterRes['Health'],
                    'characterAttackDamage' => (int)$characterRes['AttackDamage'],
                    'characterMana' => (int)$characterRes['Mana']
                ];

                $character = Character::createCharacter($characterInfo);

                $result['character'] = $character;
            }

            return $result;
        }

        public function getCharacterById($characterId){
            $result = [
                'success' => false,
                'msg' => 'Character has not been found',
            ];

            $repo = new CharacterRepository();
            $characterRes = $repo->getCharacterById($characterId);

            if($characterRes){
                $result['success'] = true;
                $result['msg'] = 'Character has been found';

                $characterInfo = [
                    'characterId' => (int)$characterRes['CharacterId'],
                    'characterName' => $characterRes['Name'],
                    'characterHealth' => (int)$characterRes['Health'],
                    'characterAttackDamage' => (int)$characterRes['AttackDamage'],
                    'characterMana' => (int)$characterRes['Mana']
                ];

                $character = Character::createCharacter($characterInfo);

                $result['character'] = $character;
            }

            return $result;
        }

        public function updateCharacter($character){
            $result = [
                'success' => false,
                'msg' => 'Character was not able to be updated'
            ];

            $repo = new CharacterRepository();

            if($repo->updateCharacter($character)){
                $result['success'] = true;
                $result['msg'] = 'Character Updated successfully';
            }
            
            return $result;
        }
    }
?>