import * as React from 'react'
import axios, { AxiosResponse } from 'axios';
import NotFound from '../../404/NotFound';
import SummonerHeader from './SummonerHeader'
import { Tab, Tabs } from 'react-bootstrap';
import { Summary } from "./pages/Summary";
import LiveGame from './pages/LiveGame';
import {SummonerProps, SummonerState, AxiosSummonerResponse, AxiosMatchlistResponse}  from "../../ReactInterfaces/RootInterface";
import { Summoner as SummonerClass} from "../../../ClassInterfaces/Summoner";

import { LeagueSummoner } from "../../../ClassInterfaces/LeagueSummoner";
import { Matchlist } from '../../../ClassInterfaces/Matchlist';

export default class Summoner extends React.Component<SummonerProps, SummonerState> {
  // Prevents a memory leak if we have an async request and dont update the state ( Eg we go forward or backward in the browser)
  _isMounted = false;

  constructor(props: SummonerProps) {
    super(props);
    this.state = {
      region: this.props.match.params.region,
      name: this.props.match.params.name,
      error: null,
      isLoaded: false,
      summonerName: null,
      items: null,
      icons: null,
      summonerSpells: null,
      champions: null,
      currentTab: "summary",
      IsButtonLoading: false,
    };
    // We will call this function from <SummonerHeaders /> and bind "this" in order to change the state of this component (Summoner and/or matchlist)
    // All the logic to handling the refresh will be in here
    this.handleRefresh = this.handleRefresh.bind(this)
  }

  componentWillUnmount() {
    this._isMounted = false;
  }

  // When updating the page this should get called to grab the input
  handleSearch = () => {
    this.setState({
      error: null,
      isLoaded: false
    });
  };

  GetSummoner = () => {
    return axios.get('/api/summoner', {
      params: {
        // workaround
        name: this.props.match.params.name,
        region: this.props.match.params.region
      }
    })

  }
  GetSummonerLeagueTarget = (summoner: SummonerClass) => {
    return axios.post('/api/getSummonerLeagueTarget', {
      region: this.props.match.params.region,
      summonerId: summoner.id
    })
  }
  GetItems = () => {
    return axios.post('/api/getItems', {
      region: this.props.match.params.region
    })
  }
  GetIcons = () => {
    return axios.post('/api/getIcons', {
      region: this.props.match.params.region
    })
  }
  GetSummmonerSpell = () => {
    return axios.post('/api/getSummonerSpell', {
      region: this.props.match.params.region
    })
  }
  GetChampions = () => {
    return axios.post('/api/getChampions', {
      region: this.props.match.params.region
    })
  }
  GetRunes = () => {
    return axios.post('/api/getRunes', {
      region: this.props.match.params.region
    })
  }

  handleRefresh(e: React.MouseEvent<HTMLButtonElement>) {
    this.setState({
      IsButtonLoading: true
    })
    const TIME_FOR_SUMMONER: number = 60 * 60 * 24 // 1 day
    const TIME_FOR_MATCHLIST: number = 60 * 5 // 5 minutes

    const TimeNow = Math.round(Date.now() / 1000)
    const TimeSummoner = Math.round(new Date(this.state[this.state.summonerName].summoner.lastUpdate).getTime() / 1000)
    const TimeMatchlist = Math.round(new Date(this.state[this.state.summonerName].summoner.lastUpdateMatchlist).getTime() / 1000)

    axios.post('/api/getMatchlist',{
      region: this.state.region,
      accountId: this.state[this.state.summonerName].summoner.accountId,
      lastMatch: this.state[this.state.summonerName].matchlist[0].gameId

    }).then((response: AxiosResponse<AxiosMatchlistResponse>) => {
      // CAN RETURN NOTHING
      if (!response.data) {
        this.setState({
          IsButtonLoading: false
        })
      } 
      else {
        var summonerState = this.state[this.state.summonerName]
        summonerState.matchlist = response.data.matchlist
        summonerState.gamesById = response.data.gamesById
        this.setState({
          [this.state.summonerName]: summonerState, 
          IsButtonLoading: false
        })
      }
    })
    if (TimeNow - TimeSummoner < TIME_FOR_SUMMONER) {
    }
    else{
    }
}

