<template>
  <div>
        <div class="section-body" v-if="project.freeze == 0">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-md-flex justify-content-between align-items-center">
                            <ul class="nav nav-tabs page-header-tab">
                                <li class="nav-item"><a @click="dispatchAnnouncement()" class="nav-link active" data-toggle="tab" href="#overview"> Overview</a></li>
                                <li class="nav-item"><a @click="dispatchDiscussions()" class="nav-link" data-toggle="tab" href="#discuss"> Discussion</a></li>                                        
                                <li class="nav-item"><a @click="dispatchTasks()" class="nav-link" data-toggle="tab" href="#tasks"> Tasks</a></li>                                        
                                <li class="nav-item"><a @click="dispatchFiles()" class="nav-link" data-toggle="tab" href="#files"> Files</a></li>                                        
                                <li class="nav-item"><a @click="dispatchNotes()" class="nav-link" data-toggle="tab" href="#note"> Notes</a></li>                                        
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#team"> Team</a></li>                                        
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#team"> Video Conference</a></li> 
                            </ul>
                            <!-- <div class="header-action d-flex">
                                <div class="input-group mr-2">
                                    <input type="text" class="form-control" placeholder="Search...">
                                </div>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addtask"><i class="fe fe-plus mr-2"></i>Add</button>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- If Project is unlocked we display this -->
        <div class="tab-content" v-if="project.freeze == 0">
            <!-- Project Overview -->
            <div class="tab-pane fade active show" id="overview" role="tabpanel">
                <div class="section-body mt-3">
                    <div class="container-fluid">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-12">
                                <div class="card c_grid c_yellow">
                                    <div class="card-body text-center">
                                        <div class="circle">
                                            <img class="rounded-circle" :src="domain+'uploads/users/'+project.user.profile" height="200" width="200" alt="">
                                        </div>
                                        <h6 class="mt-3 mb-0">{{project.user.name}} | PCS00{{project.user.id}}</h6>
                                        <span>Project Host</span> <br>
                                    </div>
                                </div>
                                <!-- Project Info Card -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Project Info</h3>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <small class="text-muted">Title: </small>
                                                <p class="mb-0">{{project.title}}</p>
                                            </li>
                                            <li class="list-group-item">
                                                <small class="text-muted">Category: </small>
                                                <p  class="mb-0">{{project.category}}</p>
                                            </li>
                                            <li class="list-group-item">
                                                <small class="text-muted">Date: </small>
                                                <p  class="mb-0">{{project.dateFrom+ ' to '+project.dateTo}}</p>
                                            </li>
                                            <li class="list-group-item" v-if="project.user_id == user_id">
                                                <small class="text-muted">Project Code: </small>
                                                <p  class="mb-0">{{project.code}}</p>
                                            </li>
                                            <li class="list-group-item">
                                                <div>Progress</div>
                                                <div class="clearfix">
                                                    <div class="float-left"><small>{{projectCompletionPercentage(moment(project.dateFrom),moment(project.dateTo))}}%</small></div>
                                                    <div class="float-right"><small class="text-muted">Completion with timeline</small></div>
                                                </div>
                                                <div class="progress progress-xs mb-0">
                                                    <div class="progress-bar bg-info" 
                                                    :style="'width: '+projectCompletionPercentage(moment(project.dateFrom),moment(project.dateTo))+'%'"
                                                    ></div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Projec Description Card -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Project Description</h3>
                                    </div>
                                    <div class="card-body">
                                        <span>
                                            {{project.description}}
                                        </span>
                                    </div>                        
                                </div>
                            </div>
                            <!-- Project Flyer -->
                            <div class="col-lg-8 col-md-12">
                                <div class="card">
                                    <img class="card-img-top" :src="domain+'uploads/project_thumbnails/'+project.flyer" height="300" width="300" alt="Card image cap">
                                </div>
                                <!-- Project Announcement Lists -->
                                <div class="card" style="background-color:#fcfbc5">
                                    <div class="card-header">
                                        <h3 class="card-title"><b>Announcements</b></h3>
                                        <a href="#" v-if="project.user_id == user_id" data-toggle="modal" data-target="#createAnnouncement" style="margin-left:70%;"><i class="fa fa-plus"></i> Add</a>
                                    </div>
                                    <div class="card-body">
                                        <div v-if="announcements.length > 0">
                                            <div class="timeline_item" v-for="announcement in announcements" :key="announcement.id">
                                                <span><small class="float-right text-right">{{moment(announcement.created_at).format('MMM D, YY')}} - {{moment(announcement.created_at).format('dddd')}}</small></span>
                                                <h6 class="font600" style="color:#114884 !important">{{announcement.title}} 
                                                    <a href="#" class="ml-3 fa fa-edit" data-toggle="tooltip" data-original-title="Edit Announcement" title="" v-if="project.user_id == user_id" @click.prevent="showeditAn(announcement.id)"></a>
                                                </h6>
                                                <!-- Announcement Comments -->
                                                <div class="msg">
                                                    <p v-html="announcement.description"></p>
                                                    <a class="text-muted" role="button" data-toggle="collapse" :href="'#collapse'+announcement.id" aria-expanded="false" :aria-controls="'collapse'+announcement.id"><i class="fa fa-comments"></i> {{announcement.announcement_comment.length}} Comment(s)</a>
                                                    <div class="collapse p-4 section-gray mt-2" :id="'collapse'+announcement.id">
                                                        <form class="well" @submit.prevent="addComment(announcement.id)">
                                                            <div class="form-group">
                                                                <textarea rows="2" class="form-control no-resize" required placeholder="Enter here for comment..." v-model="announComment"></textarea>
                                                                <span class="text-danger" v-if="errorComment">Please Enter Comment</span>
                                                            </div>
                                                            <button v-if="!isLoading" type="submit" class="btn btn-primary">Submit</button>
                                                            <button v-else class="btn btn-primary" disabled><i class="fa fa-spinner fa-spin"></i> Saving...</button>
                                                        </form>
                                                        <ul class="recent_comments list-unstyled mt-4 mb-0 comment_list" v-if="announcement.announcement_comment.length > 0">
                                                            <li v-for="comment in announcement.announcement_comment" :key="comment.id">
                                                                <div class="avatar_img">
                                                                    <img class="rounded img-fluid" :src="domain+'uploads/users/'+comment.user.profile" alt="">
                                                                </div>
                                                                <div class="comment_body">
                                                                    <h6>{{comment.user.name}} <small class="float-right font-14">{{moment(comment.created_at).fromNow()}}</small></h6>
                                                                    <p>{{comment.text}}
                                                                        <a href="#" @click.prevent="deleteComment(comment.id)" class="float-right" v-if="comment.user_id == user_id"><i class="fa fa-trash"></i></a>
                                                                        <a href="#" v-if="isDeleting" class="float-right"><i class="fa fa-spinner fa-spin"></i>Deleting...</a>
                                                                    </p>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <ul v-else>
                                                            <li class="mt-3">No comment</li>
                                                        </ul>   
                                                    </div>
                                                </div>                                
                                                <hr>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <p class="text-center">No Annoncement Posted</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- Announcement -->
            <project-announcement />
            
            <!-- Project Discussion -->
            <project-discussion :domain="domain" :user_id="user_id"/>
            
            <!-- Project Tasks -->
            <project-tasks :domain="domain" :active_project="active_project" :user_id="user_id"/>
            
            <!-- Project Files -->
            <project-files :domain="domain" :user_id="user_id"/>
            
            <!-- Project Notes -->
            <project-note :domain="domain" :user_id="user_id"/>
            
            <!-- Project Team -->
            <project-team :domain="domain" :user_id="user_id"/>

        </div>
        
        <!-- Freeze Project Display -->
        <div v-else>
            <div class="section-body mt-3">
                    <div class="container-fluid">
                        <div class="card">
                            <img class="card-img-top" :src="domain+'uploads/locked.png'" alt="Card image cap">
                            <b class="font-6000 text-center mt-2">Please contact Project Owner.</b>
                            <p class="text-center">If you are the project owner, click <a @click="unlock()" href="#"> here </a> to unlock project</p>
                            <p class="text-center" v-if="unlocking"><i class="fa fa-spinner fa-spin"></i> unlocking...</p>
                            <!-- <a href="" class="btn btn-primary mt-2">Go Back</a> -->
                        </div>
                    </div>
            </div>
        </div>

  </div>
