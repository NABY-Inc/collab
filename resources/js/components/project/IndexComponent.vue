<template>
  <div>
      <div class="section-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <ul class="nav nav-tabs page-header-tab">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Project-OnGoing">OnGoing</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Project-UpComing">UpComing</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Project-add">Create New Project                                                                                                                       </a></li>
                        </ul>
                        <div class="header-action d-md-flex">
                            <div class="input-group mr-2">
                                <!-- <input type="text" class="form-control" placeholder="Search..."> -->
                                <button type="button" style="background-color: red;color:white" class="btn btn-default" data-toggle="modal" data-target="#joinProject">Join Project</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="Project-OnGoing" role="tabpanel">
                    <div class="row" v-if="ongoing.length > 0">
                        <div class="col-lg-6 col-md-12" v-for="project in ongoing" :key="project.id">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title" style="text-decoration:underline !important; color:#00A9BD">
                                        <a v-if="user_role == 0" :href="'project/'+project.id" data-toggle="tooltip" title="" data-original-title="Click to view project">{{project.title}}</a>
                                        <a v-else :href="'admin-project/'+project.id" data-toggle="tooltip" title="" data-original-title="Click to view project">{{project.title}}</a>
                                    </h3>
                                    <div class="card-options">
                                        <label class="custom-switch m-0">
                                            <input type="checkbox" class="custom-switch-input" :checked="!project.freeze" disabled>
                                            <span class="custom-switch-indicator" data-toggle="tooltip" title="" @click="project.user_id == user_id ? freezeProject(project.id,project.freeze) : '' "
                                            :data-original-title="project.freeze == 0 ? 'Project Active' : 'Project Locked'"></span>
                                        </label>
                                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fa fa-chevron-up"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a><span class="tag mb-3 text-white" :style="'background-color: '+ project.color +' !important'">{{project.category}}</span></a>
                                    <a v-if="project.user_id == user_id" @click="showEditModal(project.id,'ongoing')" data-toggle="modal" data-target="#editUpcomingProject" href="#" class="btn btn-info btn-sm pull-right">Edit</a>
                                    <p>{{project.description}}</p>
                                    <div class="row">
                                        <div class="col-5 py-1"><strong>Creator:</strong></div>
                                        <div class="col-7 py-1">
                                            <img class="avatar avatara-sm" 
                                            :src="domain+'uploads/users/'+project.user.profile" data-placement="right" data-toggle="tooltip" title="" 
                                            :data-original-title="project.user.name">
                                        </div>
                                        <div class="col-5 py-1"><strong>Began on:</strong></div>
                                        <div class="col-7 py-1">{{moment(project.dateFrom).format('Do MMMM, YYYY')}}</div>
                                        <div class="col-5 py-1"><strong>Estimated End Date:</strong></div>
                                        <div class="col-7 py-1">{{moment(project.dateTo).format('Do MMMM, YYYY')}}</div>
                                        <div class="col-5 py-1"><strong>Project Code:</strong></div>
                                        <div class="col-7 py-1"><strong>{{project.user_id == user_id ? project.code : 'Not Available'}}</strong></div>
                                        <div class="col-5 py-1"><strong>Team:</strong></div>
                                        <div class="col-7 py-1">
                                            <div class="avatar-list avatar-list-stacked">
                                                <img class="avatar avatara-sm"  v-for="member in project.members.slice(0, 5)" :key="member.id" 
                                                :src="domain+'uploads/users/'+member.user.profile" data-placement="bottom" data-toggle="tooltip" title="" 
                                                :data-original-title="member.user.name">
                                                <span class="avatar avatar-sm" v-if="project.members.length > 5">+{{project.members.length - 5}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-left"><strong>{{projectCompletionPercentage(moment(project.dateFrom),moment(project.dateTo))}}%</strong></div>
                                        <div class="float-right"><small class="text-muted">Progress with timeline</small></div>
                                    </div>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-red" role="progressbar" 
                                            :style="'width: '+projectCompletionPercentage(moment(project.dateFrom),moment(project.dateTo))+'%'"
                                             aria-valuenow="36" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="card">
                                <p class="card-body mt-20 mb-20 ml-5">You are currently not partaking a project.</p>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="Project-UpComing" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body" v-if="upcoming.length > 0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped table-vcenter mb-0 text-nowrap">
                                            <thead>
                                            <tr>
                                                <th>Owner</th>
                                                <th>Title</th>
                                                <th>Code</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="project in upcoming" :key="project.id">
                                                    <td>
                                                        <img :src="domain + 'uploads/users/'+ project.user.profile" alt="Avatar" class="w30 rounded-circle mr-2"> 
                                                        <span>{{project.user.name}}</span>
                                                    </td>
                                                    <td>{{project.title}}</td>
                                                    <td>{{project.user_id == user_id ? project.code : 'Ask project owner'}}</td>
                                                    <td><span>{{moment(project.dateFrom).format('MMM DD, YYYY')}}</span></td>
                                                    <td><span>{{moment(project.dateTo).format('MMM DD, YYYY')}}</span></td>
                                                    <td>
                                                        <a class="btn btn-sm btn-link" v-if="project.user_id == user_id" @click="showEditModal(project.id,'upcoming')" href="javascript:void(0)" data-toggle="modal" data-target="#editUpcomingProject" title="Edit" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                        <a v-if="user_role == 0" class="btn btn-sm btn-link" :href="'project/'+project.id+'/preview-project'" data-toggle="tooltip" data-original-title="Preview"><i class="fa fa-eye"></i></a>
                                                        <a v-else class="btn btn-sm btn-link" :href="'admin-project/'+project.id+'/preview-project'" data-toggle="tooltip" data-original-title="Preview"><i class="fa fa-eye"></i></a>
                                                        <a class="btn btn-sm btn-link" v-if="project.user_id == user_id" @click="deleteProject(project.id)"  href="javascript:void(0)" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-body" v-else>
                                    <p>You have no up-coming projects.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bringing in the create project component -->
                <create-project />
                
            </div>
        </div>
    </div>

    <!-- Edit Upcoming Project Component -->
    <edit-upcoming-project :domain="domain" :user_id="user_id" />

    <!-- Join Project Component -->
    <join-project />
    
  </div>
</template>

<script>
import { mapState } from "vuex";
import createProject from "./CreateComponent.vue"
import editUpcomingProject from "./EditUpcomingComponent.vue"
import joinProject from "./JoinComponent.vue"
var moment = require('moment');
export default {
    components:{
        createProject,
        editUpcomingProject,
        joinProject
    },

    data(){
        return {
            moment:moment  
        }
    },
    
    props:['upcoming_projects','ongoing_projects','user_id','user_role','domain'],

    created(){
        this.$store.dispatch('project/allOngoing',this.ongoing_projects)
        this.$store.dispatch('project/allUpcoming',this.upcoming_projects)
    },

    computed:{
        ...mapState('project',['ongoing','upcoming'])
    },

    watch:{
        ongoing(){
            return this.ongoing
        },
        upcoming(){
            return this.ongoing
        }
    },
    methods:{
        projectCompletionPercentage(startDate,endDate){
            var now = moment()
            var percentage_complete = (now - startDate) / (endDate - startDate) * 100;
            var percentage_rounded = (Math.round(percentage_complete * 100) / 100); 
            if (percentage_rounded >= 100) {
                return 100
            } else {
                return percentage_rounded
            }
        },

        freezeProject(project_id,freeze){
            var that = this
            if (freeze == 0) {
                var title = 'Lock Project!'
                var text = "This action will disable project activities. Are you sure of this ?";
                var btnText = "Yes, Lock!";
                var btnColor = "#dc3545";
            }else{
                var title = 'Unlock Project!'
                var text = "Project Locked. Are you sure you want to unlock ?";
                var btnText = "Yes, unlock!";
                var btnColor = "#21ba45";
            }
            swal({
                title: title,
                text: text,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: btnColor,
                confirmButtonText: btnText,
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            }, function () {
                that.$store.dispatch('project/toggleFreeze',[project_id,freeze,that.user_role]).then(()=>{
                    swal('Success!','Project status changed.','success')
                })
            });
        },

        showEditModal(id,type){
            this.$store.dispatch('project/selectedProject',[id,type])
        },

        deleteProject(project_id){
            var that = this
            swal({
                title: 'Delete Project!',
                text: 'This will permanently delete the project. You sure of this ?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: "Yes Delete!",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            }, function () {
                that.$store.dispatch('project/deleteProject',[project_id,that.user_role]).then(()=>{
                    swal('Success!','Project Deleted.','success')
                })
            });
        }
    }

}
</script>