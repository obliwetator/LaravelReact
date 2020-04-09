import * as React from 'react'

import IndividualGame from './IndividualGame'

import { ChampionImage, SummonerSpell, Runes, Item } from "../../Common/ImageComponents";
import { CreateGameProps, CreateGameState } from "../../../ReactInterfaces/RootInterface";
import { GameByID } from '../../../../ClassInterfaces/GameById';
import { Items } from '../../../../ClassInterfaces/Items';

export default class CreateGame extends React.Component<CreateGameProps, CreateGameState> {
    constructor(props: CreateGameProps) {
        super(props)
        this.state = {
            ShowMore: "More",
            IsPressed: false,
            IsLoaded: false,
            IsComponentLoaded: false,
        }
    }

    componentDidMount() {
        // Find the id(index) of the summoner we are looking for
        // When we refresh for new game, the new games wont go through this function since they are already mounted
        // Only the new games will do it

    }

    componentDidUpdate(prevProps: CreateGameProps) {
        // When there is an update we just check if the previous game is same as the new
        // This will happen when we refresh the matchlist 
        // See above  

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
            var seconds = Math.floor((new Date().getTime() - timeAgo) / 1000),
                separator = this.locales.separator || ' ',
                words = this.locales.prefix + separator,
                interval = 0,
                intervals: { [key: string]: number } = {
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

    ShowDetails = (event: React.MouseEvent<HTMLButtonElement>) => {
        if (this.state.IsPressed) {
            this.setState({
                ShowMore: "More",
                IsPressed: false
            })
        }
        else {
            this.setState({
                ShowMore: "Less",
                IsPressed: true,
            })
        }

    }
    render() {
        let winner
        // TODO: SAVE THE STATE OF THE WIN/LOSS
        if (this.props.match.participants[this.props.target].teamId === 100) {
            // Left Team
            this.props.match.teams[0].win == "Win" ? winner = true : winner = false
        }
        else {
            // Right Team
            this.props.match.teams[1].win == "Win" ? winner = true : winner = false
        }

        let nameClass = winner ? "bg-primary" : "bg-danger"

        // TODO: Add custom style e.g style={{backgroundColor: "rgb(177, 212, 175)", color: "black"}} OR Create a color class
        return (
            <div className={"" + nameClass}>
                <div className="d-flex flex-row align-items-center">
                    <div>
                        <div className="">Game ID: {this.props.match.gameId} (temp)</div>
                        <div>Summoner target {this.props.target} (temp)</div>
                        <div className="GameType">
                            {this.props.match.gameMode}
                        </div>
                        <div className="Timestamp">
                            {this.TimeAgo.inWords(this.props.match.gameCreation)}
                        </div>
                        <div className="GameLength">
                            {Math.floor(this.props.match.gameDuration / 60)}m {this.props.match.gameDuration % 60}s
                    </div>
                    </div>

                    <div className="pr-1 pl-1">
                        <ChampionImage Id={this.props.champions.data[this.props.match.participants[this.props.target].championId].id} />
                    </div>
                    <div className="pr-1">
                        <SummonerSpell Description={this.props.summonerSpells.data[this.props.match.participants[this.props.target].spell1Id].description} Id={this.props.summonerSpells.data[this.props.match.participants[this.props.target].spell1Id].id} />

                        <SummonerSpell Description={this.props.summonerSpells.data[this.props.match.participants[this.props.target].spell2Id].description} Id={this.props.summonerSpells.data[this.props.match.participants[this.props.target].spell2Id].id} />
                    </div>
                    {/* Innactive accounts might not have played with the new rune system. If so dont display them at all */}
                    <div className="pr-1">
                        <Runes Description={this.props.runes![this.props.match.participants[this.props.target].stats.perk0].longDesc} Id={this.props.runes![this.props.match.participants[this.props.target].stats.perk0].icon} />
                        {/* There are no description for the perk style. Just send the name of the tree */}
                        <Runes Description={this.props.runes![this.props.match.participants[this.props.target].stats.perkSubStyle].key} Id={this.props.runes![this.props.match.participants[this.props.target].stats.perkSubStyle].icon} />
                    </div>
                    <div className="ChampionName">
                        <a href="/champions/{{$champions->data[$champion[$key]]->name}}/statistics" target="_blank"></a>
                    </div>
                    <div className="KDA">
                        <div className="KDA">
                            1/1/1
                    </div>
                        <div className="KDARatio">
                            ratio
                    </div>
                    </div>
                    <div className="Stats">
                        <div className="Level">
                            target champ level
                    </div>
                        <div className="CS">
                            CS (per/min)
                    </div>
                        <div>
                            KP
                    </div>
                        <div>
                            Averrage tier
                    </div>

                    </div>
                    <div className="Items" style={{ width: "5em" }}>
                        <div className="ItemList d-flex flex-wrap">
                            <ItemList match={this.props.match} items={this.props.items} target={this.props.target} />
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
                </div>
                <div className="d-flex flex-col   ">
                    <div>
                        {/* The game details are rendered on click and will stay mounted until the page refreshes */}
                        <IndividualGame IsPressed={this.state.IsPressed} {...this.props.match} />
                    </div>
                    <hr className="bg-success"></hr>
                </div>
            </div>
        )
    }
}

interface ItemListProps {
    match: GameByID
    items: Items
    target: number
}

function ItemList(props: ItemListProps) {

    let itemIndex: string
    let item = []
    for (let index = 0; index < 7; index++) {
        itemIndex = `item${index}`
        item.push(props.match.participants[props.target].stats[itemIndex])
    }
    let Items = item.map((name, index) => {
        return <Item key={index} Id={name} Description={props.items.data[name] ? props.items.data[name].description : null} />
    })

    return (
        <>
            {Items}
        </>
    )
}   