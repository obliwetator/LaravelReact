import * as React from 'react'

import IndividualGame from './IndividualGame'

import { ChampionImage, SummonerSpell, Runes, Item } from "../../Common/ImageComponents";
import {CreateGameProps, CreateGameState} from "../../../ReactInterfaces/RootInterface";

export default class CreateGame extends React.Component<CreateGameProps, CreateGameState> {
    constructor(props: CreateGameProps){
        super(props)
        this.state = {
            target: 0,
            ShowMore: "More",
            IsPressed: false,
            IsLoaded: false,
        }

    }

    componentDidMount() {
        
    }

    componentDidUpdate() {

    }

    TimeAgo = (function () {
        var self: any = {};

        // Public Methods
        self.locales = {
            prefix: '',
            sufix: 'ago',

            seconds: 'less than a minute',
            minute: 'about a minute',
            minutes: '%d minutes',
            hour: 'about an hour',
            hours: 'about %d hours',
            day: 'a day',
            days: '%d days',
            month: 'about a month',
            months: '%d months',
            year: 'about a year',
            years: '%d years'
        };

        self.inWords = function (timeAgo: number) {
            // TODO Potential * 1000 is not needed
            var seconds = Math.floor((new Date().getTime() * 1000 - timeAgo) / 1000),
                separator = this.locales.separator || ' ',
                words = this.locales.prefix + separator,
                interval = 0,
                intervals: {[key:string] : number} = {
                    year: seconds / 31536000,
                    month: seconds / 2592000,
                    day: seconds / 86400,
                    hour: seconds / 3600,
                    minute: seconds / 60
                }
                
            var inter 
            var distance = this.locales.seconds;

            for (var key in intervals) {
                interval = Math.floor(intervals[key]);

                if (interval > 1) {
                    distance = this.locales[key + 's'];
                    break;
                } else if (interval === 1) {
                    distance = this.locales[key];
                    break;
                }
            }

            distance = distance.replace(/%d/i, interval);
            words += distance + separator + this.locales.sufix;

            return words.trim();
        };

        return self;
    }());
    

    ShowDetails = (event : React.MouseEvent<HTMLButtonElement>) => {
        if (this.state.IsPressed) {
            this.setState({
                ShowMore: "More",
                IsPressed: false
            })
        }
        else{
            this.setState({
                ShowMore: "Less",
                IsPressed: true,
                IsLoaded: true,
            })
        }

    }
    render() {
        let GameDetails
        if (this.state.IsPressed && !this.state.IsLoaded) {
            GameDetails =                 
            <div className="GameDetails">
                <IndividualGame {...this.props.match}/>
            </div>    
        }
        this.props.match.participantIdentities.forEach((participantIdentities, j) => {
            if (participantIdentities.player.summonerId == this.props.summoner.id) {
                this.setState({target: j})
            }
        })
        return (
            <div className="GameItemWrap">
                <div>Game ID: {this.props.match.gameId} (temp)</div>
                <div>Summoner target {this.state.target} (temp)</div>
                {/* This is the preview before we load the details */}
                <div className="GameStats" style={{ display: 'table-cell' }}>
                    <div className="GameType">
                        {this.props.match.gameMode}
                    </div>
                    <div className="Timestamp">
                        {this.TimeAgo.inWords(this.props.match.gameCreation)}
                    </div>
                    <div className="Bar">
    
                    </div>
                    <div className="GameLength">
                        {(this.props.match.gameDuration / 60).toFixed(0)}m {this.props.match.gameDuration % 60}s
                    </div>
                </div>
                <div className="GameSettingsInfo" style={{ display: 'table-cell', verticalAlign: 'middle' }}>
                        <ChampionImage Id={this.props.champions.data[this.props.match.participants[this.state.target].championId].id} />
                    <div className="SummonerSpell d-inline-block" style={{ verticalAlign: 'middle' }}>
                        <SummonerSpell Id={this.props.summonerSpells.data[this.props.match.participants[this.state.target].spell1Id].id}/>

                        <SummonerSpell Id={this.props.summonerSpells.data[this.props.match.participants[this.state.target].spell2Id].id}/>
                    </div>
                    {/* Innactive accounts might not have played with the new rune system. If so dont display them at all */}
                    <div className="Runes d-inline-block">
                        <Runes Id={ this.props.runes[this.props.match.participants[this.state.target].stats.perk0].icon}/>
                        <Runes Id={ this.props.runes[this.props.match.participants[this.state.target].stats.perkSubStyle].icon}/>
                    </div>
                    <div className="ChampionName">
                        <a href="/champions/{{$champions->data[$champion[$key]]->name}}/statistics" target="_blank"></a>
                    </div>
                </div>
                <div className="KDA">
                    <div className="KDA">
                    </div>
                    <div className="KDARatio">
                    </div>
                </div>
                <div className="Stats">
                    <div className="Level">
                    </div>
                    <div className="CS">
                    </div>
    
                </div>
                <div className="Items">
                    <div className="ItemList">
                    </div>
                    <div className="ControllWards">
                    </div>
                </div>
                <div className="Participants">
                    {/* Left block  */}
                    <div className="Team">
    
                    </div>
    
                    <div className="Team">
                    </div>
                </div>
                <div className="Stats Button">
                    <div className="Content">
                        <div className="Item">
                            <span onClick={this.ShowDetails}>{this.state.ShowMore}</span>

                        </div>
                    </div>
                </div>
                <div className="GameDetails">
                    {GameDetails}
                </div>
                <hr className="bg-success"></hr>
            </div>
        )
    }
}