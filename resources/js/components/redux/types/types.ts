import { RunesReforged } from "../../../ClassInterfaces/RunesReforged";

export interface SummonerType {
    [k:string]: any
    region?: any
    error?: String | null
    isLoaded?: Boolean
    summonerName?: any
    currentTab?: string
    items?: any
    icons?: any
    summonerSpells?: any
    champions?: any
    runes?: RunesReforged
    IsButtonLoading?: boolean
    code?: number | null
    isAlertVisible?: boolean
    target?: number[]
    WinLoss?: [number, number]
    version?: VersionState
}

export interface SummonerState {
    summoner: SummonerType
}

export interface VersionState {
    LastVersion: string
    LatestVersion: string
}

export const ADD_SUMMONER = 'ADD_SUMMONER'
export const UPDATE_SUMMONER = 'UPDATE_SUMMONER'
export const ADD_VERSION = 'ADD_VERSION'


interface AddSummoner {
    type: typeof ADD_SUMMONER,
    payload: SummonerType
}

interface UpdateSummoner {
    type: typeof UPDATE_SUMMONER,
    payload: SummonerType
}

interface AddVersion {
    type: typeof ADD_VERSION
    payload: VersionState
}
export type SummonerActionTypes = AddSummoner | UpdateSummoner |  AddVersion