<?php
    namespace Model\Services;

    use Model\Repository\CharacterRepository;
    use Model\Repository\EnemyPartyRepository;
    use Model\Repository\EnemyPartyMembersRepository;
    use Model\Objects\EnemyParty;

    class EnemyPartyService{
        const MAX_PARTY_MEMBERS_COUNT = 4;

        public function saveNewEnemyParty($partyName){
            $result = [
                'success' => false,
                'msg' => 'New party was not added'
            ];

            $repo = new EnemyPartyRepository();

            if($repo->checkEnemyPartyName($partyName)){
                $result['msg'] = 'Party already exists';
                return $result;
            }

            $newParty = ['name' => $partyName];

            if($repo->saveNewEnemyParty($newParty)){
                $result['success'] = true;
                $result['msg'] = 'New party save successfully';
            }

            return $result;
        }

        public static function jsonCreateEnemyParty($json_decoded){
            $enemyPartyId = $json_decoded['enemyPartyId'];
            $enemyPartyName = $json_decoded['enemyPartyName'];

            $enemyParty = new EnemyParty($enemyPartyName, $enemyPartyId);
            
            foreach($json_decoded['members'] as $member){
                $character = CharacterService::jsonCreateCharacter($member);

                $enemyParty->addMemberToEnemyParty($character);
            }

            return $enemyParty;
        }

        public function getEnemyPartyByName($partyName)
        {
            $result = [
                'success' => false,
                'msg' => 'Party not found'
            ];

            $repo = new EnemyPartyRepository();
            $partyRes = $repo->getEnemyPartyByName($partyName);

            if($partyRes){
                $partyInfo = [
                    'enemyPartyName' => $partyRes['Name'],
                    'enemyPartyId' => (int)$partyRes['EnemyPartyId']
                ];

                $party = new EnemyParty($partyName, $partyInfo['enemyPartyId']);

                $characterService = new CharacterService();
                $enemyPartyMembers = $this->getEnemyPartyMembers($partyInfo['enemyPartyId']);
                foreach($enemyPartyMembers as $epm){
                    $character = $characterService->getCharacterById($epm['characterId'])['character'];

                    $party->addMemberToEnemyParty($character);
                    $this->addMemberToPartyByName($character->getCharacterName(), $partyName);
                }

                $result = [
                    'success' => true,
                    'msg' => 'Party found',
                    'party' => $party
                ];
            }

            return $result;
        }

        public function addMemberToPartyByName($characterName, $partyName){
            $result = [
                'success' => false
            ];

            $characterRepo = new CharacterRepository();
            $partyRepo = new EnemyPartyRepository();
            $partyMembersRepo = new EnemyPartyMembersRepository();

            $character = $characterRepo->getCharacterByName($characterName);
            $party = $partyRepo->getEnemyPartyByName($partyName);

            if($character === false || $party === false){
                $result['msg'] = 'Either Member or Party was not found';
                return $result;
            }

            $characterId = $character['CharacterId'];
            $partyId = $party['EnemyPartyId'];

            $membersCount = (int)$partyMembersRepo->getMembersCount($partyId)['membersCount'];
            if($membersCount >= self::MAX_PARTY_MEMBERS_COUNT){
                $result['msg'] = 'Enemy party ' . $partyName . ' is full';
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
            $partyRepo = new EnemyPartyRepository();
            $partyMembersRepo = new EnemyPartyMembersRepository();

            $character = $characterRepo->getCharacterByName($characterName);
            $party = $partyRepo->getEnemyPartyByName($partyName);

            if($character === false || $party === false){
                $result['msg'] = 'Either Member or Party was not found';
                return $result;
            }

            $characterId = $character['CharacterId'];
            $partyId = $party['EnemyPartyId'];

            $checkForMember = $partyMembersRepo->getMemberFromEnemyPartyById($characterId, $partyId);
            if(!$checkForMember){
                $result['msg'] = $characterName . ' was not found in enemy party ' . $partyName;
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

        public function getEnemyPartyMembers($enemyPartyId){
            $enemyPartyMembersRepo = new EnemyPartyMembersRepository();

            $enemyPartyMembersIds = $enemyPartyMembersRepo->getMembersFromEnemyParty($enemyPartyId);

            $enemyPartyMembers = [];
            foreach($enemyPartyMembersIds as $epm){
                $enemyPartyMember = [
                    'enemyPartyId' => (int)$epm['EnemyPartyId'],
                    'characterId' => (int)$epm['CharacterId']
                ];
                $enemyPartyMembers[] = $enemyPartyMember;
            }

            return $enemyPartyMembers;
        }
    }
?>