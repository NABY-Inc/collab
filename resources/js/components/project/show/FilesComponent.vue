<template>
  <div class="tab-pane fade" id="files" role="tabpanel">
      <div class="section-body mt-3">
            <div class="container-fluid">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-options d-flex" v-if="project.user_id == user_id">
                                    <a href="#" data-toggle="modal" data-target="#uploadFile" class="btn btn-primary ml-2">Upload New</a>
                                </div>
                                <div class="page-subtitle float-right">Total Files | {{files.length}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-cards" v-if="files.length > 0">
                    <div class="col-sm-6 col-lg-4" v-for="file in files" :key="file.id">
                        <div class="card p-3">
                            <a href="javascript:void(0)" class="mb-2" v-if="file.file.split('.').pop().toLowerCase() == 'jpg' || file.file.split('.').pop().toLowerCase() == 'png' || file.file.split('.').pop().toLowerCase() == 'jpeg'">
                                <img id="img" @click="showImg(domain+'uploads/project_files/'+file.file)" :src="domain+'uploads/project_files/'+file.file" alt="PCS_FILE_IMAGE" class="rounded" height="100%" width="100%">
                            </a>
                            <a v-else>
                                <div class="file_folder mr-2">
                                    <a href="javascript:void(0);">
                                        <div class="icon">
                                            <i class="fa fa-lg fa-file-o text-success"></i>
                                        </div>
                                        <div class="file-name">
                                            <p class="mt-3 text-muted">{{file.file}}</p>
                                            <a :href="project.id + '/downloadFile/'+file.file"><i class="fa fa-download"> Download</i></a>
                                        </div>
                                    </a>
                                </div>
                            </a>
                            <div class="d-flex align-items-center px-2">
                                <img class="avatar avatar-md mr-3" :src="domain+'uploads/users/'+file.user.profile" alt="">
                                <div>
                                    <div>{{file.user.name}}</div>
                                    <small class="d-block text-muted">{{moment(file.created_at).fromNow()}}</small>
                                </div>
                                <div class="ml-auto text-muted">
                                    <a v-if="file.user_id == user_id" href="javascript:void(0)" @click="deleteFile(file)" class="icon d-none d-md-inline-block ml-3"><i class="fa fa-trash-o mr-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Share Note -->
        <div class="modal fade" data-backdrop="static" id="uploadFile" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="justify-content:center !important">
                        <h6 class="title">Upload New File(s)</h6>
                    </div>
                    <form @submit.prevent="upload()">
                        <div class="modal-body">
                            <div class="row clearfix">
                                <div class="col-lg-12">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group">
                                                <label>Select File <span class="text-danger">*</span></label>
                                                <input id="uploadInput" type="file" class="form-control" multiple @change="onFileChange">
                                            </div>
                                            <small v-if="error" class="text-danger mt-2 ml-2">Please Attach a File!</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" v-if="!isLoading" class="btn btn-sm btn-primary float-right mb-2">Upload</button>
                            <button type="button" v-else class="btn btn-sm btn-primary float-right mb-2" disabled><i class="fa fa-spinner fa-spin"></i> please wait...</button>
                            <button type="button" class="btn btn-sm btn-danger float-right mr-2 mb-2" @click="attachments=[]" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- The Modal to Display Discussion Image -->
        <div class="modal fade" id="filesImageModal" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-center modal-lg" role="document">
                <div class="modal-content">
                    <img height="200%" width="200%" class="img-responsive" :src="img">
                </div>
            </div>
        </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
var moment = require('moment');
export default {
    data(){
        return{
            isLoading:false,
            moment:moment,
            img:null,
            attachments:[],
            error:false
        }
    },
    props:['domain','user_id'],
    computed:{
        ...mapState('project',['project']),
        ...mapState('file',['files'])
    },
    created(){
        this.$store.dispatch('file/getAll',this.project.id)
    },
    watch:{
        error(){
            if (this.error) {
                setTimeout(() => {
                    this.error = false
                }, 3000);
            }
        }
    },
    methods:{
        setFileImg(event){
            event.target.src = this.domain+'uploads/project_files/file_default.png'
            event.target.height = 100
            event.target.width = 100
        },
        showImg(img){
            this.img = img
            $('#filesImageModal').modal('show');
        },
        onFileChange(e){
            this.attachments = []; // Emptying files array this will reset it
            var selectedFiles = e.target.files;
            for (let i = 0; i < selectedFiles.length; i++) {
                if(selectedFiles[i].size > 3145728){
                    swal("Oops! Somthing went wrong","Selected File(s) should be less than 3MB","error")
                    document.getElementById("messageFiles").value = "" // Emptying file input
                    this.attachments = [] // Emptying image value
                    $('#uploadInput').val('')
                }else{
                    this.attachments.push(selectedFiles[i]);
                }
            }
        },
        upload(){
            this.isLoading = true
            if (this.attachments.length < 1) {
                this.error = true
                this.isLoading = false
            } else {
                var fdata  = new FormData
                fdata.append('project_id',this.project.id)
                for(let file of this.attachments){
                    fdata.append('attachments[]', file);
                }
                this.$store.dispatch('file/upload',fdata).then(()=>{
                    $('#uploadFile').modal('hide')
                    swal('Success!','File(s) Uploaded!.','success')
                    this.attachments = []
                    this.isLoading = false
                    $('#uploadInput').val('')
                })
            }
        },
        deleteFile(file){
            var that = this
            swal({
                title: "Delete File",
                text: "You won't be able to recover. Are you sure of this?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, delete!',
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            }, function () {
                that.$store.dispatch('file/delete',{
                    project_id:that.project.id,
                    id:file.id,
                    file:file.file,
                }).then(()=>{
                    swal('Success!','File Deleted!.','success')
                })
            });
        }
    }
}
</script>

<style scoped>
    #img{
        cursor: pointer;
        transition: 0.3s;
    }
    #img:hover {opacity: 0.7;}
</style>