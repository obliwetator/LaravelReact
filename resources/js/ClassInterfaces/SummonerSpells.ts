export interface SummonerSpells {
    version: string;
    type:    string;
    data:    { [key: string]: Datum };
}

export interface Datum {
    id:            string;
    name:          string;
    description:   string;
    tooltip:       string;
    maxrank:       number;
    cooldown:      number[];
    cooldownBurn:  string;
    cost:          number[];
    costBurn:      string;
    effect:        Array<number[] | null>;
    vars:          Var[] | null;
    image:         Image;
    effectBurn:    Array<null | string>;
    rangeBurn:     string;
    key:           string;
    leveltip:      null;
    modes:         string[];
    resource:      Resource;
    costType:      CostType;
    range:         number[];
    summonerLevel: number;
}

export enum CostType {
    S = "s",
    SICooldown = "s %i:cooldown%",
}

export interface Image {
    full:   string;
    group:  Group;
    sprite: Sprite;
    h:      number;
    w:      number;
    y:      number;
    x:      number;
}

export enum Group {
    Spell = "spell",
}

export enum Sprite {
    Spell0PNG = "spell0.png",
}

export enum Resource {
    CooldownSICooldown = "{{ cooldown }}s %i:cooldown%",
    ICooldownModifiedcooldownS = "%i:cooldown% {{ modifiedcooldown }}s",
}

export interface Var {
    link:  string;
    coeff: number[] | number;
    key:   string;
}
