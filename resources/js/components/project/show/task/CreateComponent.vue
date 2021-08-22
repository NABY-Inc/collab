<template>
    <div class="modal fade" data-backdrop="static" id="addTask" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header" style="justify-content:center !important">
                    <h6 class="title">Add Task</h6>
                </div>
                <form @submit.prevent="save()">
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-lg-12">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Task Title" v-model="title">
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>Start Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" v-model="start">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>Due Date <small>(optional)</small></label>
                                            <input type="date" class="form-control" v-model="due">
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Share with <small>(optional)</small></label>
                                            <multiselect v-model="team" placeholder="Select member(s)" 
                                            :options="members" :multiple="true" :close-on-select="false" 
                                            :preserve-search="true" label="name" track-by="name" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Description <span class="text-danger">*</span></label>
                                            <vue-editor 
                                                :editorToolbar="editorOptions"
                                                required
                                                v-model="description">
                                            </vue-editor>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" v-if="!isLoading" class="btn btn-sm btn-primary float-right mb-2">Submit</button>
                        <button type="button" v-else class="btn btn-sm btn-primary float-right mb-2" disabled><i class="fa fa-spinner fa-spin"></i> please wait...</button>
                        <button type="button" class="btn btn-sm btn-danger float-right mr-2 mb-2" @click="reset()" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { VueEditor } from "vue2-editor";
import Multiselect from 'vue-multiselect'
const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],  
    ['blockquote','clean'],

    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent

    [{ 'header': [1, 2, 3, 4, 5, 6, false] }], [{ 'color': [] }], [{ 'font': [] }],
    [{ 'align': [] }],
];
export default {
    components:{
        VueEditor,
        Multiselect,
    },

    data(){
        return{
            isLoading:false,
            title:null,
            description:null,
            start:null,
            due:null,
            editorOptions: toolbarOptions,
            team:[],
            members:[],
        }
    },

    created(){
        this.$parent.$parent.project.members.forEach(element => {
            this.members.push({id:element.user.id,name:element.user.name})
        });
    },

    methods:{
        save(){
            this.isLoading = true
            if (!this.title || !this.description || !this.start) {
                swal('Oops!','Title, Start Date and Description Required','error')
                this.isLoading = false
            } else {
                var ids=[];
                this.team.forEach(element => {
                   ids.push(element.id)
                });
                var fdata = new FormData
                fdata.append('title',this.title)
                fdata.append('project_id',this.$parent.$parent.project.id)
                fdata.append('members',ids)
                fdata.append('start',this.start)
                fdata.append('due',this.due)
                fdata.append('description',this.description)
                this.$store.dispatch('task/create',fdata).then(()=>{
                    this.isLoading = false
                    swal('Success','Task Added Successfully','success')
                    this.reset()
                    $('#addTask').modal('hide')
                })
            }
        },

        reset(){
            this.title = null
            this.start = null
            this.due = null
            this.description = null
            this.team = null
        }
        
    }
}

</script>

<style>

</style>