import axios from "axios";
import type { User } from "../interfaces";

export const getAllUsers = async (): Promise<User> => {
    const resp = await axios.get<User>(`http://localhost:8000/api/users`);
    return resp.data;
};
