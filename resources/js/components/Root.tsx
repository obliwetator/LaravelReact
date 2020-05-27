import { hot } from 'react-hot-loader';
import * as React from 'react'
import { 
	BrowserRouter as Router,
} from "react-router-dom"
import  {RootProps, RootState}  from "./ReactInterfaces/RootInterface";
import Main from './Main';

import configureStore from "./redux";
import { Provider } from 'react-redux';
import { ADD_SUMMONER, UPDATE_SUMMONER } from './redux/types/types';
import { addSummoner } from './redux/actions/actions';
import { Button } from 'react-bootstrap';

class Root extends React.Component<RootProps, RootState> {
	constructor(props: RootProps) {
		super(props);
	}
	componentDidMount() {
		document.title = 'Welcome';
	}

	render() {
		return (
			<Router>
				<Main></Main>
			</Router>
		);	
	}
}

export default hot(module)(Root);


// if (module.hot){
//   	module.hot.accept()
// }

// if (document.getElementById('root')) {
// 	ReactDOM.render(
// 		<Provider store={store}>
// 			<Root/>
// 		</Provider>,
// 	document.getElementById('root'));
// }