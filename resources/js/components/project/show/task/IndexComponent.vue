<template>
    <div class="tab-pane fade" id="tasks" role="tabpanel">
      <div class="section-body mt-3" v-if="active_project.user_id == user_id">
        <div class="container-fluid">
          <div class="row clearfix mt-2 row-deck">
              <div class="col-xl-6 col-lg-6 col-md-6">
                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title">In progress Tasks</h3>
                      </div>
                      <div class="card-body">
                          <h5 class="number mb-0 font-32 counter">{{tasks.length > 0 ? (tasks.length) - (completed.length): '0'}}</h5>
                          <span class="font-12">Total Tasks in action</span>
                      </div>
                  </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6">
                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title">Completed Tasks</h3>
                      </div>
                      <div class="card-body">
                          <h5 class="number mb-0 font-32 counter">{{completed.length}}</h5>
                          <span class="font-12">Total Tasks Completed</span>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>

      <div class="section-body">
        <div class="container-fluid">
          <button v-if="active_project.user_id == user_id" type="submit" class="btn btn-primary mb-2" data-toggle="modal" data-target="#addTask">Add New</button>
            <div class="row clearfix">
                <div class="col-lg-4 col-md-12">
                    <div class="card planned_task">
                        <div class="card-header">
                            <h3 class="card-title">Tasks Assigned to You</h3>
                        </div>
                        <div class="card-body">
                            <div class="dd" data-plugin="nestable">
                                <ul v-if="inProgress.length > 0">
                                <li v-for="task in userTasks" :key="task.id">
                                    <div>
                                        <h6>{{task.title}}</h6>
                                        <span class="time"><span class="text-success" style="color:#00A9BD !important">Start: {{moment(task.start).format('DD MMM')}}</span> to <span class="text-success" style="color:#00A9BD !important">Complete: {{task.due != 'null' ? moment(task.due).format('DD MMM') : 'Not Set'}}</span></span>
                                        <p class="mt-2" v-html="task.description"></p>
                                        <ul class="list-unstyled team-info" v-if="task.task_members.length > 0">
                                            <li v-for="member in task.task_members" :key="member.id"><img :src="domain+'uploads/users/'+member.user.profile" data-toggle="tooltip" data-placement="top" title="" alt="Avatar" data-original-title="Avatar"></li>
                                        </ul>
                                    </div>
                                <hr>
                                </li>
                            </ul>
                            <ul v-else>                              
                                <div>
                                    <p>No Task Available</p>
                                </div>
                            </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Task in progress -->
                <div class="col-lg-4 col-md-12" v-if="active_project.user_id == user_id">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">In progress</h3>
                        </div>
                        <div class="card-body">
                            <ul v-if="inProgress.length > 0">
                                <li v-for="task in inProgress" :key="task.id">
                                    <div>
                                        <h6>
                                            <span data-toggle="tooltip" title="Complete Task" data-original-title="Share"><a class="btn btn-sm" @click="completeTask(task)" href="#"><i class="fa fa-check"></i></a> </span>
                                            <a class="text-info" @click="showEdit(task.id)" href="#"><u>{{task.title}}</u></a>
                                            <span data-toggle="tooltip" title="Delete Task" data-original-title="Share"><a class="btn btn-sm" @click="deleteTask(task.id)" href="#"><i class="fa fa-trash"></i></a> </span>
                                        </h6>
                                        <span class="time"><span class="text-success" style="color:#00A9BD !important">Start: {{moment(task.start).format('DD MMM')}}</span> to <span class="text-success" style="color:#00A9BD !important">Complete: {{task.due != 'null' ? moment(task.due).format('DD MMM') : 'Not Set'}}</span></span>
                                        <p class="mt-2" v-html="task.description"></p>
                                        <ul class="list-unstyled team-info" v-if="task.task_members.length > 0">
                                            <li v-for="member in task.task_members" :key="member.id"><img :src="domain+'uploads/users/'+member.user.profile" data-toggle="tooltip" data-placement="top" title="" alt="Avatar" data-original-title="Avatar"></li>
                                            <li @click="showAddParticipant(task)"><a href="#"><span data-toggle="tooltip" title="Share" data-original-title="Share" class="avatar avatar-sm"><i class="fa fa-plus"></i></span></a></li>
                                        </ul>
                                        <ul class="list-unstyled team-info" v-else>
                                            <li @click="showAddParticipant(task)"><a href="#"><span data-toggle="tooltip" title="Share" data-original-title="Share" class="avatar avatar-sm"><i class="fa fa-plus"></i></span></a></li>
                                        </ul>
                                    </div>
                                <hr>
                                </li>
                            </ul>
                            <ul v-else>                              
                                <div>
                                    <p>No Task Available</p>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12" v-if="active_project.user_id == user_id">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Completed</h3>
                        </div>
                        <div class="card-body">
                            <ul v-if="completed.length > 0">
                                <li class="dd-item" data-id="1" v-for="task in completed" :key="task.id">
                                    <div class="dd-handle">                                        
                                        <h6>
                                            {{task.title}}
                                            <span data-toggle="tooltip" title="Delete Task" data-original-title="Share"><a class="btn btn-sm" @click="deleteTask(task.id)" href="#"><i class="fa fa-trash"></i></a> </span>
                                        </h6>
                                        <span class="time"><span class="text-primary" style="color:#00A9BD !important">Start: {{moment(task.start).format('DD MMM')}}</span> to <span class="text-danger" style="color:#00A9BD !important">Complete: {{task.due != 'null' ? moment(task.due).format('DD MMM') : 'Not Set'}}</span></span>
                                        <p v-html="task.description"></p>
                                        <ul class="list-unstyled team-info">
                                            <li v-for="member in task.task_members" :key="member.id"><img :src="domain+'uploads/users/'+member.user.profile" data-toggle="tooltip" data-placement="top" title="" alt="Avatar" data-original-title="Avatar"></li>
                                        </ul>
                                    </div>
                                <hr>
                                </li>
                            </ul>
                            <ul v-else>                              
                                <div>
                                    <p>No Task Completed</p>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Craete Task -->
    <create-task />
    
    <!-- Edit Task -->
    <edit-task ref="editTask" />

    <!-- Add Task Participant -->
    <add-task-participant ref="addTaskParticipant" />

    
    
  </div>
