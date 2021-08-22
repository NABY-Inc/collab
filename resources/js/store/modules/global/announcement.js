import { update } from "lodash"

const state = {
    announcements:[],
    selectedAnnouncement:{},

}

const mutations = {

    SET_ANNOUNCEMENT(state,data){
        state.announcements = data
    },

    SET_SELECTED_ANNOUNCEMENT(state,data){
        state.selectedAnnouncement = state.announcements.find(announcement => announcement.id == data)
    }
}

const actions = {
    getAll({commit},data){
        axios.post(data+'/get-announcements')
        .then((response)=>{
            commit('SET_ANNOUNCEMENT',response.data)
        })
    },
    
    async create({commit},data){
        await axios.post('createAnnouncement',data)
        .then((response)=>{
            commit('SET_ANNOUNCEMENT',response.data)
        })
    },

    setAnnouncement({commit}, data){
        commit('SET_SELECTED_ANNOUNCEMENT',data)
    },

    async update({commit}, data){
        await axios.post('updateAnnouncement',data)
        .then((response)=>{
            commit('SET_ANNOUNCEMENT',response.data)
        })
    },
    
    async delete({commit}, data){
        await axios.post('deleteAnnouncement',data)
        .then((response)=>{
            commit('SET_ANNOUNCEMENT',response.data)
        })
    },
    
    async addComment({commit}, data){
        await axios.post('saveAnnouncementComment',data)
        .then((response)=>{
            commit('SET_ANNOUNCEMENT',response.data)
        })
    },
    
    async deleteComment({commit}, data){
        await axios.post('deleteAnnouncementComment',data)
        .then((response)=>{
            commit('SET_ANNOUNCEMENT',response.data)
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
