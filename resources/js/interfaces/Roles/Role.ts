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
    guard_name: GuardNameRole;
    id: number;
    name: string;
    permissions: PermissionRole[];
    tags: TagRole[];
}

export enum GuardNameRole {
    Web = "web",
}

export interface PermissionRole {
    id: number;
    name: string;
    pivot: PermissionPivotRole;
}

export interface PermissionPivotRole {
    permission_id: number;
    role_id: number;
}

export interface TagRole {
    id: number;
    name: string;
    pivot: TagPivotRole;
}

export interface TagPivotRole {
    model_id: number;
    model_type: string;
    tag_id: number;
}

export interface LinkRole {
    active: boolean;
    label: string;
    url: null | string;
}
