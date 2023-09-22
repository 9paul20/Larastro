import axios from "axios";
import { defineStore } from "pinia";
// import { ref } from "vue";
import type { User } from "../interfaces";
import { Datum } from "@js/interfaces/User";

export const usersStore = defineStore('usersStore', () => {
    //
    const getAllUsers = async (): Promise<User> => {
        const resp = await axios.get<User>(`http://localhost:8000/api/users`);
        return resp.data;
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
    return {
        getAllUsers,
        storeUser,
        updateUser,
    };
});
