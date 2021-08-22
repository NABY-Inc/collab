const state = {
    ongoing:[],
    upcoming:[],
    selectedProject:[],
    allUsers:[],
    error_join:null,
    project:{},
}

const mutations = {
    SET_ONGOING_PROJECT(state,data){
        state.ongoing = data
    },
    
    SET_UPCOMING_PROJECT(state,data){
        state.upcoming = data
    },
    
    SET_SELECTED_PROJECT(state,data){
        if (data.type == 'ongoing') {
            state.selectedProject = state.ongoing.find(project => project.id == data.id) 
        } else {
            state.selectedProject = state.upcoming.find(project => project.id == data.id)
        }
    },

    SET_PROJECT(state,data){
        state.project = data
    },

    SET_USERS(state,data){
        state.allUsers = data
    },

    SET_ERROR_JOINING(state,data){
        state.error_join = data
    },

    
}

const actions = {
    allOngoing({commit}, data){
        commit('SET_ONGOING_PROJECT',data)
    },

    allUpcoming({commit}, data){
        commit('SET_UPCOMING_PROJECT',data)
    },

    findAllUsers({commit}){
        axios.post('allmembers')
        .then(response => {
            commit('SET_USERS', response.data)
        });
    },

    selectedProject({commit},data){
        commit('SET_SELECTED_PROJECT',{id:data[0],type:data[1]})
    },

    setProject({commit},data){
        commit('SET_PROJECT',data)
    },

    async create({commit}, data){
        await axios.post('',data)
        .then(response => {
            commit('SET_ONGOING_PROJECT',response.data[0])
            commit('SET_UPCOMING_PROJECT',response.data[1])
        })
    },

    async joinProject({commit},data){
        await axios.post(data[1] == 1 ?'admin-project/join':'project/join',{code:data[0]})
        .then((response)=>{
            if (response.data == 111 || response.data == 212) {
                commit('SET_ERROR_JOINING',response.data)
            } else {
                commit('SET_ONGOING_PROJECT',response.data[0])
                commit('SET_UPCOMING_PROJECT',response.data[1])
                commit('SET_ERROR_JOINING',null)
            }
        })
    },
    
    async toggleFreeze({commit},data){
        await axios.post(data[2] == 1 ? 'admin-project/toggle-freeze':'project/toggle-freeze',{project_id:data[0],freeze:data[1]})
        .then((response)=>{
            commit('SET_ONGOING_PROJECT',response.data[0])
            commit('SET_UPCOMING_PROJECT',response.data[1])
        })
    },
    
    async unlockFromShow({commit},data){
        await axios.post('toggle-freeze',{project_id:data[0],freeze:data[1],show:1})
        .then((response)=>{
            commit('SET_PROJECT',response.data)
        })
    },
    
    async update({commit},data){
        await axios.post(data[2] == 1 ? 'admin-project/update/'+data[0]:'project/update/'+data[0],data[1])
        .then((response) => {
            commit('SET_ONGOING_PROJECT',response.data[0])
            commit('SET_UPCOMING_PROJECT',response.data[1])
        })
    },
    
    async deleteProject({commit},data){
        await axios.delete(data[1] == 1 ? 'admin-project/'+data[0] : 'project/'+data[0])
        .then((response)=>{
            commit('SET_ONGOING_PROJECT',response.data[0])
            commit('SET_UPCOMING_PROJECT',response.data[1])
        })
    },

}

const getters = {

}

export default {
    namespaced:true,
    state,
    mutations,
    actions,
    getters
}