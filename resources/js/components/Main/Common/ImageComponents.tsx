import * as React from "react";
import Image from 'react-bootstrap/Image'
import { OverlayTrigger } from "react-bootstrap";
import { Tooltip } from "react-bootstrap";
import parse from 'html-react-parser';

import { connect, useDispatch, createDispatchHook, ConnectedProps, useStore } from 'react-redux';
import { RootStateRedux } from "../../redux";

interface ImageComponents {
    Id: string
    Description?: string | null
}
// TODO: Version may not be set since we the images are loaded before the set is set right now.
// Delete after it's fixed
function GetVersion(): string {
    const store = useStore<RootStateRedux>()
    const state = store.getState()
    if (state.summonerReducer.summoner.version) {
        return state.summonerReducer.summoner.version.GameVersion
    }
    return ""
}

// The description need to be parsed with an HTML parser since react is quite picky about it.
export function SummonerSpell(props: ImageComponents) {
    const version = GetVersion()

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
                <Image height={32} width={32} src={`/lolContent/${version}/${version}/img/spell/` + props.Id + ".png"} />
            </OverlayTrigger>

        </div>
    )
}

export function Runes(props: ImageComponents) {
    const version = GetVersion()

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
                <Image height={32} width={32} src={`/lolContent/${version}/img/` + props.Id} />
            </OverlayTrigger>


        </div>
    )
}

export function Item(props: ImageComponents) {
    const version = GetVersion()

    return (
        <div className="Item">
            {props.Description ?
                <OverlayTrigger
                    key="top"
                    placement="top"
                    overlay={
                        <Tooltip id={"hey"}>
                            {props.Description &&
                                parse(props.Description!)}
                        </Tooltip>
                    }
                >
                    <Image height={22} width={22} src={`/lolContent/${version}/${version}/img/item/` + props.Id + ".png"} />
                </OverlayTrigger>
                :
                <Image height={22} width={22} className="noImage" />
            }
        </div >
    )
}

export function ChampionImage(props: any) {
    const version = GetVersion()
    
    return (
        <div className="ChampionImage d-inline-block" style={{ verticalAlign: 'middle' }}>
            <a href={"/champions/" + props.Id + "/statistics"} target="_blank">
                <Image height={64} width={64} src={`/lolContent/${version}/${version}/img/champion/` + props.Id + ".png"} />
            </a>
        </div>
    )
}

export function TierImage(props: ImageComponents) {
    return (
        <div className="d-inline-block align-middle">
            <Image src={"/lolContent/emblems/Emblem_" + props.Id + ".png"} height={128} width={128}></Image>
        </div>
    )
}

export function ProvisionalTierImage() {
    return (
        <div className="d-inline-block align-middle">
            <Image src={"/lolContent/emblems/Emblem_Provisional.png"} height={128} width={128}></Image>
        </div>
    )
}

export function ProfileImage(props: ImageComponents) {
    const version = GetVersion()

    return (
        <div className="d-inline-block">
            <div className="position-relative" >
                <Image src={`/lolContent/${version}/${version}/img/profileicon/` + props.Id} alt="Rank icon"></Image>
                <span className="position-absolute" id="level"></span>
            </div>
        </div>
    )
}