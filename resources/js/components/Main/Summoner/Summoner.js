import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import NotFound from '../../404/NotFound';


import SummonerHeader from './SummonerHeader'
import { Tab, Tabs } from 'react-bootstrap';

import { Summary } from "./pages/Summary";
import LiveGame from './pages/LiveGame';

export default class Summoner extends Component {
  // Prevents a memory leak if we have an async request and dont update the state ( Eg we go forward or backward in the browser)
  _isMounted = false;

  constructor(props) {
    super(props);

    this.state = {
      region: this.props.match.params.region,
      error: null,
      isLoaded: false,
      summonerName: null,
      items: null,
      icons: null,
      summonerSpells: null,
      champions: null,
      runes: null,
      currentTab: "summary",
    };
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

  GetSummonerLeagueTarget = (summoner) => {
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
    return axios.post('/api/getSummmonerSpell', {
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
      .then(axios.spread((Data, Items, Icons, SummonerSpells, Champions, Runes) => {
        if (this._isMounted) {
          this.setState({
            [Data.data.summoner.name.replace(/\s/g, '').toLowerCase()]: Data.data,
            summonerName: Data.data.summoner.name.replace(/\s/g, '').toLowerCase(),
            items: Items.data,
            icons: Icons.data,
            summonerSpells: SummonerSpells.data,
            champions: Champions.data,
            runes: Runes.data,
          })
          
          axios.all([
            this.GetSummonerLeagueTarget(Data.data.summoner)
          ]).then(axios.spread((SummonerLeagueTarget) =>{
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
        }
      }))
      .catch((error) => {
        this.setState({
          error: error
        })
        document.title = "summoner not found"
      })
  }

  componentDidUpdate(prevProps) {
    let prevSearch = prevProps.match.params.name;
    let newSearch = this.props.match.params.name;


    if (prevSearch !== newSearch) {

      // When we search for a new summoner we save they summoner details in the state.
      // If we search for a new summoner or go back/formward in history we will check the state first before calling the server
      if (this.props.match.params.name && this.state[this.props.match.params.name]) {
        // We update which summoner we are currently viewing. The summoner object with the same name should be in the state
        this.setState({ summonerName: this.props.match.params.name })
      }
      else {
        // Will only refresh if the input is different from the previous
        this.handleSearch()
        axios.all([
          this.GetSummoner(),
        ])
          .then(axios.spread((Data) => {
            this.setState({
              [Data.data.summoner.name.replace(/\s/g, '').toLowerCase()]: Data.data,
              summonerName: Data.data.summoner.name.replace(/\s/g, '').toLowerCase(),
              isLoaded: true,
            })
            
            axios.all([
              this.GetSummonerLeagueTarget(Data.data.summoner)
            ]).then(axios.spread((SummonerLeagueTarget) =>{
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
  }

  handleTab = (event) => {
    this.setState({
        currentTab: event
    })
}

  render() {
    console.log("Summoner", this.state)
    // const { error, isLoaded, summonerName } = this.state
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
            <SummonerHeader icons={this.state.icons} summoner={this.state[this.state.summonerName].summoner} />
            <div className="Menu">
              <Tabs onSelect={this.handleTab} activeKey={this.state.currentTab} id="uncontrolled-tab-example">
                <Tab eventKey="summary" title="Summary">
                  <Summary 
                    runes = {this.state.runes}
                    summoner = {this.state[this.state.summonerName].summoner}
                    gamesById = {this.state[this.state.summonerName].gamesById}
                    champions = {this.state.champions}
                    summonerSpells = {this.state.summonerSpells}
                    items = {this.state.items}
                    {...this.state["summonerLeagueTarget" + this.state.summonerName]}
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
                    runes={this.state.runes}
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