  // this.state wont update unless this function finishes.
  // We get the name from the location passed from the props
  componentDidMount() {
    this._isMounted = true;
    axios.all([
      this.GetSummoner(),
      this.GetItems(),
      this.GetIcons(),
      this.GetSummmonerSpell(),
      this.GetChampions(),
      this.GetRunes(),
    ])
      .then(axios.spread((Summoner: AxiosResponse<AxiosSummonerResponse>, Items, Icons, SummonerSpells, Champions, Runes) => {
        if (this._isMounted) {
          this.setState({
            [Summoner.data.summoner.name.replace(/\s/g, '').toLowerCase()]: Summoner.data,
            summonerName: Summoner.data.summoner.name.replace(/\s/g, '').toLowerCase(),
            items: Items.data,
            icons: Icons.data,
            summonerSpells: SummonerSpells.data,
            champions: Champions.data,
            runes: Runes.data,
          })
          axios.all([
            this.GetSummonerLeagueTarget(Summoner.data.summoner)
          ]).then(axios.spread((SummonerLeagueTarget) =>{
            let property = "summonerLeagueTarget" + Summoner.data.summoner.name.replace(/\s/g, '').toLowerCase()
            this.setState({
              [property]: SummonerLeagueTarget.data,
            }, () => {
              this.setState({ isLoaded: true})
            })
          })).catch((error) => {
            this.setState({
              error: error
            })
          })
          
          document.title = Summoner.data.summoner.name
        }
      }))
      .catch((error) => {
        this.setState({
          error: error
        })
        document.title = "summoner not found"
      })
  }
  componentDidUpdate(prevProps: SummonerProps) {
    let prevSearch = prevProps.match.params.name;
    let newSearch = this.props.match.params.name;

    if (prevSearch !== newSearch) {

      console.log("prev seracg", prevProps)
      console.log("this seracg", this.props)
      // When we search for a new summoner we save they summoner details in the state.
      // If we search for a new summoner or go back/formward in history we will check the state first before calling the server
      let name: string = this.props.match.params.name.replace(/\s/g, '')

      if (name && this.state[name]) {
        // We update which summoner we are currently viewing. The summoner object with the same name should be in the state
        this.setState({ summonerName: this.props.match.params.name.replace(/\s/g, '') })
      }
      else {
        console.log("else")
        // Will only refresh if the input is different from the previous
        this.handleSearch()
        axios.all([
          this.GetSummoner(),
        ]).then(axios.spread((Data) => {
          console.log("data",Data)
            this.setState({
              [Data.data.summoner.name.replace(/\s/g, '').toLowerCase()]: Data.data,
              summonerName: Data.data.summoner.name.replace(/\s/g, '').toLowerCase(),
              isLoaded: true,
            })
            axios.all([
              this.GetSummonerLeagueTarget(Data.data.summoner)
            ]).then(axios.spread((SummonerLeagueTarget: AxiosResponse<LeagueSummoner>) =>{
              let property = "summonerLeagueTarget" + Data.data.summoner.name.replace(/\s/g, '').toLowerCase()
  
              this.setState({
                [property]: SummonerLeagueTarget.data,
                isLoaded: true,
              })
            })).catch((error) => {
              this.setState({
                error: error
              })
            })

            document.title = Data.data.summoner.name
          }))
          .catch((error) => {
            this.setState({
              error: error
            })
            document.title = "summoner not found"
          })
      }
    }
    else{
    }
  }
  handleTab = (eventKey: any)  => {
    this.setState({
        currentTab: eventKey.toString()
    })
}
  render() {
    if (this.state.error) {
      return <NotFound error={this.state.error} />
    }
    // When trying to access the state very rapidly(Eg when going back/ froward in history). Sometimes it may fail to load some data that we are dowmloading.
    // We check if the data we are downloading is present and only then we can proceed
    else if (!this.state.isLoaded || !this.state.icons || !this.state["summonerLeagueTarget" + this.state.summonerName]) {
      return (
        // Loading animation
        <div className="spinner">
          <div className="rect1"></div>
          <div className="rect2"></div>
          <div className="rect3"></div>
          <div className="rect4"></div>
          <div className="rect5"></div>
        </div>
      );
    }
    else {
      return (
        <>
          <div className="summoner-header">
            <SummonerHeader action={this.handleRefresh} icons={this.state.icons} summoner={this.state[this.state.summonerName].summoner} IsLoading={this.state.IsButtonLoading} />
            <div className="Menu">
              <Tabs onSelect={this.handleTab} activeKey={this.state.currentTab} id="uncontrolled-tab-example">
                <Tab eventKey="summary" title="Summary">
                  <Summary 
                    runes = {this.state.runes!}
                    summoner = {this.state[this.state.summonerName].summoner}
                    gamesById = {this.state[this.state.summonerName].gamesById}
                    champions = {this.state.champions}
                    summonerSpells = {this.state.summonerSpells}
                    items = {this.state.items}
                    leagueTarget = {this.state["summonerLeagueTarget" + this.state.summonerName]}
                  />
                </Tab>
                <Tab eventKey="champions" title="Champions">
                  Profile
                </Tab>
                <Tab eventKey="leagues" title="Leagues">
                  Contact
                </Tab>
                <Tab eventKey="LiveGame" title="Live Game">
                  <LiveGame
                    runes={this.state.runes!}
                    summonerSpells={this.state.summonerSpells}
                    summoner={this.state[this.state.summonerName].summoner}
                    champions={this.state.champions}
                    region={this.state.region}
                    tabs={this.state.currentTab}
                  />
                </Tab>
              </Tabs>

            </div>
          </div>
          <div className="summoner=stats">

          </div>
        </>
      );
    }
  }
}