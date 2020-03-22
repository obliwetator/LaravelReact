import * as React from 'react'
import { 
  Route,
  Switch,
  withRouter,
  useParams,
  useRouteMatch
 } from "react-router-dom";
import Welcome from "./Pages/Welcome"

import Footer from './Footer/Footer';
import Summoner from "./Main/Summoner/Summoner";
import NavigationBar from "./NavigationBar/NavigationBar";

import {MainProps, MainState} from "./ReactInterfaces/RootInterface";

class Main extends React.Component<MainProps, MainState> {
  constructor(props: MainProps) {
    super(props);
  }

  render() {
    return (
      <div className="dark">
        {/* <NavigationBar {...this.props}/> */}
        <Switch>
          <Route exact path="/" component={Welcome} />
          <Route exact path="/champions" />
          <Route exact path="/stats" />
          <Route exact path="/leaderboards" />
          {/* <Route path="/summoner" component={Summoner} /> */}

          <Route
            path="/summoner/:region/:name"
            render={({ match }) => (
                <Summoner match={match}/>
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
      </div>
    );
  }
}

export default Main;
// export default withRouter(Main);

// TODO props
function NotFound(props: any) {
  return (
    <div>
      The page {props.location.pathname.slice(1)} does not exist.
    </div>
  )
}