import React from "react";
import { Route, Switch, withRouter } from "react-router-dom";
import { Navbar, Nav, Form, FormControl, Button } from "react-bootstrap";
import Toggle from 'react-toggle'

class NavigationBar extends React.Component {

  state = {
    searchText: "",
    regions: [],
    text: {},
    region: "EUNE",
    regionValue: "eun1",
    darkMode: localStorage.getItem("darkSwitch")? true: false
  };

  componentDidMount() {
    this.setState({
      regions: [
        { text: "EUNE", value: "eun1" },{ text: "Brazil", value: "br1" },{ text: "EUW", value: "euw1" },{ text: "JP", value: "jp1" },{ text: "KR", value: "kr" },{ text: "LAN", value: "la1" },{ text: "LAS", value: "la2" },{ text: "NA", value: "na1" },{ text: "OCE", value: "oc1" },{ text: "Turkey", value: "tr1" },{ text: "Russia", value: "ru" },
      ]
    })
    if (this.state.darkMode) {
      document.body.setAttribute("data-theme", "dark") 
    }
    else{
      document.body.removeAttribute("data-theme");
    }
    // var darkSwitch = document.getElementById("darkSwitch");
    // if (darkSwitch) {
    //   var darkThemeSelected =
    //     localStorage.getItem("darkSwitch") !== null && localStorage.getItem("darkSwitch") === "dark";
    //     darkSwitch.checked = darkThemeSelected;
    //     darkThemeSelected  ? document.body.setAttribute("data-theme", "dark")   : document.body.removeAttribute("data-theme");

    //     this.setState({darkMode: darkSwitch.checked})
    // }
  }

  handleChangeRegion = event => {
    this.setState({
      regionValue: event.target.value
    });
  }

  handleSearchInput = event => {
    this.setState({
      searchText: event.target.value
    });
  };

  handleSearchSubmit = (event) => {
    // Prevents default form action when pressing enter
    event.preventDefault()

    if (this.state.searchText) {
      this.props.history.push({
        pathname: "/summoner/" + this.state.regionValue + "/" + this.state.searchText,
      });
      // Reset the search box
      this.state.searchText = ""
    } else {
      alert("Please enter some search text!");
    }
  };

  handleRoute = route => () => {
    this.props.history.push({ pathname: route });
  };

  handleDarkMode = (event) => {
    this.setState({darkMode: event.target.checked })

    if (event.target.checked) {
      document.body.setAttribute("data-theme", "dark");
      localStorage.setItem("darkSwitch", "dark");
    } else {
      document.body.removeAttribute("data-theme");
      localStorage.removeItem("darkSwitch");
    }
  }
  
  render() {
    const { regions } = this.state;

    let region = regions.map((item, i) => {
      return (
        <option key={i} value={item.value}>{item.text}</option>
      )
    })
    return (
      <Navbar bg="dark" variant="dark">
        <Nav className="mr-auto">
          <Nav.Link onClick={this.handleRoute("/")}>Home</Nav.Link>
          <Nav.Link onClick={this.handleRoute("/champions")}>Champions</Nav.Link>
          <Nav.Link onClick={this.handleRoute("/stats")}>Stats</Nav.Link>
          <Nav.Link onClick={this.handleRoute("/leaderboards")}>Leadeboards</Nav.Link>

        </Nav>
        <Toggle
        
          icons={{
            checked: <Moon/>,
            unchecked: <Sun/>,
          }}
          onFocus={this.handleFocus}
          className="toggleBarColor"
          id='darkSwitch'
          checked={this.state.darkMode}
          onChange={this.handleDarkMode} />
        <span id='biscuit-label'>Dark Mode</span>
        <p className="text-primary">Doesn't work. Only EUNE for now</p>
        <Form inline onSubmit={this.handleSearchSubmit}>
          {/* We set the value for the region in the state with a default value (EUNE) and when we change the dropdown we update the state */}
          <Form.Control as="select" value={this.state.value} onChange={this.handleChangeRegion}>
            {/* Above we iterrate over the regions and produce a dynamic select menu */}
            {region}
          </Form.Control>
          <Form.Control
            onChange={this.handleSearchInput}
            value={this.state.searchText}
            name="SummonerSearch"
            id="SummonerSearch"
            placeholder="Search"
            className="mr-sm-2"
          />
          <Button onClick={this.handleSearchSubmit} variant="outline-info">
            Search
            </Button>
        </Form>
      </Navbar>
    );
  }
}

export default NavigationBar;

function Moon() {
  return (
    <div className="toggleBar Moon">🌙</div>
  )
}

function Sun() {
  return (
    <div className="toggleBar Sun">☀️</div>
  )
}