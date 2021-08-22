<template>
<div class="modal fade" id="editUpcomingProject" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header mt-2" style="justify-content:center !important">
                <h6 class="title" id="defaultModalLabel">Edit Project</h6>
            </div>
            <form @submit.prevent="updateProject(selectedProject.id)">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <img style="height:-50% !important" :src="domain + 'uploads/project_thumbnails/' + selectedProject.flyer" alt="">
                            <p class="text-center text-danger mt-2 font-20" style="margin-bottom:0px;text-transform: uppercase;">Project Take Up {{moment(selectedProject.dateFrom).fromNow()}}</p>
                            <div class="progress progress-xs mb-3">
                                <div class="progress-bar bg-red" role="progressbar" 
                                    :style="'width: 100%'"
                                    aria-valuenow="36" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label>Project Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter Title here" name="name" v-model="selectedProject.title" required autofocus autocomplete>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label>Select Project Category <span class="text-danger">*</span></label>
                                        <select class="form-control" required v-model="selectedProject.category">
                                            <option value="">-- Select Category --</option>
                                            <option>Technology</option>
                                            <option>Education</option>
                                            <option>Business</option>
                                            <option>Finance</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label>Date Range [Start Date - End Date] <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <div class="input-daterange input-group">
                                            <input type="date" class="form-control" name="start" placeholder="Start Date" v-model="selectedProject.dateFrom" required>
                                            <span class="input-group-addon pl-3 pt-2 pr-3"> to </span>
                                            <input type="date" class="form-control" name="end" placeholder="Ending Date" v-model="selectedProject.dateTo" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label>Project Flyer <small>(optional)</small></label>
                                    <div class="form-group">
                                        <input type="file" id="flyerUpdate" class="form-control" @change="onImageUpdate">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label>Select Project Team. <span class="text-danger">*</span></label><br>
                                            <multiselect v-model="team" placeholder="Select Project members" 
                                                :options="members" :multiple="true" :close-on-select="false" 
                                                :preserve-search="true" label="name" track-by="name" />
                                        <span style="color:red" v-show="this.showError">Please select Project Member(s)</span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label>Select Project Color. <small>(optional)</small></label><br>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" :style="'background-color: ' + selectedProject.color"></span>
                                            </div>
                                            <input type="color" style="height:40px" class="form-control" v-model="selectedProject.color">   
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Project Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" placeholder="Please say something about this project.." v-model="selectedProject.description" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" v-if="!isLoading" class="btn btn-primary">Update</button>
                    <button type="button" v-else class="btn btn-primary" disabled><i class="fa fa-spinner fa-spin"></i> Updating...</button>
                    <button type="button" class="btn btn-default" @click="code=null" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>
import { mapState } from "vuex";
import Multiselect from 'vue-multiselect'
var moment = require('moment');
export default {
    components:{
        Multiselect
    },
    
    data(){
        return{
            moment:moment,
            isLoading:false,
            members:[],
            team:[],
            image:null,
            showError:false,
        }
    },

    props:['domain','user_id'],

    created(){
        this.$store.dispatch('project/findAllUsers')
    },

    computed:{
        ...mapState('project',['allUsers','selectedProject'])
    },

    watch:{
        allUsers(allUsers){
            this.members = []
            allUsers.forEach(element => {
                this.members.push({id:element.id,name:element.name})
            });
        },
        selectedProject(){
            this.team=[]
            this.selectedProject.members.forEach(element => {
                this.team.push({id:element.user.id,name:element.user.name})
            });
            this.team = this.team.filter(member => member.id != this.selectedProject.user_id) // Removing the project creator
        }

    },

    methods:{

        onImageUpdate(e){
            if (!this.validateFile('flyerUpdate', ['.jpg','.png','.jpeg','.JPG','.PNG','.JPEG'])) {
                swal("Oops! Invalid Image","Please select an image with extension .jpg, .png, or .jpeg!","error")
                document.getElementById("flyerUpdate").value = "" // Emptying flyer input
                this.image = null // Emptying image value
            }else{
                var img = e.target.files[0]
                if(img.size > 3145728){
                    swal("Oops! Somthing went wrong","Image should be less than 3MB","error")
                    document.getElementById("flyerUpdate").value = "" // Emptying file input
                    this.image = null // Emptying image value
                }else{
                    this.image = img
                }
            }
        },

        validateFile(inputID, exts) {
            var fileName = document.getElementById(inputID).value;
            return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
        },

        updateProject(id){
            this.isLoading = true
            if (this.team.length < 1) {
                this.showError = true
                this.isLoading = false
            }else{
                this.showError = false
                var fdata = new FormData();
                fdata.append('dateFrom', this.selectedProject.dateFrom);        fdata.append('team', this.setTeamIds(this.team));
                fdata.append('title', this.selectedProject.title);              fdata.append('dateTo', this.selectedProject.dateTo);
                fdata.append('category', this.selectedProject.category);        fdata.append('image', this.image);              
                fdata.append('description', this.selectedProject.description);  fdata.append('color',this.selectedProject.color)
                fdata.append('old_image', this.selectedProject.image);              
                
                this.$store.dispatch('project/update',[id,fdata,this.$parent.user_role]).then(()=>{
                    swal('Success!','Project Updated Successfully!','success');
                    this.isLoading = false
                })
            }
        },

        setTeamIds(team){
            var teamIds = []
            team.forEach(e => { teamIds.push(e.id) })
            return teamIds
        },


    },
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>