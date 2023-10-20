import axios from "axios";
import { defineStore } from "pinia";
import type { Tag } from "../interfaces";
import { DatumTag } from "@js/interfaces/Tags/Tag";

export const tagsStore = defineStore('tagsStore', () => {
    //
    const getAllTags = async (): Promise<Tag> => {
        try {
            const resp = await axios.get<Tag>(`http://localhost:8000/api/tags`);
            return resp.data as Tag;
        } catch (error) {
            return error as any;
        }
    };
    //
    const storeTag = async (tag: DatumTag): Promise<DatumTag> => {
        try {
            const resp = await axios.post<DatumTag>('http://localhost:8000/api/tags', tag);
            return resp.data as DatumTag;
        } catch (error) {
            return error as any;
        }
    };
    //
    const updateTag = async (tag: DatumTag): Promise<DatumTag> => {
        try {
            const resp = await axios.put<DatumTag>(`http://localhost:8000/api/tags/${tag.id}`, tag);
            return resp.data as DatumTag;
        } catch (error) {
            return error as any;
        }
    };
    //
    const destroyTag = async (tag: DatumTag): Promise<DatumTag> => {
        try {
            const resp = await axios.delete<DatumTag>(`http://localhost:8000/api/tags/${tag.id}`);
            return resp.data as DatumTag;
        } catch (error) {
            return error as any;
        }
    };
    //
    const destroyTags = async (tags: Array<object>): Promise<Array<object>> => {
        try {
            const resp = await axios.post<any>('http://localhost:8000/api/tags/destroyMany', tags);
            return resp.data as Array<object>;
        } catch (error) {
            return error as any;
        }
    };
    //
    const getCurrentTagId = async (): Promise<object> => {
        try {
            const resp = await axios.get<object>(`http://localhost:8000/api/tags/getCurrentTagId`);
            return resp.data as object;
        } catch (error) {
            return error as any;
        }
    };
    //
    return {
        getAllTags,
        storeTag,
        updateTag,
        destroyTag,
        destroyTags,
        getCurrentTagId
    };
});
