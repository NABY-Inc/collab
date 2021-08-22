<template>
<div>
    <!-- Create Modal -->
    <div class="modal fade" id="createAnnouncement" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="justify-content:center !important">
                    <h6 class="title" id="defaultModalLabel">Create Announcement</h6>
                </div>
                <form @submit.prevent="create()">
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-lg-12">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Title here" name="name" v-model="title" required autofocus autocomplete>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Announcement Text <span class="text-danger">*</span></label>
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
                    </div>
                    <div class="modal-footer">
                        <button type="submit" v-if="!isLoading" class="btn btn-primary">Create</button>
                        <button type="button" v-else class="btn btn-primary" disabled><i class="fa fa-spinner fa-spin"></i> creating...</button>
                        <button type="button" class="btn btn-default" @click="description=null,title=null" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editAnnouncement" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="justify-content:center !important">
                    <h6 class="title" id="defaultModalLabel">Edit Announcement</h6>
                </div>
                <form @submit.prevent="update()">
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-lg-12">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Title here" name="name" v-model="editTitle" required autofocus autocomplete>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Announcement Text <span class="text-danger">*</span></label>
                                            <vue-editor 
                                                :editorToolbar="editorOptions"
                                                required
                                                v-model="editDescription">
                                            </vue-editor>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" v-if="!isLoading" class="btn btn-primary">Update</button>
                        <button type="button" v-else class="btn btn-primary" disabled><i class="fa fa-spinner fa-spin"></i> updating...</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn text-white" @click="deleteAnnouncement()" style="float;left !important; background-color:red">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import { VueEditor } from "vue2-editor";
import { mapState } from "vuex";
const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],  
    ['blockquote','link','clean'],

    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent

    [{ 'header': [1, 2, 3, 4, 5, 6, false] }], [{ 'color': [] }], [{ 'font': [] }],
    [{ 'align': [] }],
];
export default {
    components:{
        VueEditor
    },
    data(){
        return{
            isLoading:false,
            title:null,
            description:null,
            editorOptions: toolbarOptions,
            editTitle:null,
            editDescription:null,

        }
    },
    computed:{
        ...mapState('project',['project']),
        ...mapState('announcement',['selectedAnnouncement'])
    },

    watch:{
        selectedAnnouncement(){
            this.editTitle = this.selectedAnnouncement.title
            this.editDescription = this.selectedAnnouncement.description
        }
    },

    methods:{
        create(){
            this.isLoading = true
            if (!this.title || !this.description) {
                swal('Oops!','Title and Announcement Text Required!','error')
                this.isLoading = false
            } else {
                var fdata = new FormData
                fdata.append('title',this.title)
                fdata.append('description',this.description)
                fdata.append('project_id',this.project.id)
                this.$store.dispatch('announcement/create',fdata).then(()=>{
                    swal('Successful','New Announcement Created!','success')
                    $('#createAnnouncement').modal('hide')
                    this.title = null
                    this.description = null
                    this.isLoading = false
                })
            }
        },

        update(){
            this.isLoading = true
            if (!this.editTitle || !this.editDescription) {
                swal('Oops!','Title and Announcement Text Required!','error')
                this.isLoading = false
            } else {
                var fdata = new FormData
                fdata.append('title',this.editTitle)
                fdata.append('description',this.editDescription)
                fdata.append('project_id',this.project.id)
                fdata.append('id',this.selectedAnnouncement.id)
                this.$store.dispatch('announcement/update',fdata).then(()=>{
                    swal('Successful','Announcement Updated!','success')
                    $('#editAnnouncement').modal('hide')
                    this.isLoading = false
                })
            }
        },

        deleteAnnouncement(){
            var that = this
            swal({
                title: 'Delete Announcement.',
                text: 'This will permanently delete Announcement. You sure of this ?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: "Yes Delete!",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            }, function () {
                var fdata = new FormData
                fdata.append('project_id',that.project.id)
                fdata.append('id',that.selectedAnnouncement.id)
                that.$store.dispatch('announcement/delete',fdata).then(()=>{
                    swal('Successful','Announcement Deleted!','success')
                    $('#editAnnouncement').modal('hide')
                })
            });

        }
    }
}
</script>