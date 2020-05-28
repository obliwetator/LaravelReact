import { SummonerState, ADD_SUMMONER, SummonerActionTypes, TestState, UPDATE_SUMMONER } from "../types/types";

const initialState: SummonerState = {
    summoner : {
            region: "",
            name: "",
            error: null,
            isLoaded: false,
            summonerName: null,
            items: null,
            icons: null,
            summonerSpells: null,
            champions: null,
            currentTab: "summary",
            IsButtonLoading: false,
            code: null,
            isAlertVisible: false,
            version: {
                LatestVersion: "",
                LastVersion: ""
            }
    },
}

export function summonerReducer(
    state = initialState,
    action: SummonerActionTypes
): SummonerState {
    console.log("action", action)
    switch (action.type) {
        // Probably not needed to split update and add but it makes it easier to track
        case ADD_SUMMONER:
            return {
                // We have a blank initial state, add a new summoner
                summoner: action.payload
            }
        case UPDATE_SUMMONER:
            return {
                // IMPORTANT return ...state before the ...action
                // otherwise the state will override the action making it pointless ¯\_(ツ)_/¯
                summoner: {
                    ...state.summoner,
                    ...action.payload,
                }
            }
        default:
            return state
    }
}