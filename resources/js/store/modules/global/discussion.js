import { update } from "lodash"

const state = {
    discussions:[],
    selectedDiscussion:{},

}

const mutations = {

    SET_DISCUSSION(state,data){
        state.discussions = data
    },

    SET_SELECTED_DISCUSSION(state,data){
        state.selectedDiscussion = state.discussions.find(discussion => discussion.id == data)
    }
}

const actions = {
    getAll({commit},data){
        axios.post(data+'/get-discussions')
        .then((response)=>{
            commit('SET_DISCUSSION',response.data)
        })
    },
    
    async create({commit},data){
        await axios.post('create-discussion',data)
        .then((response)=>{
            commit('SET_DISCUSSION',response.data)
        })
    },

    async delete({commit}, data){
        await axios.post('delete-discussion',data)
        .then((response)=>{
            commit('SET_DISCUSSION',response.data)
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
