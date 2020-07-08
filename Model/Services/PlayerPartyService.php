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

            
            $partyCap = (int)$partyMembersRepo->getMembersCount($partyId)['COUNT(*)'];

            if($partyCap >= 4){
                $result['msg'] = $partyName . ' is already full';
                return $result;
            }

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

            $checkForMember = $partyMembersRepo->getMemberFromPartyById($characterId, $partyId);
            if(!$checkForMember){
                $result['msg'] = $characterName . ' was not found in ' . $partyName;
                return $result;
            }

            if($partyMembersRepo->removeMemberFromParty($characterId, $partyId)){
                $result['success'] = true;
                $result['msg'] = $characterName . ' was removed from ' . $partyName;
            }
            else {
                $result['msg'] = $characterName . ' is not in ' . $partyName;
            }

            return $result;
        }

        private function checkPlayerPartyCapacity($playerPartyName){
            $result = [
                'success' => false,
                'msg' => $playerPartyName . ' was not found'
            ];

            $playerPartyRepo = new PlayerPartyRepository();
            $playerPartyMembersRepo = new PlayerPartyMembersRepository();

            $playerPartyId = $playerPartyRepo->getPartyByName($playerPartyName);
            if($playerPartyId === false) return $result;

            $playerPartyId = $playerPartyId['PlayerPartyId'];

            $playerPartyMembersCount = $playerPartyMembersRepo->getMembersCount($playerPartyId);

            if($playerPartyMembersCount == false){
                $result['msg'] = $playerPartyName . 'was not found or it is empty';
                return $result;
            }

            $playerPartyMembersCount = (int)$playerPartyMembersCount['COUNT(*)'];

            $result['success'] = true;
            $result['msg'] = $playerPartyName . ' was found';
            $result['count'] = $playerPartyMembersCount;

            return $result;
        }
    }
?>