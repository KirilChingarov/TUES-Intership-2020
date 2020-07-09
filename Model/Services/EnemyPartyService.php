<?php
    namespace Model\Services;

    use Model\Repository\CharacterRepository;
    use Model\Repository\EnemyPartyRepository;
    use Model\Repository\EnemyPartyMembersRepository;

    define('MAX_PARTY_MEMBERS_COUNT', 4);

    class EnemyPartyService{
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

        public function getEnemyPartyByName($partyName)
        {
            $result = [
                'success' => false,
                'msg' => 'Party not found'
            ];

            $repo = new EnemyPartyRepository();
            $partyRes = $repo->getEnemyPartyByName($partyName);

            if($partyRes){
                $result['success'] = true;
                $result['msg'] = 'Party found';

                $party = [
                    'enemyPartyName' => $partyRes['Name'],
                    'enemyPartyId' => $partyRes['EnemyPartyId']
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
            if($membersCount >= MAX_PARTY_MEMBERS_COUNT){
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

        public function getEnemyPartyMembers($enemyPartyName){
            $enemyParty = $this->getEnemyPartyByName($enemyPartyName);

            $enemyPartyMembersRepo = new EnemyPartyMembersRepository();

            $enemyPartyMembersIds = $enemyPartyMembersRepo->getMembersFromEnemyParty($enemyParty['party']['enemyPartyId']);

            $enemyPartyMembers = [];
            foreach($enemyPartyMembersIds as $epm){
                $enemyPartyMember = [
                    'enemyPartyId' => (int)$epm['EnemyPartyId'],
                    'characterId' => (int)$epm['CharacterId']
                ];
                array_push($enemyPartyMembers, $enemyPartyMember);
            }

            return $enemyPartyMembers;
        }
    }
?>