import axios from "axios";
import { defineStore } from "pinia";
import type { Role } from "../interfaces";
import { DatumRole } from "@js/interfaces/Roles/Role";

export const rolesStore = defineStore('rolesStore', () => {
    //
    const getAllRoles = async (): Promise<Role> => {
        try {
            const resp = await axios.get<Role>(`http://localhost:8000/api/roles`);
            return resp.data as Role;
        } catch (error) {
            return error as Role;
        }
    };
    //
    const storeRole = async (role: DatumRole): Promise<DatumRole> => {
        try {
            const resp = await axios.post<DatumRole>('http://localhost:8000/api/roles', role);
            return resp.data as DatumRole;
        } catch (error) {
            return error as any;
        }
    };
    //
    const updateRole = async (role: DatumRole): Promise<DatumRole> => {
        try {
            const resp = await axios.put<DatumRole>(`http://localhost:8000/api/roles/${role.id}`, role);
            return resp.data as DatumRole;
        } catch (error) {
            return error as any;
        }
    };
    //
    const destroyRole = async (role: DatumRole): Promise<DatumRole> => {
        try {
            const resp = await axios.delete<DatumRole>(`http://localhost:8000/api/roles/${role.id}`);
            return resp.data as DatumRole;
        } catch (error) {
            return error as any;
        }
    };
    //
    const destroyRoles = async (roles: Array<object>): Promise<Array<object>> => {
        try {
            const resp = await axios.post<any>('http://localhost:8000/api/roles/destroyMany', roles);
            return resp.data as Array<object>;
        } catch (error) {
            return error as any;
        }
    };
    //
    const getCurrentRoleId = async (): Promise<object> => {
        try {
            const resp = await axios.get<object>(`http://localhost:8000/api/roles/getCurrentRoleId`);
            return resp.data as object;
        } catch (error) {
            return error as any;
        }
    };
    //
    return {
        getAllRoles,
        storeRole,
        updateRole,
        destroyRole,
        destroyRoles,
        getCurrentRoleId
    };
});