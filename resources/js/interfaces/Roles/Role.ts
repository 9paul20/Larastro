export interface Role {
    current_page: number;
    data: DatumRole[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: LinkRole[];
    next_page_url: null;
    path: string;
    per_page: number;
    prev_page_url: null;
    to: number;
    total: number;
}

export interface DatumRole {
    description: string;
    guard_name: string;
    id: number;
    name: string;
    tags: string[];
}

export enum GuardNameRole {
    Web = "web",
}

export interface LinkRole {
    active: boolean;
    label: string;
    url: null | string;
}
