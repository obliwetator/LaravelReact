import * as React from "react";
import Image from 'react-bootstrap/Image'

import { IndividualGameProps, IndividualGameState } from "../../../ReactInterfaces/RootInterface";

export default class IndividualGame extends React.Component<IndividualGameProps, IndividualGameState> {
	constructor(props: IndividualGameProps) {
		super(props)
		this.state = {
			IsLoaded: false,
		}
	}

	componentDidMount() {
	}

	componentDidUpdate() {
		if (this.props.IsPressed && !this.state.IsLoaded) {
			this.setState({
				IsLoaded: true
			})
		}
	}

	render() {
		let runes
		if (this.props.participants[0].stats.perk0) {
			runes = <col className="Runes" style={{ width: 38 }}></col>
		}
		else {
			runes = <></>
		}
		if (!this.state.IsLoaded) {
			return <></>
		}
		else {
			return (
				<div className="MatchDetailLayout" style={{ display: this.props.IsPressed ? 'block' : 'none' }}>
					<div className="MatchDetailHeader">
						<ul className="nav nav-tabs">
							<li className="test nav-link active">
								<span>Overview</span>
							</li>
							<li className="test nav-link">
								<span>Team analysis</span>
							</li>
							<li className="test nav-link" >
								<span>Builds</span>
							</li>
							<li className="test nav-link">
								<span>etc</span>
							</li>
						</ul>
					</div>
					<div className="MatchDetailContent tab-content">
						{/* Those divs correspond to the <ul> above */}
						<div className="Overview tab-pane active">
							<div className="GameDetailWrap">
								{/* The team that the searched summoner is search should always be in the first table. */}
								<table className="GameDetailTable Win">
									<colgroup>
										<col className="ChampionImage" style={{ width: 38 }}></col>
										<col className="SummonerSpell" style={{ width: 38 }}></col>
										{this.props.participants[0].stats.perk0 &&
											<col className="Runes" style={{ width: 38 }}></col>
										}
										<col className="SummonerName" style={{ width: 140 }}></col>
										<col className="Tier" style={{ width: 140 }}></col>
										<col className="KDA" style={{ width: 80 }}></col>
										<col className="Damage" style={{ width: 110 }}></col>
										<col className="Ward" style={{ width: 60 }}></col>
										<col className="CS" style={{ width: 38 }}></col>
										<col className="Items" style={{ width: 200 }}></col>
									</colgroup>
									<thead className="Header">
										<tr>
											{this.props.participants[0].stats.perk0 ?
												<th colSpan={4}>
													<span>Victory?</span>
													<span>Team color</span>
												</th>
												:
												<th colSpan={3}>
													<span>Victory?</span>
													<span>Team color</span>
												</th>
											}
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
											<td className="ChampionImage">
												<a href="/champions/{{ $champions->data[$matchById[0]->participants[$i]->championId]->name }}/statistics" target="_blank">
													<Image />
													<div className="LevelTable"></div>
												</a>
											</td>
											<td className="SummonerSpell">
												<div className="Spell">
													<Image />
												</div>
												<div className="Spell" >
													<Image />
												</div>
											</td>

											{this.props.participants[0].stats.perk0 &&
												<td className="Rune">
													<div className="Rune">
														{/* Zero index is Keystone */}
														<Image />
													</div>
													<div className="Rune">
														<Image />
													</div>
												</td>
											}
											<td className="SummonerName">
												<a href='/summoner?name={{ $sumonerNameObj[0][$i]}}'></a>
											</td>
											<td className="Tier">
												{/* @if (isset($summonerLeague[0][$i]["RANKED_SOLO_5x5"])) */}
												<div></div>
												{/* @else */}
												<div>Unranked</div>
												{/* @endif */}
											</td>
											<td className="KDA">
												{/* @if ($matchById[0]->participants[$i]->stats->deaths == 0) */}
												<span className="KdaRation"></span> KDA
									{/* @else */}
												<span className="KdaRation"></span> KDA
									{/* @endif */}
											</td>
											<td className="Dammage">Dmg: </td>
											<td className="Wards">CW </td>
											<td className="CS">
												<div className="CS">

												</div>
												<div className="CSperMin">

												</div>
											</td>
											<td className="ItemBlock">
												{/* @for ($j = 0; $j < 7; $j++) */}
												<div className="Item">
													<Image />
												</div>
											</td>
										</tr>
										{/* @endfor */}
									</tbody>
								</table>
								<div className="Summary">
									<hr className="bg-success" />
							Here goes some summary that applies to both sides
						<hr className="bg-success" />
								</div>
								<table className="GameDetailTable Lose">
									<colgroup>
										<col className="ChampionImage" style={{ width: 38 }}></col>
										<col className="SummonerSpell" style={{ width: 38 }}></col>
										{this.props.participants[0].stats.perk0 &&
											<col className="Runes" style={{ width: 38 }}></col>
										}
										<col className="SummonerName" style={{ width: 140 }}></col>
										<col className="Tier" style={{ width: 140 }}></col>
										<col className="KDA" style={{ width: 80 }}></col>
										<col className="Damage" style={{ width: 110 }}></col>
										<col className="Ward" style={{ width: 60 }}></col>
										<col className="CS" style={{ width: 38 }}></col>
										<col className="Items" style={{ width: 200 }}></col>
									</colgroup>
									<thead className="Header">
										<tr>
											{this.props.participants[0].stats.perk0 ?
												<th colSpan={4}>
													<span>Victory?</span>
													<span>Team color</span>
												</th>
												:
												<th colSpan={3}>
													<span>Victory?</span>
													<span>Team color</span>
												</th>
											}
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
											<td className="ChampionImage">
												<a href="/champions/{{ $champions->data[$matchById[0]->participants[$i]->championId]->name }}/statistics" target="_blank">
													<Image />
													<div className="LevelTable"></div>
												</a>
											</td>
											<td className="SummonerSpell">
												<div className="Spell">
													<Image />
												</div>
												<div className="Spell" >
													<Image />
												</div>
											</td>
											{this.props.participants[0].stats.perk0 &&
												<td className="Rune">
													<div className="Rune">
														{/* Zero index is Keystone */}
														<Image />
													</div>
													<div className="Rune">
														<Image />
													</div>
												</td>
											}
											<td className="SummonerName">
												<a href='/summoner?name={{ $sumonerNameObj[0][$i] }}'></a>
											</td>
											<td className="Tier">
												{/* @if (isset($summonerLeague[0][$i]["RANKED_SOLO_5x5"])) */}
												<div></div>
												{/* @else */}
												<div>Unranked</div>
												{/* @endif */}
											</td>
											<td className="KDA">
												{/* @if ($matchById[0]->participants[$i]->stats->deaths == 0) */}
												<span className="KdaRation"></span>KDA
									{/* @else */}
												<span className="KdaRation"></span>KDA
									{/* @endif */}
											</td>
											<td className="Dammage">Dmg: </td>
											<td className="Wards">CW </td>
											<td className="CS">
												<div className="CS">

												</div>
												<div className="CSperMin">

												</div>
											</td>
											<td className="ItemBlock">
												{/* @for ($j = 0; $j < 7; $j++) */}
												<div className="Item">
													<Image />
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div className="TeamAnalysis tab-pane">
							<p>Team Analysis</p>
						</div>
						<div className="Builds tab-pane">
							<p>Builds</p>
						</div>
						<div className="etc tab-pane">
							<p>Etc</p>
						</div>
					</div>
				</div>
			)
		}
	}
}