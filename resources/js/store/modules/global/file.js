import { update } from "lodash"

const state = {
    files:[],
    selectedFile:{},

}

const mutations = {

    SET_FILES(state,data){
        state.files = data
    },

    SET_SELECTED_FILE(state,data){
        state.selectedFile = state.files.find(file => file.id == data)
    }
}

const actions = {
    getAll({commit},data){
        axios.post(data+'/get-files')
        .then((response)=>{
            commit('SET_FILES',response.data)
        })
    },
    
    async upload({commit},data){
        await axios.post('upload-file',data)
        .then((response)=>{
            commit('SET_FILES',response.data)
        })
    },

    async delete({commit}, data){
        await axios.post('delete-file',data)
        .then((response)=>{
            commit('SET_FILES',response.data)
        })
    },
    
}

const getters = {}

export default {
    namespaced:true,
    state,
    mutations,
    actions,
    getters
}
