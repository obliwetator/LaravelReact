import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import NotFound from '../404/NotFound';

export default class Summoner extends Component {
  constructor(props) {
    super(props);

    this.state = {
    summoner: this.props.match.params.summoner,
    region: this.props.match.params.region,
    error: null,
    isLoaded: false,
    data: []
  };
}

  // When updating the page this should get called to grab the input
  handleSearch = () => {

    this.setState({
      error: null,
      isLoaded: false
    });
  };

  GetSummoner = () =>{
    axios.get('/api/summoner', {
      params: {
        // workaround
        name: this.props.match.params.summoner,
        region: this.props.match.params.region
      }
    })
      .then(response => {
        this.setState({
          data: response.data
        })
      })
      .catch(error => {
        this.setState({ error: error })
      })
      .finally(() => this.setState({
        isLoaded: true
      }));
  }

  // this.state wont update unless this function finishes.
  // We get the name from the location passed from the props
  componentDidMount() {
    this.GetSummoner()
  }

  componentDidUpdate(prevProps) {

    let prevSearch = prevProps.match.params.summoner;
    let newSearch = this.props.match.params.summoner;
    // Will only refresh if the input is different from the previous
    if (prevSearch !== newSearch) {
      console.log(this)
      this.handleSearch()
      this.GetSummoner()
    }
  }

  render() {
    const { error, isLoaded, data } = this.state

    if (error) {
      return <NotFound error={error}/>
    } 
    else if (!isLoaded) {
      return <div>Loading...</div>;
    } 
    else {
      return (
        <div className="container">
          <div className="row justify-content-center">
            <div className="col-md-8">
              <div className="card">
                <div className="card-header">{data.name}</div>
                <div className="card-body">
                  Level: {data.summonerLevel}
                </div>
              </div>
            </div>
          </div>
        </div>
      );
    }

  }
}