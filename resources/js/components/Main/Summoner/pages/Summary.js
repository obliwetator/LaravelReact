import React from 'react';
import Image from 'react-bootstrap/Image'
import { Tab, Tabs } from 'react-bootstrap';
import Chart from 'chart.js';

import CreateGame from './CreateGame';

export class Summary extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            chart: null,
            currentTab: "TotalGames"
        };

    }

    
    componentDidMount() {
        // [0] = WIN, [1] = LOSS
        let WinLoss = [0,0]
        
        let target = []
        this.props.gamesById.forEach((match, i) => {
            match.participantIdentities.forEach((participantIdentities, j) => {
                if (participantIdentities.player.summonerId == this.props.summoner.id) {
                    target.push(j)
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
        this.InitChart(WinLoss)
    }

    InitChart = (WinLoss) => {
        let data = {
            labels: ['Win', 'Loss'],
            datasets: [{
                data: WinLoss,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                ]
            }],
        };
        let settings = {
            tooltips: {
                enabled: false,
            },
            legend: {
                display: false,
            }
        }

        let ctx = document.getElementById('myChart').getContext('2d');
        let chart = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: settings
        });

        this.chart = chart
    }

    UpdateChart = (WinLoss) => {
        this.chart.data.datasets.forEach((dataset) => {
            dataset.data[0] = WinLoss[0];
            dataset.data[1] = WinLoss[1];
        });
        
        this.chart.update()
    }

    componentDidUpdate() { 
        // [0] = WIN, [1] = LOSS
        let WinLoss = [0,0]

        let target = []
        this.props.gamesById.forEach((match, i) => {
            match.participantIdentities.forEach((participantIdentities, j) => {
                if (participantIdentities.player.summonerId == this.props.summoner.id) {
                    target.push(j)
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
        this.UpdateChart(WinLoss)
    }

    chartButton = (event) => {
        console.log(this.chart.data.datasets[0].data)
    }

    handleTab = (event) => {
        this.setState({
            currentTab: event
        })
        console.log(event)
    }


    render() {
        const { RANKED_FLEX_SR, RANKED_SOLO_5x5, RANKED_TFT } = this.props
        const { gamesById, summoner, champions, summonerSpells, items } = this.props

        return (
            <div className="row">
                <div className="col-md-3" >
                    <SOLO {...RANKED_SOLO_5x5} />
                    <hr className="bg-success"></hr>
                    <FLEX {...RANKED_FLEX_SR} />
                </div>
                <div className="col-md-9">
                    <div className="container-fluid">
                        <Tabs onSelect={this.handleTab} activeKey={this.state.currentTab} id="Tab-Summary">
                            <Tab eventKey="TotalGames" title="TotalGames">
                                <div className="content">
                                    <div className="gameAverageBox">
                                        <div style={{ width: "100px", height: "100px" }}>
                                            <canvas id="myChart" width="100px" height="100px"></canvas>
                                        </div>
                                        <button className="btn btn-primary" onClick={this.chartButton}>Chart Values</button>
                                    </div>
                                    {/* Each div is a game */}
                                    <div className="GameList">
                                        <CreateGamesList  {...this.props} />
                                    </div>
                                </div>
                            </Tab>
                            <Tab eventKey="RankedSoloGames" title="Ranked Solo Games">
                                {/* <CreateGamesList  {...this.props} /> */}
                                <RankedSoloGames tabs={this.state.currentTab}/>
                            </Tab>
                            <Tab eventKey="RankedFlexGames" title="Ranked Flex Games">
                                Ranked Flex
                            </Tab>
                            <Tab eventKey="SelectQueueType" title="Select Queue Type">
                                Selected queue type
                            </Tab>
                        </Tabs>
                    </div>

                </div>
            </div>
        )
    }
}

function RankedSoloGames(props) {
    const [isLoaded, setisLoaded] = React.useState(false);
    if (!isLoaded) {
        setisLoaded(true)
    }

    if (props.tabs == "RankedSoloGames" || isLoaded) {
        return <div>Solo games</div>
    }
    else{
        return null
    }
}

function SOLO(props) {
    // If SOLO rank isset
    if (Object.entries(props).length !== 0) {
        return (
            <>
                {/* isset summoer target league solo*/}
                <div className="d-inline-block align-middle">
                    <Image src={"/lolContent/emblems/Emblem_" + props.tier + ".png"} height={128} width={128}></Image>
                </div>
                <div className="d-inline-block align-middle">
                    <div>Ranked Solo</div>
                    <div>{props.tier}  {props.rank}</div>
                    <div>
                        <span>{props.leaguePoints}LP - </span>
                        <span>
                            <span>{props.wins}W </span>
                            <span>{props.losses}L </span>
                            <br></br>
                            <span>{(props.wins / (props.losses + props.wins) * 100).toFixed(1)}  Win rate </span>
                        </span>
                    </div>
                </div>
            </>
        )
    } else {
        return (
            <div>
                <div className="d-inline-block align-middle">
                    <Image src={"/lolContent/emblems/Emblem_Provisional.png"} height={128} width={128}></Image>
                </div>
                <div className="d-inline-block align-middle">
                    <div>Ranked Solo</div>
                    <div>Uranked</div>
                </div>
            </div>
        )
    }
}
function FLEX(props) {
    // If FLEX rank isset
    if (Object.entries(props).length !== 0) {
        return (
            <div>
                <div className="d-inline-block align-middle">
                    <Image src={"/lolContent/emblems/Emblem_" + props.tier + ".png"} height={128} width={128}></Image>
                </div>
                <div className="d-inline-block align-middle">
                    <div>Ranked Flex</div>
                    <div>{props.tier}  {props.rank}</div>
                    <div>
                        <span>{props.leaguePoints}LP - </span>
                        <span>
                            <span>{props.wins}W </span>
                            <span>{props.losses}L </span>
                            <br></br>
                            <span>{(props.wins / (props.losses + props.wins) * 100).toFixed(1)}  Win rate </span>
                        </span>
                    </div>
                </div>
            </div>
        )
    }
    else {
        return (
            <div>
                {/* If the summoner doesnt have a rank we display an empty border */}
                <div className="d-inline-block align-middle">
                    <Image src={"/lolContent/emblems/Emblem_Provisional.png"} height={128} width={128}></Image>
                </div>
                <div className="d-inline-block align-middle">
                    <div>Ranked Flex</div>
                    <div>Unranked</div>
                </div>
            </div>
        )
    }
}

function CreateGamesList(props) {
    let game = props.gamesById.map((match, i) => {
        // Find the summoner we are looking for
        return (
            <CreateGame runes={props.runes} key={i} match={match} summoner={props.summoner} champions={props.champions} summonerSpells={props.summonerSpells} items={props.items} />
        )
    })
    return game
}
