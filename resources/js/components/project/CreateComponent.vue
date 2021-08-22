<template>
    <div class="tab-pane fade" id="Project-add" role="tabpanel">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="createProject()">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label>Project Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter Title here" name="name" v-model="title" required autofocus autocomplete>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label>Select Project Category <span class="text-danger">*</span></label>
                                        <select class="form-control" required v-model="category">
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
                                            <input type="date" class="form-control" name="start" placeholder="Start Date" v-model="dateFrom" required>
                                            <span class="input-group-addon pl-3 pt-2 pr-3"> to </span>
                                            <input type="date" class="form-control" name="end" placeholder="Ending Date" v-model="dateTo" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label>Project Flyer <small>(optional)</small></label>
                                    <div class="form-group">
                                        <input type="file" id="flyer" class="form-control" @change="onImageChange">
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
                                                <span class="input-group-text" :style="'background-color: ' + color"></span>
                                            </div>
                                            <input type="color" style="height:40px" class="form-control" v-model="color">   
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Project Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="" cols="30" rows="9" placeholder="Please say something about this project.." v-model="description" required></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <button type="submit" v-if="!isLoading" class="btn btn-primary">Create</button>
                                    <button type="button" v-else class="btn btn-primary" disabled><i class="fa fa-spinner fa-spin"></i> Creating Project..</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
import Multiselect from 'vue-multiselect'
export default {
    components:{
        Multiselect
    },
    
    data(){
        return{
            isLoading:false,
            title:null,
            category:null,
            dateFrom:null,
            dateTo:null,
            members:[],
            color:null,
            team:[],
            image:null,
            description:null,
            showError:false,
        }
    },

    props:['user_role'],

    created(){
        this.$store.dispatch('project/findAllUsers')
        this.randomizeCategoryColor()
    },

    computed:{
        ...mapState('project',['allUsers'])
    },

    watch:{
        allUsers(allUsers){
            this.members = []
            allUsers.forEach(element => {
                this.members.push({id:element.id,name:element.name})
            });
        }
    },

    methods:{

        onImageChange(e){
            if (!this.validateFile('flyer', ['.jpg','.png','.jpeg','.JPG','.PNG','.JPEG'])) {
                swal("Oops! Invalid Image","Please select an image with extension .jpg, .png, or .jpeg!","error")
                document.getElementById("flyer").value = "" // Emptying flyer input
                this.image = null // Emptying image value
            }else{
                var img = e.target.files[0]
                if(img.size > 3145728){
                    swal("Oops! Somthing went wrong","Image should be less than 3MB","error")
                    document.getElementById("flyer").value = "" // Emptying file input
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

        createProject(){
            this.isLoading = true
            if (this.team.length < 1) {
                this.showError = true
                this.isLoading = false
            }else{
                this.showError = false
                var fdata = new FormData();
                fdata.append('dateFrom', this.dateFrom);        fdata.append('team', this.setTeamIds(this.team));
                fdata.append('title', this.title);              fdata.append('dateTo', this.dateTo);
                fdata.append('category', this.category);        fdata.append('image', this.image);              
                fdata.append('description', this.description);  fdata.append('color',this.color)
                
                this.$store.dispatch('project/create',fdata).then(()=>{
                    swal('Success!','New Project Created Successfully!','success');
                    this.clearForm();
                    this.isLoading = false
                })
            }
        },

        setTeamIds(team){
            var teamIds = []
            team.forEach(e => { teamIds.push(e.id) })
            return teamIds
        },

        clearForm(){
            this.title = null
            this.category = null
            this.dateFrom = null
            this.dateTo = null
            this.description = null
            this.team = []
            document.getElementById("flyer").value = "" // Emptying file input
            this.randomizeCategoryColor() // Reseting color
        }, 

        randomizeCategoryColor(){
            var letters = "0123456789ABCDEF".split('');
            var color = "#";
            for (var i = 0; i < 6; i++ ) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            this.color = color
        },

    },
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>