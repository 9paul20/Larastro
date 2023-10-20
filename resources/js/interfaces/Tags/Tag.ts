export interface Tag {
    current_page: number;
    data: DatumTag[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: LinkTag[];
    next_page_url: null;
    path: string;
    per_page: number;
    prev_page_url: null;
    to: number;
    total: number;
}

export interface DatumTag {
    description: string;
    id: number;
    name: string;
}

export interface LinkTag {
    active: boolean;
    label: string;
    url: null | string;
}
