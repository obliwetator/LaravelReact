import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import NotFound from '../../404/NotFound';

import Image from 'react-bootstrap/Image'

import SummonerHeader from './SummonerHeader'

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
    // .then(response => {
    //   this.setState({
    //     data: response.data
    //   })
    // })
    // .catch(error => {
    //   this.setState({ error: error })
    // })
    // .finally(() => this.setState({
    //   isLoaded: true
    // }));
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



  // this.state wont update unless this function finishes.
  // We get the name from the location passed from the props
  componentDidMount() {

    this._isMounted = true;

    axios.all([
      this.GetSummoner(),
      this.GetItems(),
      this.GetIcons()
    ])
      .then(axios.spread((Summoner, Items, Icons) => {
        if (this._isMounted) {
        this.setState({
          [Summoner.data.name]: Summoner.data.replace(/\s/g,''),
          summonerName: Summoner.data.name.replace(/\s/g,''),
          items: Items.data,
          icons: Icons.data,
          isLoaded: true,
        })
        document.title = this.state.summonerName
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

    // Will only refresh if the input is different from the previous
    if (prevSearch !== newSearch) {
      this.handleSearch()
          axios.all([
      this.GetSummoner(),
    ])
      .then(axios.spread((Summoner) => {
        this.setState({
          [Summoner.data.name]: Summoner.data.replace(/\s/g,''),
          summonerName: Summoner.data.name.replace(/\s/g,''),
          isLoaded: true,
        })
        document.title = this.state.summonerName
      }))
      .catch((error) => {
        this.setState({
          error: error
        })
        document.title = "summoner not found"
      })
    }
  }

  render() {
    // const { error, isLoaded, summonerName } = this.state
    { console.log(this.state)}
    if (this.state.error) {
      return <NotFound error={this.state.error} />
    }
    else if (!this.state.isLoaded) {
      return <div>Loading...</div>;
    }
    else {
      return (
        <>
          <div className="summoner-header">
            <SummonerHeader icons={this.state.icons} summoner={this.state[this.state.summonerName]}/>
            <div className="Menu">
              <h1>Some menus to choose from for different stats</h1>
              <ul className="nav nav-tabs" id="myTab" role="tablist">
                <li id="Summary-tab" className="nav-item" >
                  <a data-toggle="tab" href="#Summary" role="tab" aria-controls="Summary" aria-selected="true" className="nav-link active">Summary</a>
                </li>

                <li id="Champions-tab" className="nav-item" >
                  <a data-toggle="tab" href="#Champions" role="tab" aria-controls="Champions" aria-selected="false" className="nav-link">Champions</a>
                </li>

                <li id="Leagues-tab" className="nav-item" >
                  <a data-toggle="tab" href="#Leagues" role="tab" aria-controls="Leagues" aria-selected="false" className="nav-link">Leagues</a>
                </li>

                <li id="LiveGame-tab" className="nav-item" >
                  <a data-toggle="tab" href="#LiveGame " role="tab" aria-controls="LiveGame" aria-selected="false" className="nav-link">Live Game</a>
                </li>
              </ul>
            </div>
          </div>
          <div className="summoner=stats">

          </div>
        </>
      );
    }

  }
}