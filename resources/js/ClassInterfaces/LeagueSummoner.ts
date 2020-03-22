export interface LeagueSummoner {
    RANKED_SOLO_5x5?: Ranked;
    RANKED_FLEX_SR?:  Ranked;
    RANKED_TFT?:      Ranked;
    RANKED_FLEX_TT?:  Ranked;
}

export interface Ranked {
    leagueId:     string;
    queueType:    string;
    tier:         string;
    rank:         string;
    summonerId:   string;
    summonerName: string;
    leaguePoints: number;
    wins:         number;
    losses:       number;
    veteran:      number;
    inactive:     number;
    freshBlood:   number;
    hotStreak:    number;
    miniSeries?:  MiniSeries;
}

export interface MiniSeries {
    target:   number;
    wins:     number;
    losses:   number;
    progress: string;
}