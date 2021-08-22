const state = {
    notes:[],
    selectedAnnouncement:{},

}

const mutations = {

    SET_NOTES(state,data){
        state.notes = data
    },

    SET_SELECTED_ANNOUNCEMENT(state,data){
        state.selectedAnnouncement = state.announcements.find(announcement => announcement.id == data)
    }
}

const actions = {
    getAll({commit},data){
        axios.post(data+'/get-notes')
        .then((response)=>{
            commit('SET_NOTES',response.data)
        })
    },
    
    async create({commit},data){
        await axios.post('create-note',data)
        .then((response)=>{
            commit('SET_NOTES',response.data)
        })
    },
    
    async saveNoteMembers({commit},data){
        await axios.post('add-note-members',data)
        .then((response)=>{
            commit('SET_NOTES',response.data)
        })
    }


}

const getters = {}

export default {
    namespaced:true,
    state,
    mutations,
    actions,
    getters
}
