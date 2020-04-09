import * as React from "react";
import Image from 'react-bootstrap/Image'

import { SummonerHeaderProps, SummonerHeaderState } from "../../ReactInterfaces/RootInterface";
import { Button, Spinner, Alert } from "react-bootstrap";

export default class SummonerHeader extends React.Component<SummonerHeaderProps, SummonerHeaderState> {
    constructor(props: SummonerHeaderProps) {
        super(props)
    }

    render() {
    return (
        <div className="position-relative">
            <div className="d-inline-block">
                <div className="position-relative" >
                    <Image src={"/lolContent/img2/profileicon/" + this.props.icons.data[this.props.summoner.profileIconId].image.full} alt="Rank icon"></Image>
                    <span className="position-absolute" id="level"></span>
                </div>
            </div>
            <div className="d-inline-block ml-2">
                <div>
                    <span id="name">{this.props.summoner.name}</span>
                    <div id="Ranks">some rank</div>
                </div>
                <div>
                    <Button onClick={this.props.action} className="text-center" disabled={this.props.IsLoading}>
                        {this.props.IsLoading && (
                            <i className="spinner-border text-warning spinner-border-sm" style={{ marginRight: "5px" }}></i>
                        )}
                        Refresh            
                    </Button> 
                </div>
            </div>
            {/* We handle different error codes here? */}
            {this.props.code! !== null && this.props.code! == 0 && 
                <Alert className="d-inline-block" variant="danger" show={this.props.isVisible}>Wait for next refresh</Alert>
            }
            {this.props.code! !== null && this.props.code! == 1 && 
                <Alert className="d-inline-block" variant="primary" show={this.props.isVisible}>Your games are up to date</Alert>
            }
            {this.props.code! == null && 
                <Alert className="d-inline-block" variant="danger" show={this.props.isVisible}>
                    An unknown error occured. Riot's API might be unresponsive. 
                    <br></br>
                    Please try again later
                </Alert>
            }
        </div>
        
    )
    }
}
