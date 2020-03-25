import * as React from "react";
import Image from 'react-bootstrap/Image'

import { SummonerHeaderProps } from "../../ReactInterfaces/RootInterface";
import { Button, Spinner } from "react-bootstrap";

export default class SummonerHeader extends React.Component<SummonerHeaderProps,{}> {
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
        </div>
    )
    }
}
