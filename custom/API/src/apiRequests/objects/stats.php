<?php
//
//  Stat.php
//  Model Generated using http://www.jsoncafe.com/ 
//  Created on June 12, 2019

namespace API\LeagueAPI\Objects;
use API\LeagueAPI\Objects\objectInit;

class Stat extends objectInit
{
    /** @var int $participantId */
    public $participantId;
    /** @var bool $win */
    public $win;
    /** @var int $item0 */
    public $item0;
    /** @var int $item1 */
    public $item1;
    /** @var int $item2 */
    public $item2;
    /** @var int $item3 */
    public $item3;
    /** @var int $item4 */
    public $item4;
    /** @var int $item5 */
    public $item5;
    /** @var int $item6 */
    public $item6;
    /** @var int $kills */
    public $kills;
    /** @var int $deaths */
    public $deaths;
    /** @var int $assists */
    public $assists;
    /** @var int $largestKillingSpree */
    public $largestKillingSpree;
    /** @var int $largestMultiKill */
    public $largestMultiKill;
    /** @var int $killingSprees */
    public $killingSprees;
    /** @var int $longestTimeSpentLiving */
    public $longestTimeSpentLiving;
    /** @var int $doubleKills */
    public $doubleKills;
    /** @var int $tripleKills */
    public $tripleKills;
    /** @var int $quadraKills */
    public $quadraKills;
    /** @var int $pentaKills */
    public $pentaKills;
    /** @var int $unrealKills */
    public $unrealKills;
    /** @var int $totalDamageDealt */
    public $totalDamageDealt;
    /** @var int $magicDamageDealt */
    public $magicDamageDealt;
    /** @var int $physicalDamageDealt */
    public $physicalDamageDealt;
    /** @var int $trueDamageDealt */
    public $trueDamageDealt;
    /** @var int $largestCriticalStrike */
    public $largestCriticalStrike;
    /** @var int $totalDamageDealtToChampions */
    public $totalDamageDealtToChampions;
    /** @var int $magicDamageDealtToChampions */
    public $magicDamageDealtToChampions;
    /** @var int $physicalDamageDealtToChampions */
    public $physicalDamageDealtToChampions;
    /** @var int $trueDamageDealtToChampions */
    public $trueDamageDealtToChampions;
    /** @var int $totalHeal */
    public $totalHeal;
    /** @var int $totalUnitsHealed */
    public $totalUnitsHealed;
    /** @var int $damageSelfMitigated */
    public $damageSelfMitigated;
    /** @var int $damageDealtToObjectives */
    public $damageDealtToObjectives;
    /** @var int $damageDealtToTurrets */
    public $damageDealtToTurrets;
    /** @var int $visionScore */
    public $visionScore;
    /** @var int $timeCCingOthers */
    public $timeCCingOthers;
    /** @var int $totalDamageTaken */
    public $totalDamageTaken;
    /** @var int $magicalDamageTaken */
    public $magicalDamageTaken;
    /** @var int $physicalDamageTaken */
    public $physicalDamageTaken;
    /** @var int $trueDamageTaken */
    public $trueDamageTaken;
    /** @var int $goldEarned */
    public $goldEarned;
    /** @var int $goldSpent */
    public $goldSpent;
    /** @var int $turretKills */
    public $turretKills;
    /** @var int $inhibitorKills */
    public $inhibitorKills;
    /** @var int $totalMinionsKilled */
    public $totalMinionsKilled;
    /** @var int $neutralMinionsKilled */
    public $neutralMinionsKilled;
    /** @var int $neutralMinionsKilledTeamJungle */
    public $neutralMinionsKilledTeamJungle;
    /** @var int $neutralMinionsKilledEnemyJungle */
    public $neutralMinionsKilledEnemyJungle;
    /** @var int $totalTimeCrowdControlDealt */
    public $totalTimeCrowdControlDealt;
    /** @var int $champLevel */
    public $champLevel;
    /** @var int $visionWardsBoughtInGame */
    public $visionWardsBoughtInGame;
    /** @var int $sightWardsBoughtInGame */
    public $sightWardsBoughtInGame;
    /** @var int $wardsPlaced */
    public $wardsPlaced;
    /** @var int $wardsKilled */
    public $wardsKilled;
    /** @var bool $firstBloodKill */
    public $firstBloodKill;
    /** @var bool $firstBloodAssist */
    public $firstBloodAssist;
    /** @var bool $firstTowerKill */
    public $firstTowerKill;
    /** @var bool $firstTowerAssist */
    public $firstTowerAssist;
    /** @var bool $firstInhibitorKill */
    public $firstInhibitorKill;
    /** @var bool $firstInhibitorAssist */
    public $firstInhibitorAssist;
    /** @var int $combatPlayerScore */
    public $combatPlayerScore;
    /** @var int $objectivePlayerScore */
    public $objectivePlayerScore;
    /** @var int $totalPlayerScore */
    public $totalPlayerScore;
    /** @var int $totalScoreRank */
    public $totalScoreRank;
    /** @var int $playerScore0 */
    public $playerScore0;
    /** @var int $playerScore1 */
    public $playerScore1;
    /** @var int $playerScore2 */
    public $playerScore2;
    /** @var int $playerScore3 */
    public $playerScore3;
    /** @var int $playerScore4 */
    public $playerScore4;
    /** @var int $playerScore5 */
    public $playerScore5;
    /** @var int $playerScore6 */
    public $playerScore6;
    /** @var int $playerScore7 */
    public $playerScore7;
    /** @var int $playerScore8 */
    public $playerScore8;
    /** @var int $playerScore9 */
    public $playerScore9;
    /** @var int $perk0 */
    public $perk0;
    /** @var int $perk0Var1 */
    public $perk0Var1;
    /** @var int $perk0Var2 */
    public $perk0Var2;
    /** @var int $perk0Var3 */
    public $perk0Var3;
    /** @var int $perk1 */
    public $perk1;
    /** @var int $perk1Var1 */
    public $perk1Var1;
    /** @var int $perk1Var2 */
    public $perk1Var2;
    /** @var int $perk1Var3 */
    public $perk1Var3;
    /** @var int $perk2 */
    public $perk2;
    /** @var int $perk2Var1 */
    public $perk2Var1;
    /** @var int $perk2Var2 */
    public $perk2Var2;
    /** @var int $perk2Var3 */
    public $perk2Var3;
    /** @var int $perk3 */
    public $perk3;
    /** @var int $perk3Var1 */
    public $perk3Var1;
    /** @var int $perk3Var2 */
    public $perk3Var2;
    /** @var int $perk3Var3 */
    public $perk3Var3;
    /** @var int $perk4 */
    public $perk4;
    /** @var int $perk4Var1 */
    public $perk4Var1;
    /** @var int $perk4Var2 */
    public $perk4Var2;
    /** @var int $perk4Var3 */
    public $perk4Var3;
    /** @var int $perk5 */
    public $perk5;
    /** @var int $perk5Var1 */
    public $perk5Var1;
    /** @var int $perk5Var2 */
    public $perk5Var2;
    /** @var int $perk5Var3 */
    public $perk5Var3;
    /** @var int $perkPrimaryStyle */
    public $perkPrimaryStyle;
    /** @var int $perkSubStyle */
    public $perkSubStyle;
    /** @var int $statPerk0 */
    public $statPerk0;
    /** @var int $statPerk1 */
    public $statPerk1;
    /** @var int $statPerk2 */
    public $statPerk2;
}
