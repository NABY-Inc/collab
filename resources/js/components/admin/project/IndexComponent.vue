<template>
    <div class="tab-pane fade" id="Project-add" role="tabpanel">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="allMembers" enctype="multipart/form-data">
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
                                        <div class="input-daterange input-group" data-provide="datepicker">
                                            <input type="text" class="form-control" name="start" placeholder="Start Date">
                                            <span class="input-group-addon"> to </span>
                                            <input type="text" class="form-control" name="end" placeholder="Ending Date">
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
                                            <select multiple="multiple" placeholder="Hello  im from placeholder" onchange="console.log($(this).children(':selected').length)" class="search_test" style="width:170%">
                                                <option v-for="member in this.members" :key="member.id" :value="member.id">{{member.name}}</option>
                                            </select>
                                        </div>
                                        <span v-show="this.loading == true" class="btn btn-xs btn-primary"><i class="fa fa-cog fa-spin ml-2 "></i> Loading...</span>
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
                                        <label>Prject Description</label>
                                        <textarea name="" class="form-control" id="" cols="30" rows="9" placeholder="Put Description Here..." v-model="description"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                    <button type="submit" class="btn btn-default">Cancel</button>
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
            description:[]
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

        projectID(){
            var id = Math.floor(Math.random()*99999) + 1;
            this.projectCode = 'PCS'+id+'project';
        }
    },


}
</script>
