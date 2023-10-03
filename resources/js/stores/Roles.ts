import axios from "axios";
import { defineStore } from "pinia";
import type { Role } from "../interfaces";
import { Datum } from "@js/interfaces/Roles/Role";

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
    const storeRole = async (role: Datum): Promise<Datum> => {
        try {
            const resp = await axios.post<Datum>('http://localhost:8000/api/roles', role);
            return resp.data as Datum;
        } catch (error) {
            return error as any;
        }
    };
    //
    const updateRole = async (role: Datum): Promise<Datum> => {
        try {
            const resp = await axios.put<Datum>(`http://localhost:8000/api/roles/${role.id}`, role);
            return resp.data as Datum;
        } catch (error) {
            return error as any;
        }
    };
    //
    const destroyRole = async (role: Datum): Promise<Datum> => {
        try {
            const resp = await axios.delete<Datum>(`http://localhost:8000/api/roles/${role.id}`);
            return resp.data as Datum;
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