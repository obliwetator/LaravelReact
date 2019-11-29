import React from "react";
import { 
  Route,
  Switch,
  withRouter,
  useParams,
  useRouteMatch
 } from "react-router-dom";
import { Navbar, Nav, Form, FormControl, Button } from "react-bootstrap";
import Welcome from "./Pages/Welcome"

import Footer from './Footer/Footer';
import Summoner from "./Main/Summoner/Summoner";
import NavigationBar from "./NavigationBar/NavigationBar";

class Main extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (
      <>
        <NavigationBar {...this.props}/>
        <Switch>
          <Route exact path="/" component={Welcome} />
          <Route exact path="/champions" />
          <Route exact path="/stats" />
          <Route exact path="/leaderboards" />
          {/* <Route path="/summoner" component={Summoner} /> */}

          <Route
            path="/summoner"
            render={({ match: { url } }) => (
              <>
                <Route path={`${url}/:region/:name`} component={Summoner} />
              </>
            )}
          />

          {/* <Route path="*" component={NotFound} /> */}
          {/* If we want to pass parameters use the render tag */}
          <Route
            path='*'
            render={(props) => <NotFound {...props} isAuthed={true} />}
          />
        </Switch>
        <Footer />
      </>
    );
  }
}

export default withRouter(Main);


function NotFound(props) {
  return (
    <div>
      The page {props.location.pathname.slice(1)} does not exist.
    </div>
  )
}