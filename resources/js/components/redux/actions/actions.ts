import { SummonerType ,ADD_SUMMONER, SummonerActionTypes, UPDATE_SUMMONER, VersionState, ADD_VERSION } from "../types/types";

export function addSummoner(newSummoner: SummonerType): SummonerActionTypes {
    return {
        type: ADD_SUMMONER,
        payload: newSummoner
    }
}

export function updateSummoner(updatedSummoner: SummonerType): SummonerActionTypes {
    return {
        type: UPDATE_SUMMONER,
        payload: updatedSummoner
    }
}

export function AddVersion(NewVersion: VersionState): SummonerActionTypes {
    return {
        type: ADD_VERSION,
        payload: NewVersion
    }
}