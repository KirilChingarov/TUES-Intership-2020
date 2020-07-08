<?php
    namespace Model\Services;

    use Model\Repository\PlayerPartyRepository;
    use Model\Repository\CharacterRepository;
    use Model\Repository\PlayerPartyMembersRepository;

class PlayerPartyService{
        public function saveNewParty($partyName){
            $result = [
                'success' => false,
                'msg' => 'New party was not added'
            ];

            $repo = new PlayerPartyRepository();

            if($repo->checkPartyName($partyName)){
                $result['msg'] = 'Party already exists';
                return $result;
            }

            $newParty = ['name' => $partyName];

            if($repo->saveNewParty($newParty)){
                $result['success'] = true;
                $result['msg'] = 'New party save successfully';
            }

            return $result;
        }

        public function getPartyByName($partyName)
        {
            $result = [
                'success' => false,
                'msg' => 'Party not found'
            ];

            $repo = new PlayerPartyRepository();
            $party = $repo->getPartyByName($partyName);

            if($party){
                $result['success'] = true;
                $result['msg'] = 'Party found';
                $result['party'] = $party;
            }

            return $result;
        }

        public function addMemberToPartyByName($characterName, $partyName){
            $result = [
                'success' => false
            ];

            $characterRepo = new CharacterRepository();
            $partyRepo = new PlayerPartyRepository();
            $partyMembersRepo = new PlayerPartyMembersRepository();

            $character = $characterRepo->getCharacterByName($characterName);
            $party = $partyRepo->getPartyByName($partyName);

            if($character === false || $party === false){
                $result['msg'] = 'Either Member or Party was not found';
                return $result;
            }

            $characterId = $character['CharacterId'];
            $partyId = $party['PlayerPartyId'];

            if($partyMembersRepo->addMemberToParty($characterId, $partyId)){
                $result['success'] = true;
                $result['msg'] = $characterName . ' was added to ' . $partyName;
            }
            else {
                $result['msg'] = $characterName . ' is already in ' . $partyName;
            }

            return $result;
        }

        public function removeMemberFromParty($characterName, $partyName){
            $result = [
                'success' => false
            ];

            $characterRepo = new CharacterRepository();
            $partyRepo = new PlayerPartyRepository();
            $partyMembersRepo = new PlayerPartyMembersRepository();

            $character = $characterRepo->getCharacterByName($characterName);
            $party = $partyRepo->getPartyByName($partyName);

            if($character === false || $party === false){
                $result['msg'] = 'Either Member or Party was not found';
                return $result;
            }

            $characterId = $character['CharacterId'];
            $partyId = $party['PlayerPartyId'];

            if($partyMembersRepo->removeMemberFromParty($characterId, $partyId)){
                $result['success'] = true;
                $result['msg'] = $characterName . ' was removed from ' . $partyName;
            }
            else {
                $result['msg'] = $characterName . ' is not in ' . $partyName;
            }

            return $result;
        }
    }
?>