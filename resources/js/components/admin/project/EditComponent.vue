<template>
    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Project</h3>
            </div>
            <form @submit.prevent="updateProject()" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row clearfix">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label>Project Title</label>
                                    <input type="text" class="form-control" placeholder="Enter Title here" name="name" v-model="title" required autofocus autocomplete>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Select Project Category</label>
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
                                <div class="form-group">
                                    <label>Priority</label>
                                    <select class="form-control" required v-model="priority">
                                        <option value="">-- Select Priority --</option>
                                        <option>High</option>
                                        <option>Medium</option>
                                        <option>Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <label>Date Range</label>
                                <div class="form-group">
                                    <div class="input-daterange input-group">
                                        <input type="date" class="form-control" name="start" placeholder="Start Date" v-model="dateFrom">
                                        <span class="input-group-addon"> to </span>
                                        <input type="date" class="form-control" name="end" placeholder="Ending Date" v-model="dateTo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Project Code</label>
                                    <input type="text" class="form-control" placeholder="Enter Title here" name="name" v-model="projectCode" required disabled>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Add New Project Member(s).</label><br>
                                    <div v-show="this.loading == false">
                                        <select id="newMembers" multiple="multiple" placeholder="Select Project members" class="search_test" style="width:170%">
                                            <option v-for="member in this.members" @click="selectedmembers()"  :key="member.id" :value="member.id">{{member.name}}</option>
                                        </select>
                                    </div>
                                    <span v-show="this.loading == true" class="btn btn-xs btn-primary"><i class="fa fa-cog fa-spin ml-2 "></i> Loading...</span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <label>Project Progress <span class="bg-success text-white p-1">({{this.progress}}%)</span></label>
                                <div class="form-group">
                                    <input type="range" class="form-control-range" data-toggle="tooltip" title="" name="progress" min="0" max="100" style="1" v-model="progress">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <label>Change Project label (Optional)</label>
                                <div class="form-group">
                                    <input type="file" class="dropify" name="image" @change="onFileChange">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Project Description</label>
                                    <textarea name="" class="form-control" id="" cols="30" rows="9" placeholder="Put Description Here..." v-model="description" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Update Project</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    mounted(){
        this.theProject();
        this.allMembers();
        $('#newMembers').on('change',(event)=>{
            var array; 
            $(event.target).children(':selected').each(()=>{
                array = $(event.target).val();
            }); 
            this.selectedmembers(array);
            
        });
    },
    props:['project'],
    
    data(){
        return{
            members:[],
            loading:false,
            title:null,
            category:null,
            priority:null,
            dateFrom:null,
            dateTo:null,
            projectCode:null,
            team:[],
            image:null,
            description:null,
            progress:null,
            oldImage:null,
        }
    },
    methods:{

        allMembers(){
            this.loading = true;
            axios.get(''+this.project.id+'/newMembers')
            .then(response => {
                this.members = response.data;
                this.loading = false;
                
            });
        },

        theProject(){
            this.title = this.project.title;
            this.category = this.project.category;
            this.priority = this.project.priority;
            this.dateFrom = this.project.dateFrom;
            this.dateTo = this.project.dateTo;
            this.projectCode = this.project.code;
            this.description = this.project.description;
            this.progress = this.project.progress;
            this.oldImage = this.project.image;
        },

        onFileChange(e){
            this.image = e.target.files[0];
        },

        updateProject(){
            var fdata = new FormData();
                if (this.image != null) { fdata.append('image', this.image); }
                fdata.append('dateFrom', this.dateFrom);        fdata.append('team', this.team);
                fdata.append('title', this.title);              fdata.append('dateTo', this.dateTo);
                fdata.append('category', this.category);        fdata.append('code', this.projectCode);
                fdata.append('priority', this.priority);        fdata.append('description', this.description);
                fdata.append('progress', this.progress);        fdata.append('oldImage', this.oldImage);
            axios.post(''+this.project.id, fdata)
            .then(response => {
                if (response.data == 1) {
                        this.succcess();
                    }else{
                        this.error();
                    }
                    console.log(response.data);
            });
        },

        selectedmembers(data){
            this.team.push(data);
            // console.log(data);
        }, 

        succcess(){
            swal({
                title:"Success!",
                text: "Project Updated Successfully!",
                type: "success",
            }, function(){
                window.location.reload(true);
            });
        }, 

        error(){
            swal({
                title:"Oops Something went wrong!",
                text: "File should be an image with size not more than 2MB",
                type: "error"
            });
        }
    },


}
</script>
