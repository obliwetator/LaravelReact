export interface Champions {
    keys:    { [key: string]: number };
    data:    { [key: string]: Datum };
    version: string;
    type:    Type;
    format:  string;
}

export interface Datum {
    id:          string;
    key:         string;
    name:        string;
    title:       string;
    image:       Image;
    skins:       null;
    lore:        null;
    blurb:       string;
    allytips:    null;
    enemytips:   null;
    tags:        Tag[];
    partype:     string;
    info:        Info;
    stats:       { [key: string]: number };
    spells:      null;
    passive:     null;
    recommended: null;
}

export interface Image {
    full:   string;
    sprite: Sprite;
    group:  Type;
    x:      number;
    y:      number;
    w:      number;
    h:      number;
}

export enum Type {
    Champion = "champion",
}

export enum Sprite {
    Champion0PNG = "champion0.png",
    Champion1PNG = "champion1.png",
    Champion2PNG = "champion2.png",
    Champion3PNG = "champion3.png",
    Champion4PNG = "champion4.png",
}

export interface Info {
    attack:     number;
    defense:    number;
    magic:      number;
    difficulty: number;
}

export enum Tag {
    Assassin = "Assassin",
    Fighter = "Fighter",
    Mage = "Mage",
    Marksman = "Marksman",
    Support = "Support",
    Tank = "Tank",
}
