import * as React from 'react'
import axios, { AxiosResponse } from 'axios';

import { LiveGameProps, LiveGameState, LiveGameContentProps, TableContentProps } from "../../../ReactInterfaces/RootInterface";
import { ActiveGame, Participant } from '../../../../ClassInterfaces/ActiveGame';
import { SummonerSpell, Runes, ChampionImage, TierImage } from '../../Common/ImageComponents';
import { Summoner } from '../../../../ClassInterfaces/Summoner';

export default class LiveGame extends React.Component<LiveGameProps, LiveGameState> {
    constructor(props: LiveGameProps) {
        super(props);

        this.state = {
            isLiveGameLoaded: false,
            activeGame: null,
            summonerLeagues: null,
            isLoaded: false,
        }
    }

    componentDidMount() {
        // if (!this.state.isLiveGameLoaded) {
        //   this.setState({
        //       isLiveGameLoaded: true
        //   })  
        // }
    }

    componentDidUpdate() {
        // When we click on the live game tab we try to get the live game
        // After the call is made we set the loaded to true adn the webpage can render
        if (this.props.tabs.toString() === "LiveGame" && !this.state.isLoaded) {
            this.GetLiveGame()
        }
        // When we click any other tab we reset the live game
        // Any new click will get a new game request
        if (this.props.tabs.toString() !== "LiveGame" && this.state.isLoaded) {
            this.setState({
                isLoaded: false,
                activeGame: null,
            })
        }
    }

    getSummonerLeagues = (summoners: Participant[]) => {
        axios.post('/api/getLeagues', {
            region: this.props.region,
            summoners: summoners
        })
        .then((response) => {
            // handle success
            this.setState({
                summonerLeagues: response.data,
                isLoaded: true
            })
        })
        .catch((error) => {
            // handle error
        })
    }
    
    GetLiveGame = () => {
        axios.post('/api/getSummonerLiveGame', {
            region: this.props.region,
            summonerId: this.props.summoner.id
        })
        .then((response: AxiosResponse<ActiveGame>) => {
            this.getSummonerLeagues(response.data.participants)
            // handle success
            this.setState({
                activeGame: response.data,
            })
        })
        .catch((error) => {
            // handle error
        })
    }

    render() {
        if (this.props.tabs.toString() == "LiveGame") {
            if (this.state.isLoaded) {
                if (this.state.activeGame && this.state.summonerLeagues) {
                    return (
                        <LiveGameContent
                            activeGame={this.state.activeGame}
                            runes={this.props.runes}
                            summonerSpells={this.props.summonerSpells}
                            champions={this.props.champions}
                            summonerLeague={this.state.summonerLeagues}
                        />
                    )
                }
                else {
                    return (
                        <>
                            <div>This summoner isn't playing a game right now</div>
                            <div>(bot games cannot be spectatcted)</div>
                        </>
                    )
                }
            } else {
                return (
                    // Loading animation
                    <div className="spinner">
                        <div className="rect1"></div>
                        <div className="rect2"></div>
                        <div className="rect3"></div>
                        <div className="rect4"></div>
                        <div className="rect5"></div>
                    </div>
                )
            }
        }
        else {
            return null
        }

    }
}

function LiveGameContent(props: LiveGameContentProps) {
    const activeGame = props.activeGame
    return (
        <div className="Spectate box">
            <div className="container-fluid">
                <div className="Title">
                    <div className="d-inline-block" >{activeGame.gameMode}</div>
                    <br></br>
                    <div className="d-inline-block">{activeGame.gameQueueConfigId}</div>
                    <br></br>
                    <div className="d-inline-block">{activeGame.gameStartTime}</div>
                </div>

                <div className="Content">
                    <table className="Team 100">
                        <ColGroup  {...props.activeGame} />

                        <TableHead {...props.activeGame} />

                        <TableContent
                            activeGame={props.activeGame}
                            runes={props.runes}
                            summonerSpells={props.summonerSpells}
                            summonerLeague={props.summonerLeague}
                            champions={props.champions}
                        />
                    </table>
                    <table className="Team 200">
                        <ColGroup  {...props.activeGame} />

                        <TableHead {...props.activeGame} />
                    </table>
                </div>
            </div>
        </div>
    )
}

function ColGroup(props: ActiveGame) {
    return (
        <colgroup>
            <col className="color thingy"></col>
            <col className="champ image"></col>
            <col className="summoner spell"></col>
            <col className="runes"></col>
            <col className="name"></col>
            <col className="ranked icon"></col>
            <col className="LP"></col>
            <col className="ranked wr"></col>
            <col className="current season info"></col>
            <col className="current season info"></col>
            <col className="previous rank image"></col>
            <col className="tier average"></col>
            { typeof props.bannedChampions !== 'undefined' &&
                <col className="bans"></col>
            }
        </colgroup>
    )
}

function TableHead(props: ActiveGame) {
    return (
        <thead>
            <tr>
                <th className="bg-primary"></th>
                <th colSpan={4}>Blue/red Team</th>
                <th colSpan={2}>The Season ranked</th>
                <th>Ranekd Winratio</th>
                <th colSpan={2}>The season stats</th>
                <th>Previous season rank</th>
                <th>Tier Average</th>
                {/* If games is ranked(has bans) add this */}
                { typeof props.bannedChampions !== 'undefined' &&
                    <th>Bans</th>
                }
            </tr>
        </thead>
    )
}

function TableContent(props: TableContentProps) {
    let body = props.activeGame.participants.map((participant, i) => {
        return(
             // LOOP dependign on summoner ammount
             <tr id="s">
                <td colSpan={3}>
                    {/* Champion Image/link */}
                    <a href={"/champions/" + props.champions.data[props.activeGame.participants[i].championId].id + "/statistics"} target="_blank">
                        <ChampionImage />
                    </a>
                </td>
                <td className="Summoner Spell">
                    <SummonerSpell />
                    <SummonerSpell />
                </td>
                <td className="Runes">
                    <div className="Rune">
                        {/* Zero index is Keystone */}
                        <Runes/>
                    </div>
                    <div className="Rune">
                        <Runes/>
                    </div>
                </td>
                <td className="Name">
                    <div className="SummonerName">
                        <a></a>
                    </div>
                </td>
                <td className="Tier icon">
                    {/* {props.summonerLeague[i] ? <TierImage /> : null} */}

                </td>
                <td className="Tier/level">
                {/* {props.summonerLeague[i]["RANKED_SOLO_5x5"] ? */}
                    {/* props.summonerLeague[i]["RANKED_SOLO_5x5"].tier + " " + props.summonerLeague[i]["RANKED_SOLO_5x5"].rank : null} */}

                </td>
                <td className="Ranked WR">

                </td>
                <td className="Season info">
                    need champion stats
                </td>
                <td className="Season info">
                    need champion stats
                </td>
                <td className="Last season">
                    Last season rank(image)
                </td>
                <td className="Detailed runes">
                    <button className="btn btn-primary" onClick={() => alert("fix me")}>Runes</button>
                </td>
                <td className="Bans">

                </td>
            </tr>
        )
    })
    return (
        <tbody>
            
		</tbody>
    )
}
