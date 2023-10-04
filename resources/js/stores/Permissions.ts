import axios from "axios";
import { defineStore } from "pinia";
import type { Permission } from "../interfaces";
import { Datum } from "@js/interfaces/Permissions/Permission";

export const permissionsStore = defineStore('permissionsStore', () => {
    //
    const getAllPermissions = async (): Promise<Permission> => {
        try {
            const resp = await axios.get<Permission>(`http://localhost:8000/api/permissions`);
            return resp.data as Permission;
        } catch (error) {
            return error as Permission;
        }
    };
    //
    const storePermission = async (permission: Datum): Promise<Datum> => {
        try {
            const resp = await axios.post<Datum>('http://localhost:8000/api/permissions', permission);
            return resp.data as Datum;
        } catch (error) {
            return error as any;
        }
    };
    //
    const updatePermission = async (permission: Datum): Promise<Datum> => {
        try {
            const resp = await axios.put<Datum>(`http://localhost:8000/api/permissions/${permission.id}`, permission);
            return resp.data as Datum;
        } catch (error) {
            return error as any;
        }
    };
    //
    const destroyPermission = async (permission: Datum): Promise<Datum> => {
        try {
            const resp = await axios.delete<Datum>(`http://localhost:8000/api/permissions/${permission.id}`);
            return resp.data as Datum;
        } catch (error) {
            return error as any;
        }
    };
    //
    const destroyPermissions = async (permissions: Array<object>): Promise<Array<object>> => {
        try {
            const resp = await axios.post<any>('http://localhost:8000/api/permissions/destroyMany', permissions);
            return resp.data as Array<object>;
        } catch (error) {
            return error as any;
        }
    };
    //
    const getCurrentPermissionId = async (): Promise<object> => {
        try {
            const resp = await axios.get<object>(`http://localhost:8000/api/permissions/getCurrentPermissionId`);
            return resp.data as object;
        } catch (error) {
            return error as any;
        }
    };
    //
    return {
        getAllPermissions,
        storePermission,
        updatePermission,
        destroyPermission,
        destroyPermissions,
        getCurrentPermissionId
    };
});