import * as React from 'react'
import * as ReactDOM from 'react-dom'
import { BrowserRouter as Router } from "react-router-dom"
import  {RootProps, RootState}  from "./ReactInterfaces/RootInterface";
import Main from './Main';
import { useHistory } from "react-router-dom";

export default class Root extends React.Component<RootProps, RootState> {
	
	constructor(props : RootProps) {
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