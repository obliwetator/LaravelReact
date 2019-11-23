import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import {
	BrowserRouter as Router,
	Redirect,
	Switch,
	Route,
	Link,
	useParams
} from "react-router-dom";



import { useHistory as history } from "react-router-dom";
import Main from './Main';


export default class Root extends Component {
	
	constructor(props) {
		super(props);
		this.state = {};
	}
	componentDidMount() {
		document.title = 'Title';
	}

	render() {

		return (
			<div>
				<Router> 
					<Main></Main>
				</Router>
			</div>
		);
	}
}

if (document.getElementById('root')) {
	ReactDOM.render(<Root />, document.getElementById('root'));
}


{/* <Redirect to="/a"></Redirect> */}

{/* <Switch>
	<Route exact path="/">
		<Welcome />
	</Route>
	<Route exact path="/champions">
		<h1>Champions</h1>
	</Route>

	<Route path='/summoner'>
		<h1>Summoner</h1>
	</Route>

	<Route path="/lol/summoner/:region/:name/summary" component={Summoner} />



	If the url doesnt match any of the specified above
	<Route path='*' exact={true}>
		<h1>Not Found</h1>
	</Route>

</Switch> */}
