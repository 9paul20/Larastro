import axios from "axios";
import { defineStore } from "pinia";
import type { Permission } from "../interfaces";
import { DatumPermission } from "@js/interfaces/Permissions/Permission";

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
    const storePermission = async (permission: DatumPermission): Promise<DatumPermission> => {
        try {
            const resp = await axios.post<DatumPermission>('http://localhost:8000/api/permissions', permission);
            return resp.data as DatumPermission;
        } catch (error) {
            return error as any;
        }
    };
    //
    const updatePermission = async (permission: DatumPermission): Promise<DatumPermission> => {
        try {
            const resp = await axios.put<DatumPermission>(`http://localhost:8000/api/permissions/${permission.id}`, permission);
            return resp.data as DatumPermission;
        } catch (error) {
            return error as any;
        }
    };
    //
    const destroyPermission = async (permission: DatumPermission): Promise<DatumPermission> => {
        try {
            const resp = await axios.delete<DatumPermission>(`http://localhost:8000/api/permissions/${permission.id}`);
            return resp.data as DatumPermission;
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