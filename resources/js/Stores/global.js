import {defineStore} from "pinia";

export const useGlobalStore = defineStore('global', () => {
    const path = '/';

    return {path}
},{
    persist:true
})
