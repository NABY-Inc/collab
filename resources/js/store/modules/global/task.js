const state = {
    tasks:[],
    selectedTask:{},

}

const mutations = {

    SET_TASKS(state,data){
        state.tasks = data
    },

    SET_SELECTED_TASK(state,data){
        state.selectedTask = state.tasks.find(task => task.id == data)
    }
}

const actions = {
    getAll({commit},data){
        axios.post(data+'/get-tasks')
        .then((response)=>{
            commit('SET_TASKS',response.data)
        })
    },
    
    async create({commit},data){
        await axios.post('create-task',data)
        .then((response)=>{
            commit('SET_TASKS',response.data)
        })
    },
    
    async saveTaskMembers({commit},data){
        await axios.post('add-task-members',data)
        .then((response)=>{
            commit('SET_TASKS',response.data)
        })
    },
    
    setStatus({commit},data){
        axios.post('complete-task',data)
        .then((response)=>{
            commit('SET_TASKS',response.data)
        })
    },
    
    selectedTask({commit},data){
        commit('SET_SELECTED_TASK',data)
    },
    
    async update({commit},data){
        await axios.post('update-task',data)
        .then((response)=>{
            commit('SET_TASKS',response.data)
        })
    },
    
    async delete({commit},data){
        await axios.post('delete-task',data)
        .then((response)=>{
            commit('SET_TASKS',response.data)
        })
    },


}

const getters = {
    completed(state){
        var completed = state.tasks.filter(task => task.status == 1)
        return completed
    },
    inProgress(state){
        var progress = state.tasks.filter(task => task.status == 0)
        return progress
    }
}

export default {
    namespaced:true,
    state,
    mutations,
    actions,
    getters
}
