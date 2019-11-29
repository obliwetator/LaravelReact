import React from "react";
import { Route, Switch, withRouter } from "react-router-dom";
import { Navbar, Nav, Form, FormControl, Button } from "react-bootstrap";

class NavigationBar extends React.Component {

  state = {
    searchText: "",
    regions: [],
    text: {},
    region:"EUNE",
    regionValue: "eun1"
  };

  componentDidMount(){
    this.setState({
      regions:[
				{ text: "EUNE", value: "eun1" },
				{	text: "Brazil", value: "br1" },
				{	text: "EUW", value: "euw1" },
				{	text: "JP",	value: "jp1" },
				{	text: "KR",	value: "kr"	},
				{	text: "LAN", value: "la1"	},
				{	text: "LAS", value: "la2"	},
				{	text: "NA",	value: "na1" },
				{	text: "OCE", value: "oc1" },
				{	text: "Turkey",	value: "tr1" },
				{	text: "Russia",	value: "ru"	},
      ]
    })
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
        pathname: "/summoner/" + this.state.regionValue + "/"+ this.state.searchText,
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

  render() {
    const { regions } = this.state;

    let region = regions.map((item, i) =>{
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
        <p className="text-primary">Doesn't work. Only EUNE for now</p>
        <Form inline onSubmit={this.handleSearchSubmit}>
          {/* We set the value for the region in the state with a default value (EUNE) and when we change the dropdown we update the state */}
          <FormControl as="select" value={this.state.value} onChange={this.handleChangeRegion}>
            {/* Above we iterrate over the regions and produce a dynamic select menu */}
            {region}
          </FormControl>
          <FormControl
            onChange={this.handleSearchInput}
            value={this.state.searchText}
            type="text"
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
