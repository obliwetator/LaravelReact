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

export interface RootProps {

}

export interface RootState {

}

export interface MainProps {
    // history: History
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
    runes: RunesReforged | null
    isLoaded: Boolean
    summonerName: any
    items: any
    icons: any
    summonerSpells: any
    champions: any
    currentTab: string
}

export interface CreateGameProps {
    match: GameByID
    runes: RunesReforged
    summoner: Summoner
    champions: Champions
    summonerSpells: SummonerSpells
    items: Items
}

export interface CreateGameState {
    ShowMore: String
    IsPressed: Boolean
    IsLoaded: Boolean
    target: number
}

export interface IndividualGameProps extends GameByID {

}

export interface IndividualGameState {

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
    LeagueTarget: LeagueSummoner
}

export interface SummaryState {
    currentTab: string
    chart: Chart | null
}

export interface CreateGamesListProps {
    gamesById: GameByID[]
    runes: RunesReforged
    summoner: Summoner
    champions: Champions
    summonerSpells: SummonerSpells
    items: Items
}

export interface SummonerHeaderProps {
    icons: Icons
    summoner: Summoner
}

export interface NavigationBarProps {
    history: History
}

export interface NavigationBarState {
    searchText: string
    regions: {text:string, value:string }[]
    text: {},
    region: string
    regionValue: string
    darkMode: boolean
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
