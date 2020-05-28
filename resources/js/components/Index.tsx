import Root from './Root'
import configureStore from "./redux";
import { Provider } from 'react-redux';
import * as React from 'react'
import * as ReactDOM from 'react-dom'

const store = configureStore()


// const renderApp = () =>
//   ReactDOM.render(
//     <Provider store={store}>
//       <Root />
//     </Provider>,
//     document.getElementById('root')
//   )

// if (process.env.NODE_ENV !== 'production' && module.hot) {
//     // const NextApp = require('../components/Root').default
//     module.hot.accept('../components/Root', renderApp)
// }

// onyl log state changes in development
if (process.env.NODE_ENV !== 'production') {
  store.subscribe(() => console.log('updated state', store.getState()))
}

const renderApp = () => ReactDOM.render(
  <Provider store={store}>
    <Root />
  </Provider>,
  document.getElementById('root')
)

renderApp();


