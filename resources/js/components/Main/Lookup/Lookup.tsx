import * as React from 'react';
import { RouteComponentProps, match } from 'react-router-dom';
import axios, { AxiosResponse } from 'axios';

interface LookupProps {
    match: match<{name:string, region:string, champion:string}>
}

interface LookupState {

}

export class Lookup extends React.Component<LookupProps, LookupState> {
    constructor(props:any) {
        super(props)
        this.state = {
        }
        console.log("Lookup",props)
    }

    componentDidMount() {
        // https://euw1.api.riotgames.com/lol/match/v4/matchlists/by-account/${this.state.id}?champion=${this.state.championId}&api_key=${this.state.api}
        axios.post('/api/lookup', {
            region: this.props.match.params.region,
            name: this.props.match.params.name,
            champion: this.props.match.params.champion,
        }).then((response) => {
            console.log(response)
        })


    }

    render() {
        return (
            <div>aaa</div>
        ) 
    }
    
}