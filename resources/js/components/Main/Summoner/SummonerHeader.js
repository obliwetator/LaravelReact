import React from "react";
import Image from 'react-bootstrap/Image'

export default function SummonerHeader(props) {
    return (
        <div className="position-relative">
            <div className="d-inline-block">
                <div className="position-relative" >
                    <Image src={"/lolContent/img2/profileicon/" + props.icons.data[props.summoner.profileIconId].image.full} alt="Rank icon"></Image>
                    <span className="position-absolute" id="level"></span>
                </div>
            </div>
            <div className="d-inline-block">
                <div>
                    <span id="name">{props.summoner.name}</span>
                    <div id="Ranks">some rank</div>
                </div>
                <div id="Buttons"></div>
            </div>
        </div>
    )
}