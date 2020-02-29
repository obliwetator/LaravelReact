import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router } from "react-router-dom";
import Main from './Main';

export default class Root extends Component {
	
	constructor(props) {
		super(props);
		this.state = {};
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

if (document.getElementById('root')) {
	ReactDOM.render(<Root />, document.getElementById('root'));
}