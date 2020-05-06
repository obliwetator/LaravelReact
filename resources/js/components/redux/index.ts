import { combineReducers, createStore } from "redux";

import { summonerReducer } from "./reducers/reducers";
import { rootReducer } from "./reducers";

export type RootStateRedux = ReturnType<typeof rootReducer>

export default function configureStore() {
    const store = createStore(
        rootReducer,
        (window as any).__REDUX_DEVTOOLS_EXTENSION__ && (window as any).__REDUX_DEVTOOLS_EXTENSION__()
    )

    if (process.env.NODE_ENV !== 'production' && module.hot) {
        module.hot.accept('./reducers', () => store.replaceReducer(rootReducer))
    }
    
    return store
} 