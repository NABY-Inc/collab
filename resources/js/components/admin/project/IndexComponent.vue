<template>
    <div class="tab-pane fade" id="Project-add" role="tabpanel">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="createProject()" enctype="multipart/form-data">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-12">
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
                                <div class="col-lg-6 col-md-12">
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
                                        <label>Select Project Team.</label><br>
                                        <div v-show="this.loading == false">
                                            <select id="members" multiple="multiple" placeholder="Select Project members" class="search_test" style="width:170%" required>
                                                <option v-for="member in this.members" @click="selectedmembers()"  :key="member.id" :value="member.id">{{member.name}}</option>
                                            </select>
                                        </div>
                                        <span v-show="this.loading == true" class="btn btn-xs btn-primary"><i class="fa fa-cog fa-spin ml-2 "></i> Loading...</span>
                                        <span style="color:red" v-show="this.showError">Please select Project Member(s)</span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label>Image label (Optional)</label>
                                    <div class="form-group">
                                        <input type="file" class="dropify" name="image">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Project Description</label>
                                        <textarea name="" class="form-control" id="" cols="30" rows="9" placeholder="Put Description Here..." v-model="description" required></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <button type="submit" class="btn btn-primary">Create</button>
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
export default {
    mounted(){
        this.allMembers();
        this.projectID();
        $('#members').on('change',(event)=>{
            var array; 
            $(event.target).children(':selected').each(()=>{
                array = $(event.target).val();
            }); 
            // console.log(array);
            this.selectedmembers(array);
        });
        
    },
    props:[],
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
            image:[],
            description:null,
            showError:false,
        }
    },
    methods:{

        allMembers(){
            this.loading = true;
            axios.post('project/allmembers')
            .then(response => {
                this.members = response.data;
                this.loading = false;
            });
        },

        createProject(){
            if (this.team.length < 1) {
                setTimeout(() => {
                    this.showError = true
                }, 3000);
            }else{
                axios.post('project', {
                    title:this.title,
                    category:this.category,
                    priority:this.priority,
                    dateFrom:this.dateFrom,
                    dateTo:this.dateTo,
                    code:this.projectCode,
                    description:this.description,
                    team:this.team
                })
                .then(response=>{
                    this.succcess();
                    this.clearForm();
                    console.log(response.data);
                })
            }
        },

        selectedmembers(data){
            this.team.push(data);
            console.log(data);
        },

        projectID(){
            var id = Math.floor(Math.random()*99999) + 1;
            this.projectCode = 'PCS'+id+'project';
        },

        clearForm(){
            this.title = null,
            this.category = null,
            this.priority = null,
            this.dateFrom = null,
            this.dateTo = null,
            this.projectCode = null,
            this.description = null,
            this.team = []
        }, 

        succcess(){
            swal({
                title:"Success!",
                text: "New Project Created Successfully!",
                type: "success",
            }, function(){
                window.location.reload(true);
            });
            // swal("Success!", "", "success");
        }
    },


}
</script>
