<?php
    namespace Model\Services;

    use Model\Repository\PlayerPartyRepository;
    use Model\Repository\CharacterRepository;
    use Model\Repository\PlayerPartyMembersRepository;

    define('MAX_PARTY_MEMBERS_COUNT', 4);

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
            $partyRes = $repo->getPartyByName($partyName);

            if($partyRes){
                $result['success'] = true;
                $result['msg'] = 'Party found';

                $party = [
                    'playerPartyName' => $partyRes['Name'],
                    'playerPartyId' => $partyRes['PlayerPartyId']
                ];

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
            
            $partyCap = (int)$partyMembersRepo->getMembersCount($partyId)['membersCount'];
            if($partyCap >= MAX_PARTY_MEMBERS_COUNT){
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

            $playerParty = $playerPartyRepo->getPartyByName($playerPartyName);
            if($playerParty === false) return $result;

            $playerPartyId = $playerParty['PlayerPartyId'];

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

        public function getPlayerPartyCount(){
            $repo = new PlayerPartyRepository();

            return $repo->getPlayerPartyCount();
        }

        public function getPlayerPartyMembers($playerPartyName){
            $playerParty = $this->getPartyByName($playerPartyName);

            //echo json_encode($playerParty, JSON_PRETTY_PRINT);

            $playerPartyMembersRepo = new PlayerPartyMembersRepository();

            $playerPartyMembersIds = $playerPartyMembersRepo->getMembersFromParty($playerParty['party']['playerPartyId']);

            $playerPartyMembers = [];
            foreach($playerPartyMembersIds as $ppm){
                $playerPartyMember = [
                    'playerPartyId' => (int)$ppm['PlayerPartyId'],
                    'characterId' => (int)$ppm['CharacterId']
                ];
                array_push($playerPartyMembers, $playerPartyMember);
            }

            //echo json_encode(var_dump($playerPartyMembers), JSON_PRETTY_PRINT);

            return $playerPartyMembers;
        }
    }
?>