</template>

<script>
import projectDiscussion from "./DiscussionComponent.vue";
import projectTasks from "./task/IndexComponent.vue";
import projectFiles from "./FilesComponent.vue";
import projectNote from "./NoteComponent.vue";
import projectTeam from "./TeamComponent.vue";
import projectAnnouncement from "./AnnouncementComponent.vue";

import { mapState } from "vuex";
var moment = require('moment');
export default {
    components:{
        projectDiscussion,
        projectTasks,
        projectFiles,
        projectNote,
        projectTeam,
        projectAnnouncement
    },

    data(){
        return{
            moment:moment,
            announComment:null,
            errorComment:false,
            isLoading:false,
            isDeleting:false,
            unlocking:false
        }
    },

    props:['active_project','domain','user_id'],

    created(){
        this.$store.dispatch('project/setProject',this.active_project)
        this.$store.dispatch('announcement/getAll',this.active_project.id)
    },

    computed:{
        ...mapState('project',['project']),
        ...mapState('announcement',['announcements'])
    },

    methods:{
        unlock(){
            this.unlocking = true
            if (this.project.user_id == this.user_id) {
                this.$store.dispatch('project/unlockFromShow',[this.project.id,1]).then(()=>{
                    swal('Success!','Project unlocked.','success')
                    this.unlocking = false
                })    
            } else {
                swal('You\'re not the project owner!','Please Contact the Project Owner to unlock project.','error')
                this.unlocking = false
            }
        },

        showeditAn(id){
            this.$store.dispatch('announcement/setAnnouncement',id)
            $('#editAnnouncement').modal('show')
        },
        addComment(announcementID){
            this.isLoading = true
            if (!this.announComment) {
                this.errorComment = true
                setTimeout(() => {
                    this.errorComment = false
                }, 5000);
                this.isLoading = false
            } else {
                var fdata = new FormData
                fdata.append('announcement_id',announcementID)
                fdata.append('project_id',this.project.id)
                fdata.append('text',this.announComment)
                this.$store.dispatch('announcement/addComment',fdata).then(()=>{
                    this.isLoading = false
                    this.announComment = null
                })
            }
        },

        deleteComment(id){
            var fdata = new FormData
            fdata.append('id',id)
            fdata.append('project_id',this.project.id)
            this.$store.dispatch('announcement/deleteComment',fdata)
        },

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
        dispatchFiles(){
            this.$store.dispatch('file/getAll',this.project.id)
        },
        dispatchDiscussions(){
            this.$store.dispatch('discussion/getAll',this.project.id)
        },
        dispatchTasks(){
            this.$store.dispatch('task/getAll',this.project.id)
        },
        dispatchNotes(){
            this.$store.dispatch('note/getAll',this.project.id)
        },
        dispatchAnnouncement(){
            this.$store.dispatch('announcement/getAll',this.active_project.id)
        },
    }

}
</script>

<style scoped>
#disable {
    color: #f5e5b6;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-color:#000;
    opacity: .75;
    z-index: 9999999;
}
</style>