</template>

<script>

import { mapState,mapGetters } from "vuex";
import CreateTask from './CreateComponent.vue'
import EditTask from './EditComponent.vue'
import AddTaskParticipant from './AddParticipantComponent.vue'
var moment = require('moment');

export default {
    components:{
        CreateTask,
        EditTask,
        AddTaskParticipant
    },

    data(){
        return{
            moment:moment,
        }
    },

    props:['active_project','domain','user_id'],

    created(){
        this.$store.dispatch('task/getAll',this.active_project.id)
    },

    computed:{
        // ...mapState('project',['project']),
        ...mapState('task',['tasks']),
        ...mapGetters('task',['completed','inProgress']),
        userTasks(){
            var tasks = this.inProgress.filter(task => (task.task_members.some(member => member.user_id == this.user_id)))
            return tasks
        }
    },

    methods:{
        completeTask(task){
            var that = this
            swal({
                title: "Complete Task",
                text: "This action will mark task as Completed. You sure of this?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, complete!',
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            }, function () {
                that.$store.dispatch('task/setStatus',{
                    project_id:that.$parent.project.id,
                    task_id:task.id,
                    status:task.status
                }).then(()=>{
                    swal('Success!','Task status changed.','success')
                })
            });
        },
        showAddParticipant(task){
            this.$refs.addTaskParticipant.team = []
            this.$refs.addTaskParticipant.task_id = task.id
            
            task.task_members.forEach(element => {
                this.$refs.addTaskParticipant.team.push({id:element.user.id,name:element.user.name})
            });
            
            $('#addParticipants').modal('show')
        },

        showEdit(id){
            this.$store.dispatch('task/selectedTask',id)
            $('#editTask').modal('show')

        },
        deleteTask(task_id){
            var that = this
            swal({
                title: "Delete Task",
                text: "You won't be able to recover. Are you sure of this?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, delete!',
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            }, function () {
                that.$store.dispatch('task/delete',{
                    project_id:that.$parent.project.id,
                    task_id:task_id,
                }).then(()=>{
                    swal('Success!','Task Deleted.','success')
                })
            });
        },
        
    }
}
</script>

<style>

</style>