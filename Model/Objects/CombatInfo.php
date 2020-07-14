<?php
    namespace Model\Objects;

    use JsonSerializable;

    class CombatInfo implements JsonSerializable{
        public PlayerParty $playerParty;
        public EnemyParty $enemyParty;
        public $turns;
        public $currentTurn;

        public function __construct(PlayerParty $playerParty, EnemyParty $enemyParty, $turns, $currentTurn){
            $this->playerParty = $playerParty;
            $this->enemyParty = $enemyParty;
            $this->turns = $turns;
            $this->currentTurn = $currentTurn;
        }

        public function jsonSerialize(){
            $combatInfo['playerParty'] = $this->playerParty;
            $combatInfo['enemyParty'] = $this->enemyParty;
            $combatInfo['turns'] = $this->turns;
            $combatInfo['currentTurn'] = $this->currentTurn;

            return $combatInfo;
        }
    }
?>