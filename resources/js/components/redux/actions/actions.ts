import { Summoner ,ADD_SUMMONER, SummonerActionTypes, UPDATE_SUMMONER, VersionState, VersionTypes, ADD_VERSION } from "../types/types";

export function addSummoner(newSummoner: Summoner): SummonerActionTypes {
    return {
        type: ADD_SUMMONER,
        payload: newSummoner
    }
}

export function updateSummoner(updatedSummoner: Summoner): SummonerActionTypes {
    return {
        type: UPDATE_SUMMONER,
        payload: updatedSummoner
    }
}

export function AddVersion(NewVersion: VersionState): VersionTypes {
    return {
        type: ADD_VERSION,
        payload: NewVersion
    }
}