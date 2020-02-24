import React, { Component } from "react";
import Image from 'react-bootstrap/Image'

export function SummonerSpell(props) {
    return (
        <div className="Spell">
            <Image height={32} width={32} src={"/lolContent/img2/spell/" + props.Id + ".png"} />
        </div>
    )
}

export function Runes(props) {
    return (  
        <div className="Rune">
            {/* Already has the path suffix */}
            <Image height={20} width={20} src={"/lolContent/img/" + props.Id} />
        </div>
    )
}

export function Item(props) {
    return (
        <div className="Item">
            <Image height={22} width={22} src={"/lolContent/img2/item/" + props.Id + ".png"} />
        </div>
    )
}

export function ChampionImage(props) {
    return (
        <div className="ChampionImage d-inline-block" style={{ verticalAlign: 'middle' }}>
            <a href={"/champions/" + props.Id + "/statistics"} target="_blank">
                <Image height={64} width={64} src={"/lolContent/img2/champion/" + props.Id + ".png"} />
            </a>
        </div>
    )
}