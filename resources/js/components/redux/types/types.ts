import { RunesReforged } from "../../../ClassInterfaces/RunesReforged";

export interface Summoner {
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
    version?: {LatestVersion:string, LastVersion:string}
}

export interface SummonerState {
    summoner: Summoner
}

export interface TestState {
    test: string
}

export interface VersionState {
    GameVersion: string
    DDVersion: string
}

export const ADD_SUMMONER = 'ADD_SUMMONER'
export const UPDATE_SUMMONER = 'UPDATE_SUMMONER'
export const ADD_VERSION = 'ADD_VERSIOn'


interface AddSummoner {
    type: typeof ADD_SUMMONER,
    payload: Summoner
}

interface UpdateSummoner {
    type: typeof UPDATE_SUMMONER,
    payload: Summoner
}

interface AddVersion {
    type: typeof ADD_VERSION
    payload: VersionState
}
export type SummonerActionTypes = AddSummoner | UpdateSummoner
export type VersionTypes = AddVersion