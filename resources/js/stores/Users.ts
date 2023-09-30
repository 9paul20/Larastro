import axios from "axios";
import { defineStore } from "pinia";
import type { User } from "../interfaces";
import { Datum } from "@js/interfaces/User";

export const usersStore = defineStore('usersStore', () => {
    //
    const getAllUsers = async (): Promise<User> => {
        try {
            const resp = await axios.get<User>(`http://localhost:8000/api/users`);
            return resp.data;
        } catch (error) {
            return error as any;
        }
    };
    //
    const storeUser = async (user: Datum): Promise<Datum> => {
        try {
            const resp = await axios.post<Datum>('http://localhost:8000/api/users', user);
            return resp.data;
        } catch (error) {
            return error as any;
        }
    };
    //
    const updateUser = async (user: Datum): Promise<Datum> => {
        try {
            const resp = await axios.put<Datum>(`http://localhost:8000/api/users/${user.id}`, user);
            return resp.data;
        } catch (error) {
            return error as any;
        }
    };
    //
    const destroyUser = async (user: Datum): Promise<Datum> => {
        try {
            const resp = await axios.delete<Datum>(`http://localhost:8000/api/users/${user.id}`);
            return resp.data;
        } catch (error) {
            return error as any;
        }
    };
    //
    const getCurrentUserId = async (): Promise<object> => {
        try {
            const resp = await axios.get<object>(`http://localhost:8000/api/users/getCurrentUserId`);
            return resp.data;
        } catch (error) {
            return error as any;
        }
    };
    //
    return {
        getAllUsers,
        storeUser,
        updateUser,
        destroyUser,
        getCurrentUserId
    };
});
