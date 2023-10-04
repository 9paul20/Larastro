export interface Permission {
    current_page: number;
    data: Datum[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: Link[];
    next_page_url: null;
    path: string;
    per_page: number;
    prev_page_url: null;
    to: number;
    total: number;
}

export interface Datum {
    guard_name: GuardName;
    id: number;
    name: string;
}

export enum GuardName {
    Web = "web",
}

export interface Link {
    active: boolean;
    label: string;
    url: null | string;
}
