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
import { GameByID } from '../../../ClassInterfaces/GameById';
import { connect, useDispatch, createDispatchHook, ConnectedProps, useStore } from 'react-redux';
import { CombinedState } from 'redux';
import { SummonerType, VersionState } from '../../redux/types/types';

import { addSummoner, updateSummoner, AddVersion } from "../../redux/actions/actions";
import { Button } from 'react-bootstrap';
import { match } from 'react-router-dom';
import { RootStateRedux } from '../../redux';

class Summoner extends React.Component<Props, SummonerState> {
  // Prevents a memory leak if we have an async request and dont update the state ( Eg we go forward or backward in the browser)
  _isMounted = false;
  constructor(props: Props) {
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
      code: null,
      isAlertVisible: false
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
    this.props.updateSummoner({
      error: null,
      isLoaded: false
    })
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
    // return axios.get(`/lolContent/${version}/${version}/data/en_GB/item.json`)
    const version = this.props.summonerReducer.summoner.version?.LatestVersion;
    return axios.get(`https://static.patrykstyla.com/${version}/${version}/data/en_GB/item.json`)
  }
  GetIcons = () => {
    const version = this.props.summonerReducer.summoner.version?.LatestVersion;
    return axios.get(`https://static.patrykstyla.com/${version}/${version}/data/en_GB/profileicon.json`)
  }
  GetSummonerSpell = () => {
    const version = this.props.summonerReducer.summoner.version?.LatestVersion;
    return axios.get(`https://static.patrykstyla.com/${version}/${version}/data/en_GB/summonerByKey.json`)
  }
  GetChampions = () => {
    const version = this.props.summonerReducer.summoner.version?.LatestVersion;
    return axios.get(`https://static.patrykstyla.com/${version}/${version}/data/en_GB/championByKey.json`)
  }
  GetRunes = () => {
    const version = this.props.summonerReducer.summoner.version?.LatestVersion;
    return axios.get(`https://static.patrykstyla.com/${version}/${version}/data/en_GB/runesReforgedByKey.json`)
  }  
  GetInit = () => {
    return axios.post('/api/getInit')
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

    axios.post('/api/getMatchlist', {
      region: this.state.region,
      accountId: this.state[this.state.summonerName].summoner.accountId,
      lastMatch: this.state[this.state.summonerName].matchlist[0].gameId,
      summonerRevision: this.state[this.state.summonerName].summoner.lastUpdate,
      matchlistRevision: this.state[this.state.summonerName].summoner.lastUpdateMatchlist
    }).then((response: AxiosResponse<AxiosMatchlistResponse>) => {
      // If not data is present it will return some code
      if (response.data.code !== undefined) {
        // TODO: Handle the code and display an appropriate error message
        // TODO: Create an enum for the codes to match the backend
        // TODO: Set next time a user can refresh AND include it in the error message. See below for time validation

        // We set IsAlertVisible to true in order to show an appropriate allrt in SummonerHeader.tsx
        // We set a timeout so that the alert will dissapear after some time
        this.setState({
          code: response.data.code,
          IsButtonLoading: false,
          isAlertVisible: true
        }, () => {
          setTimeout(() => {
            this.setState({
              isAlertVisible: false
            })
          }, 3500);
        })
      }
      else {
        // If we reach this point matchlist and gamesById are ALWAYS present
        var summonerState = this.state[this.state.summonerName]
        summonerState.matchlist = response.data.matchlist
        summonerState.gamesById = response.data.gamesById
        // summoner isn't guaranteed to be returned
        summonerState.summoner ? summonerState.summoner : summonerState.summoner = response.data.summoner

        // Find the target summoner 
        // [0] = WIN, [1] = LOSS
        let WinLoss: [number, number] = [0,0]
        let targetSummoner: number[] = []
        response.data.gamesById!.forEach((match, i) => {
          match.participantIdentities.forEach((participantIdentities, j) => {
              if (participantIdentities.player.summonerId == this.state[this.state.summonerName].summoner.id) {
                  targetSummoner.push(j)
                  if (match.participants[j].teamId === 100) {
                      // Left Team
                      match.teams[0].win == "Win" ? WinLoss[0] += 1: WinLoss[1] += 1
                  }
                  else {
                      // Right Team
                      match.teams[1].win == "Win" ? WinLoss[0] += 1: WinLoss[1] += 1
                  }
              }
          })
      })
        this.setState({
          [this.state.summonerName]: summonerState,
          WinLoss: WinLoss,
          target: targetSummoner,
          IsButtonLoading: false
        })
      }
    }).catch((error) => {
      this.setState({
        code: null,
        IsButtonLoading: false,
        isAlertVisible: true
      }, () => {
        setTimeout(() => {
          this.setState({
            isAlertVisible: false
          })
        }, 3500);
      })
    })
    // TODO: Implement client side validation, use enums
    if (TimeNow - TimeSummoner < TIME_FOR_SUMMONER) {
    }
    else{
    }
}
  componentDidMount() {
    this._isMounted = true;
    
    axios.post('/api/getInit')
      .then((Version: AxiosResponse<VersionState>) => {
        if (this._isMounted) {
            this.props.AddVersion(Version.data)

            axios.all([
              this.GetSummoner(),
              this.GetItems(),
              this.GetIcons(),
              this.GetSummonerSpell(),
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
                    let WinLoss: [number, number] = [0,0]
                    let targetSummoner: number[] = []
                    Summoner.data.gamesById!.forEach((match, i) => {
                      match.participantIdentities.forEach((participantIdentities, j) => {
                          if (participantIdentities.player.summonerId == Summoner.data.summoner.id) {
                              targetSummoner.push(j)
                              if (match.participants[j].teamId === 100) {
                                  // Left Team
                                  match.teams[0].win == "Win" ? WinLoss[0] += 1: WinLoss[1] += 1
                              }
                              else {
                                  // Right Team
                                  match.teams[1].win == "Win" ? WinLoss[0] += 1: WinLoss[1] += 1
                              }
                          }
                      })
                  })
                    this.setState({
                      [property]: SummonerLeagueTarget.data,
                      WinLoss: WinLoss,
                      target: targetSummoner
                    }, () => {
                      this.setState({ isLoaded: true}, () => {
                        // TEMP DELETE
                        this.props.addSummoner(this.state)
                      })
        
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
      })


  }
  componentDidUpdate(prevProps: SummonerProps) {
    let prevSearch = prevProps.match.params.name;
    let newSearch = this.props.match.params.name;

    if (prevSearch !== newSearch) {
      // When we search for a new summoner we save they summoner details in the state.
      // If we search for a new summoner or go back/formward in history we will check the state first before calling the server
      let name: string = this.props.match.params.name.replace(/\s/g, '')

      if (name && this.state[name]) { 
        // We update which summoner we are currently viewing. The summoner object with the same name should be in the state
        this.setState({ summonerName: this.props.match.params.name.replace(/\s/g, '') })
        let WinLoss: [number, number] = [0,0]
        let targetSummoner: number[] = []
        this.state[this.props.match.params.name.replace(/\s/g, '')].gamesById!.forEach((match: GameByID, i: number) => {
          match.participantIdentities.forEach((participantIdentities, j) => {
              if (participantIdentities.player.summonerId == this.state[this.props.match.params.name.replace(/\s/g, '')].summoner.id) {
                  targetSummoner.push(j)
                  if (match.participants[j].teamId === 100) {
                      // Left Team
                      match.teams[0].win == "Win" ? WinLoss[0] += 1: WinLoss[1] += 1
                  }
                  else {
                      // Right Team
                      match.teams[1].win == "Win" ? WinLoss[0] += 1: WinLoss[1] += 1
                  }
              }
          })
        })
        this.setState({
          target: targetSummoner,
          WinLoss: WinLoss,
        })
        
      }
      else {
        // Will only refresh if the input is different from the previous
        this.handleSearch()
        axios.all([
          this.GetSummoner(),
        ]).then(axios.spread((Data) => {
            this.setState({
              [Data.data.summoner.name.replace(/\s/g, '').toLowerCase()]: Data.data,
              summonerName: Data.data.summoner.name.replace(/\s/g, '').toLowerCase(),
              isLoaded: true,
            }, () => {
              let WinLoss: [number, number] = [0,0]
              let targetSummoner: number[] = []
              this.state[this.props.match.params.name.replace(/\s/g, '')].gamesById!.forEach((match: GameByID, i: number) => {
                match.participantIdentities.forEach((participantIdentities, j) => {
                    if (participantIdentities.player.summonerId == this.state[this.props.match.params.name.replace(/\s/g, '')].summoner.id) {
                        targetSummoner.push(j)
                        if (match.participants[j].teamId === 100) {
                            // Left Team
                            match.teams[0].win == "Win" ? WinLoss[0] += 1: WinLoss[1] += 1
                        }
                        else {
                            // Right Team
                            match.teams[1].win == "Win" ? WinLoss[0] += 1: WinLoss[1] += 1
                        }
                    }
                })
              })
              this.setState({
                target: targetSummoner,
                WinLoss: WinLoss,
              })
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
            <SummonerHeader 
              isVisible={this.state.isAlertVisible}
              code={this.state.code}
              action={this.handleRefresh}
              icons={this.state.icons} 
              summoner={this.state[this.state.summonerName].summoner} 
              IsLoading={this.state.IsButtonLoading} 
              Version={this.state.Version}/>
            <div className="Menu">
              <Tabs onSelect={this.handleTab} activeKey={this.state.currentTab} id="uncontrolled-tab-example">
                <Tab eventKey="summary" title="Summary">
                  <Summary 
                    WinLoss = {this.state.WinLoss!}
                    targets = {this.state.target!}
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
          <div className="summoner-stats">

          </div>
        </>
      );
    }
  }
}

const mapDispatchToProps = { addSummoner, updateSummoner, AddVersion }

const connector = connect(mapStateToProps, mapDispatchToProps)

type PropsFromRedux = ConnectedProps<typeof connector>

type Props = PropsFromRedux & {
  // any extra props
  match: match<{name:string, region:string}>
}

function mapStateToProps(state: CombinedState<{
  summonerReducer: SummonerType;}>) {
  return {
    ...state
  }
}

export default connector(Summoner)