import { combineReducers, createStore } from "redux";
import { summonerReducer } from "./reducers/reducers";

export const rootReducer = combineReducers({
    summonerReducer
})