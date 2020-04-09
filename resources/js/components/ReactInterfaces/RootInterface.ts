import { RouteProps, RouteComponentProps, match } from 'react-router';
import * as React from 'react';
import * as Chart from 'chart.js';
import { RunesReforged } from '../../ClassInterfaces/RunesReforged';
import { Summoner } from '../../ClassInterfaces/Summoner';
import { Runes } from '../Main/Common/ImageComponents';
import { ActiveGame } from '../../ClassInterfaces/ActiveGame';
import { LeagueSummoner } from '../../ClassInterfaces/LeagueSummoner';
import { SelectCallback } from 'react-bootstrap/helpers';
import { SummonerSpells } from '../../ClassInterfaces/SummonerSpells';
import { Champions } from '../../ClassInterfaces/Champions';
import { GameByID } from "../../ClassInterfaces/GameById";
import { Icons } from "../../ClassInterfaces/Icons";
import { Items } from '../../ClassInterfaces/Items';
import { History } from 'history';
import { Matchlist } from '../../ClassInterfaces/Matchlist';

export interface RootProps {
}

export interface RootState {

}

export interface MainProps {
    // match: match
}

export interface MainState {

}

export interface SummonerProps {
    region?: any
    match: match<{name:string, region:string}>
}

export interface SummonerState {
    [k:string]: any
    region?: any
    error: String | null
    isLoaded: Boolean
    summonerName: any
    currentTab: string
    items: any
    icons: any
    summonerSpells: any
    champions: any
    runes?: RunesReforged
    IsButtonLoading: boolean
    code: number | null
    isAlertVisible: boolean
    target?: number[]
    WinLoss?: [number, number]
}

export interface AxiosMatchlistResponse {
    matchlist?: Matchlist[]
    gamesById?: GameByID[]
    summoner?: Summoner
    code?: number
}

export interface CreateGameProps {
    runes: RunesReforged | null
    match: GameByID
    summoner: Summoner
    champions: Champions
    summonerSpells: SummonerSpells
    items: Items
    target: number
}

export interface CreateGameState {
    ShowMore: String
    IsPressed: boolean
    IsLoaded: boolean
    IsComponentLoaded: boolean
}

export interface IndividualGameProps extends GameByID {
    IsPressed: boolean
}

export interface IndividualGameState {
    IsLoaded: boolean
}

export interface LiveGameProps {
    tabs: string
    region: string
    summoner: Summoner
    runes: RunesReforged
    summonerSpells: SummonerSpells
    champions: Champions
}

export interface LiveGameState {
    isLiveGameLoaded: boolean
    activeGame: ActiveGame | null
    summonerLeagues: LeagueSummoner[] | null
    isLoaded: boolean
}

export interface LiveGameContentProps {
    activeGame: ActiveGame
    runes: RunesReforged
    summonerSpells: SummonerSpells
    champions: Champions
    summonerLeague: LeagueSummoner[]
}

export interface TableContentProps {
    activeGame: ActiveGame
    runes: RunesReforged
    summonerSpells: SummonerSpells
    champions: Champions
    summonerLeague: LeagueSummoner[]
}

export interface SummaryProps {
    runes: RunesReforged
    champions: Champions
    summonerSpells: SummonerSpells
    items: Items
    gamesById: GameByID[]
    summoner: Summoner
    leagueTarget: LeagueSummoner
    targets: number[]
    WinLoss: [number, number]
}

export interface SummaryState {
    currentTab: string
    chart: Chart | null
}

export interface CreateGamesListProps {
    runes: RunesReforged | null
    gamesById: GameByID[]
    summoner: Summoner
    champions: Champions
    summonerSpells: SummonerSpells
    items: Items
    target: number[]
}

export interface SummonerHeaderProps {
    action: any
    icons: Icons
    summoner: Summoner
    IsLoading: boolean
    code: number | null
    isVisible: boolean
}

export interface SummonerHeaderState {
}

export interface NavigationBarProps {
}

export interface NavigationBarState {
    searchText: string
    regions: {text:string, value:string }[]
    text: {},
    region: string
    regionValue: string
    darkMode: boolean
}

export interface AxiosSummonerResponse {
    summoner: Summoner
    matchlist: Matchlist[]
    gamesById: GameByID[]
}

export interface Props {

}

export interface State {

}

export interface Props {

}

export interface State {

}

export interface Props {

}

export interface State {

}

export interface Props {

}

export interface State {

}
