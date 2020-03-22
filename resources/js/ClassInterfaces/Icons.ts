export interface Icons {
    type:    string;
    version: string;
    data:    { [key: string]: Datum };
}

export interface Datum {
    id:    number;
    image: Image;
}

export interface Image {
    full:   string;
    sprite: string;
    group:  string;
    x:      number;
    y:      number;
    w:      number;
    h:      number;
}
