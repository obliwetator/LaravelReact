import * as React from "react";
import Image from 'react-bootstrap/Image'
import { OverlayTrigger } from "react-bootstrap";
import { Tooltip } from "react-bootstrap";
import parse from 'html-react-parser';

import { createPopper } from '@popperjs/core';

interface ImageComponents {
    Id: string
    Description?: string | null
}

// The description need to be parsed with an HTML parser since react is quite picky about it.
export function SummonerSpell(props: ImageComponents) {
    return (
        <div className="Spell">
            <OverlayTrigger
                key="top"
                placement="top"
                overlay={
                    <Tooltip id={"hey"}>
                        {parse(props.Description!)}
                    </Tooltip>
                }
            >
                <Image height={32} width={32} src={"/lolContent/img2/spell/" + props.Id + ".png"} />
            </OverlayTrigger>

        </div>
    )
}

export function Runes(props: ImageComponents) {
    return (
        <div className="Rune">
            <OverlayTrigger
                key="top"
                placement="top"
                overlay={
                    <Tooltip id={"hey"}>
                        {parse(props.Description!)}
                    </Tooltip>
                }
            >
                {/* Already has the path suffix */}
                <Image height={32} width={32} src={"/lolContent/img/" + props.Id} />
            </OverlayTrigger>


        </div>
    )
}

export function Item(props: ImageComponents) {
    return (
        <div className="Item">
            {props.Description ? 
            <OverlayTrigger
                key="top"
                placement="top"
                overlay={
                    <Tooltip id={"hey"}>
                        { props.Description &&
                        parse(props.Description!)}
                    </Tooltip>
                }
            >
                <Image height={22} width={22} src={"/lolContent/img2/item/" + props.Id + ".png"} />
            </OverlayTrigger>
            :
                <Image height={22} width={22} className="noImage" />
            }
        </div >
    )
}

export function ChampionImage(props: any) {
    return (
        <div className="ChampionImage d-inline-block" style={{ verticalAlign: 'middle' }}>
            <a href={"/champions/" + props.Id + "/statistics"} target="_blank">
                <Image height={64} width={64} src={"/lolContent/img2/champion/" + props.Id + ".png"} />
            </a>
        </div>
    )
}

export function TierImage(props: ImageComponents) {
    return (
        <div>Fix me Tier image</div>
    )
}