import React, { Component } from "react";

export default class IndividualGame extends Component{
    constructor(props){
        super(props)

        this.state = {

        }
    }

    componentDidMount() {

    }

    componentDidUpdate() {

    }

    render() {
        if (condition) {
            return <IndividualGameContent/>;
        }
    }
}

function IndividualGameContent(props) {
    return (
        <div class="MatchDetailLayout">
	<div class="MatchDetailHeader">
		<ul class="nav nav-tabs">
			<li class="test nav-link active" data-tab-show-class="Overview">
				<a href>Overview</a>
			</li>
			<li class="test nav-link" data-tab-show-class="TeamAnalysis">
				<a href>Team analysis</a>
			</li>
			<li class="test nav-link" data-tab-show-class="Builds">
				<a href>Builds</a>
			</li>
			<li class="test nav-link" data-tab-show-class="etc">
				<a href>etc</a>
			</li>
		</ul>
	</div>
	<div class="MatchDetailContent tab-content"> 
		{/* Those divs correspond to the <ul> above */}
		<div class="Overview tab-pane active">
					<div class="GameDetailWrap">
				    {/* The team that the searched summoner is search should always be in the first table. */}
					<table class="GameDetailTable Win">
						<colgroup>
							<col class="ChampionImage" style="width: 38px"></col>
							<col class="SummonerSpell" style="width: 38px"></col>
							{/* @isset($matchById[0]->participants[0]->stats->perk0) */}
							<col class="Runes" style="width: 38px"></col>
							{/* @endisset */}
							<col class="SummonerName" style="width: 140px"></col>
							<col class="Tier" style="width: 140px"></col>
							<col class="KDA" style="width: 80px"></col>
							<col class="Damage" style="width: 110px"></col>
							<col class="Ward" style="width: 60px"></col>
							<col class="CS" style="width: 60px"></col>
							<col class="Items" style="width: 200px"></col>
						</colgroup>
						<thead class="Header">
							<tr>
								{/* @if (isset($matchById[0]->participants[0]->stats->perk0)) */}
									<th colspan="4">
										<span>Victory?</span>
										<span>Team color</span>
									</th>
								{/* @else */}
									<th colspan="3">
										<span>Victory?</span>
										<span>Team color</span>
									</th>
								{/* @endif */}
								<th>Tier</th>
								<th>KDA</th>
								<th>Damage</th>
								<th>Wards</th>
								<th>CS</th>
								<th>Items</th>
							</tr>
						</thead>
						<tbody>
							{/* @for ($i = 0; $i < sizeof($matchById[0]->participants)/2; $i++) */}
								<tr>
									<td class="ChampionImage">
										<a href="/champions/{{ $champions->data[$matchById[0]->participants[$i]->championId]->name }}/statistics" target="_blank" style="position: relative;">
                                            <Image/>
											<div class="LevelTable" style="position: absolute"></div>
										</a>
									</td>
									<td class="SummonerSpell">
										<div class="Spell">	
                                            <Image/>
										</div>
										<div class="Spell" >
                                            <Image/>
                                        </div>
									</td>
									{/* Fix if game was not played with new rune system TOD */}
									{/* @isset($matchById[0]->participants[$i]->stats->perk0) */}
									<td class="Rune">
										<div class="Rune">
											{/* Zero index is Keystone */}
                                                <Image/>
											</div>
										<div class="Rune">
                                            <Image/>
                                        </div>
									</td>	
									{/* @endisset */}
									<td class="SummonerName">
										<a href='/summoner?name={{ $sumonerNameObj[0][$i]}}'></a>
									</td>
									<td class="Tier">
										{/* @if (isset($summonerLeague[0][$i]["RANKED_SOLO_5x5"])) */}
											<div></div>
										{/* @else */}
											<div>Unranked</div>
										{/* @endif */}
									</td>
									<td class="KDA">
										{/* @if ($matchById[0]->participants[$i]->stats->deaths == 0) */}
											<span class="KdaRation"></span> KDA
										{/* @else */}
											<span class="KdaRation"></span> KDA
										{/* @endif */}
									</td>
									<td class="Dammage">Dmg: </td>
									<td class="Wards">CW </td>
									<td class="CS">
										<div class="CS">
											
                                        </div>
										<div class="CSperMin">
											
                                        </div>
									 </td>
									<td class="ItemBlock">
										{/* @for ($j = 0; $j < 7; $j++) */}
										<div class="Item" style="display: inline-block">
                                            <Image/>											
                                        </div>
									</td>
								</tr>
							{/* @endfor */}
						</tbody>	
					</table>
					<div class="Summary">
							<hr class="bg-success"/>
								Here goes some summary that applies to both sides
							<hr class="bg-success"/>	
					</div>
					<table class="GameDetailTable Lose">
						<colgroup>
							<col class="ChampionImage" style="width: 38px"/>
							<col class="SummonerSpell" style="width: 38px"/>
							@isset($matchById[0]->participants[0]->stats->perk0)
							<col class="Runes" style="width: 38px"/>
							@endisset
							<col class="SummonerName" style="width: 140px"/>
							<col class="Tier" style="width: 140px"/>
							<col class="KDA" style="width: 80px"/>
							<col class="Damage" style="width: 110px"/>
							<col class="Ward" style="width: 60px"/>
							<col class="CS" style="width: 60px"/>
							<col class="Items" style="width: 200px"/>
						</colgroup>
						<thead class="Header">
							<tr>
								{/* @if (isset($matchById[0]->participants[0]->stats->perk0)) */}
									<th colspan="4">
										<span>Victory?</span>
										<span>Team color</span>
									</th>
								{/* @else */}
									<th colspan="3">
										<span>Victory?</span>
										<span>Team color</span>
									</th>
								{/* @endif */}
								<th>Tier</th>
								<th>KDA</th>
								<th>Damage</th>
								<th>Wards</th>
								<th>CS</th>
								<th>Items</th>
							</tr>
						</thead>
						<tbody>
							{/* @for ($i; $i < sizeof($matchById[0]->participants); $i++) */}
								<tr>
									<td class="ChampionImage">
										<a href="/champions/{{ $champions->data[$matchById[0]->participants[$i]->championId]->name }}/statistics" target="_blank" style="position: relative;">
                                            <Image/>
                                            <div class="LevelTable" style="position: absolute"></div>
										</a>
									</td>
									<td class="SummonerSpell">
										<div class="Spell">	
											<Image/>
                                        </div>
										<div class="Spell" >
										    <Image/>
                                        </div>
									</td>
									{/* @isset($matchById[0]->participants[$i]->stats->perk0) */}
									<td class="Rune">
										<div class="Rune">
											{/* Zero index is Keystone */}
											<Image/>
                                        </div>
										<div class="Rune">
											<Image/>
                                        </div>
									</td>	
									{/* @endisset */}
									<td class="SummonerName">
										<a href='/summoner?name={{ $sumonerNameObj[0][$i] }}'></a>
									</td>
									<td class="Tier">
										{/* @if (isset($summonerLeague[0][$i]["RANKED_SOLO_5x5"])) */}
											<div></div>
										{/* @else */}
											<div>Unranked</div>
										{/* @endif */}
									</td>
									<td class="KDA">
										{/* @if ($matchById[0]->participants[$i]->stats->deaths == 0) */}
											<span class="KdaRation"></span>KDA
										{/* @else */}
											<span class="KdaRation"></span>KDA
										{/* @endif */}
									</td>
									<td class="Dammage">Dmg: </td>
									<td class="Wards">CW </td>
									<td class="CS">
										<div class="CS">
											
										</div>
										<div class="CSperMin">
											
										</div>
									</td>
									<td class="ItemBlock">
										{/* @for ($j = 0; $j < 7; $j++) */}
										<div class="Item" style="display: inline-block">
										    <Image/>	
                                        </div>
									</td>
								</tr>
						</tbody>																			
					</table>
					</div>
		</div>
		<div class="TeamAnalysis tab-pane">
			<p>Team Analysis</p>
		</div>
		<div class="Builds tab-pane">
			<p>Builds</p>
		</div>
		<div class="etc tab-pane">
			<p>Etc</p>
		</div>
	</div>
</div>
    )
}