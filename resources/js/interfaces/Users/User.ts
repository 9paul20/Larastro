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
    permissions: PermissionUser[];
    roles: RoleUser[];
}

export interface PermissionUser {
    description: string;
    guard_name: GuardName;
    id: number;
    name: string;
    pivot: PermissionPivot;
    tags: string[];
}

export enum GuardName {
    Web = "web",
}

export interface PermissionPivot {
    model_id: number;
    model_type: ModelType;
    permission_id: number;
}

export enum ModelType {
    AppModelsUser = "App\\Models\\User",
}

export interface LinkUser {
    active: boolean;
    label: string;
    url: null | string;
}

export interface RoleUser {
    description: string;
    guard_name: GuardName;
    id: number;
    name: string;
    pivot: RolePivot;
    tags: string[];
}

export interface RolePivot {
    model_id: number;
    model_type: ModelType;
    role_id: number;
}
