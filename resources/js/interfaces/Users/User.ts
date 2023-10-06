export interface User {
    current_page: number;
    data: DatumUser[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: LinkUser[];
    next_page_url: null;
    path: string;
    per_page: number;
    prev_page_url: null;
    to: number;
    total: number;
}

export interface DatumUser {
    email: string;
    id: number;
    name: string;
}

export interface LinkUser {
    active: boolean;
    label: string;
    url: null | string;
}
