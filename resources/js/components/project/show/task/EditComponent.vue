<template>
    <div class="modal fade" data-backdrop="static" id="editTask" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header" style="justify-content:center !important">
                    <h6 class="title">Edit Task</h6>
                </div>
                <form @submit.prevent="update()">
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-lg-12">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Task Title" v-model="edit.title">
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>Start Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" v-model="edit.start">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>Due Date <small>(optional)</small></label>
                                            <input type="date" class="form-control" v-model="edit.due">
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
                                                v-model="edit.description">
                                            </vue-editor>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" v-if="!isLoading" class="btn btn-sm btn-primary float-right mb-2">Update</button>
                        <button type="button" v-else class="btn btn-sm btn-primary float-right mb-2" disabled><i class="fa fa-spinner fa-spin"></i> please wait...</button>
                        <button type="button" class="btn btn-sm btn-danger float-right mr-2 mb-2" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { VueEditor } from "vue2-editor";
import { mapState } from "vuex";
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
    },

    data(){
        return{
            isLoading:false,
            edit:this.inputData(),
            editorOptions: toolbarOptions,
        }
    },

    computed:{
        ...mapState('task',['selectedTask']),
    },

    watch:{
        selectedTask(selectedTask){
            this.edit.id = selectedTask.id
            this.edit.title = selectedTask.title
            this.edit.description = selectedTask.description
            this.edit.start = selectedTask.start
            this.edit.due = selectedTask.due
        }
    },

    methods:{
        update(){
            this.isLoading = true
            if (!this.edit.title || !this.edit.description || !this.edit.start) {
                swal('Oops!','Title, Start Date and Description Required','error')
                this.isLoading = false
            } else {
                var fdata = new FormData
                fdata.append('id',this.edit.id)
                fdata.append('title',this.edit.title)
                fdata.append('project_id',this.$parent.$parent.project.id)
                fdata.append('start',this.edit.start)
                fdata.append('due',this.edit.due)
                fdata.append('description',this.edit.description)
                this.$store.dispatch('task/update',fdata).then(()=>{
                    this.isLoading = false
                    swal('Success','Task Updated Successfully','success')
                    this.inputData()
                    $('#editTask').modal('hide')
                })
            }
        },

        inputData(){
            return{
                id:null,
                title : null,
                start : null,
                due : null,
                description : null,
            }
        },

    }
}

</script>

<style>

</style>