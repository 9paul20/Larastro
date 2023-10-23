export interface Permission {
    current_page: number;
    data: DatumPermission[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: LinkPermission[];
    next_page_url: null;
    path: string;
    per_page: number;
    prev_page_url: null;
    to: number;
    total: number;
}

export interface DatumPermission {
    description: string;
    guard_name: GuardNamePermission;
    id: number;
    name: string;
    tags: TagPermission[];
}

export enum GuardNamePermission {
    Web = "web",
}

export interface TagPermission {
    id: number;
    name: string;
    pivot: PivotPermission;
}

export interface PivotPermission {
    model_id: number;
    model_type: ModelTypePermission;
    tag_id: number;
}

export enum ModelTypePermission {
    AppModelsCustomPermission = "App\\Models\\CustomPermission",
}

export interface LinkPermission {
    active: boolean;
    label: string;
    url: null | string;
}
