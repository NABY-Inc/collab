const state = {
    users:[],
    user:{},
}

const mutations = {
    SET_USERS(state, users){
        state.users = users
    },

    SET_USER_EDIT(state,data){
        state.user = state.users.find(user => user.id == data) 
    }
}

const actions = {
    all({ commit }, data){
        commit('SET_USERS',data)
    },
    
    enableUserEdit({commit},data){
        commit('SET_USER_EDIT',data)
    },
    
    async update({commit},data){
        await axios.post('users/' + data.id, data.userData)
        .then((response)=>{
            commit('SET_USERS',response.data)
        })
    },

    async create({commit},data){
        await axios.post('',data)
        .then((response)=>{
            commit('SET_USERS',response.data)
        })
    },

    async toggleUserAccount({commit},data){
        await axios.post('users/toggleActivate/'+data[0],{active:data[1]})
        .then((response)=>{
            commit('SET_USERS',response.data)
        })
    }
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