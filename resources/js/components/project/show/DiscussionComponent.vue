<template>
  <div class="tab-pane fade" id="discuss" role="tabpanel">
        <div class="section-body mt-3">
            <div class="container-fluid">
                <div class="section-light py-3 chat_app">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="card bg-none b-none">
                                    <!-- <div class="card-header bline bg-none"> -->
                                        <!-- <h3 class="card-title">Project Discussion <small>{{project.members.length}} Team Members</small></h3>
                                        <div class="card-options">
                                            <a href="javascript:void(0)" class="p-1"><i class="fa fa-refresh"></i></a>
                                            <a href="javascript:void(0)" class="p-1 chat_list_btn"><i class="fa fa-align-right"></i></a>
                                        </div> -->
                                    <!-- </div>                         -->
                                    <div class="chat_windows">
                                        <!-- Create Message -->
                                        <div class="chat-message clearfix">
                                            <a href="javascript:void(0);" @click="showFileSelector"><i class="fa fa-paperclip"></i></a>

                                            <a href="javascript:void(0);" @click="saveChat"><i class="fa fa-paper-plane"></i></a>
                                            <a href="#" @click="attachments = []" class="pull-right bg-red p-1 ml-2 text-white" v-if="attachments.length > 0">X</a>
                                            <span class="pull-right bg-light-red p-1" v-if="attachments.length > 0"> {{attachments.length}} Selected File(s)</span>
                                            <div class="input-group mb-0">
                                                <input type="text" @keyup.enter="saveChat" class="form-control" placeholder="Enter text here..." v-model="chatMessage">
                                            </div>

                                            <small v-if="error" class="text-danger mt-2 ml-2">Please Enter chat message or Attach a File!</small>
                                            <small v-if="success" class="mt-2 pull-right" style="color:green !important">Message Saved!</small>
                                        </div>
                                        <input type="file" multiple id="messageFiles" style="visibility:hidden;" @change="onFileChange" />
                                        
                                        <!-- Message Display Area -->
                                        <ul class="mb-0" v-if="discussions.length > 0">
                                            <li :class="chat.user.id == user_id ? 'my-message':'other-message'" v-for="chat in discussions" :key="chat.id">
                                                <img v-if="chat.user.id != user_id" class="avatar mr-3" :src="domain+'uploads/users/'+chat.user.profile" alt="avatar">
                                                <div class="message">
                                                    <div class="media_img row" v-if="chat.project_files.length > 0">
                                                        <span v-for="file in chat.project_files" :key="file.id">
                                                            <span v-if="file.file.split('.').pop().toLowerCase() == 'jpg' || file.file.split('.').pop().toLowerCase() == 'png' || file.file.split('.').pop().toLowerCase() == 'jpeg'">
                                                                <img id="img" :src="domain+'uploads/project_files/'+file.file" 
                                                                class="w200 img-thumbnail img-responsive mb-1 mr-2" @click="showImg(domain+'uploads/project_files/'+file.file)" />
                                                            </span>
                                                            <span v-else>
                                                                <div class="file_folder mr-2">
                                                                    <a href="javascript:void(0);">
                                                                        <div class="icon">
                                                                            <i class="fa fa-file-o text-success"></i>
                                                                        </div>
                                                                        <div class="file-name">
                                                                            <p class="mb-0 text-muted">{{file.file}}</p>
                                                                            <a :href="project.id + '/downloadFile/'+file.file"><i class="fa fa-download"> Download</i></a>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <p class="bg-light-blue" v-if="chat.chat != 'null'">{{chat.chat}}</p>
                                                    <span class="time" >{{moment(chat.created_at).format('h:mm A, dddd D MMM, YY')}}</span>
                                                    <a v-if="chat.user.id == user_id" href="#" @click="deleteChat(chat.id)"><i class="fa fa-trash"></i></a>
                                                </div>
                                            </li>       
                                        </ul>
                                        <ul v-else><p class="text-center mt-2">No Chat Made.</p></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>        
            </div>
        </div>

        <!-- The Modal to Display Discussion Image -->
        <div class="modal fade" id="imageModal" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-center modal-lg" role="document">
                <div class="modal-content">
                    <img height="200%" width="200%" class="img-responsive" :src="img" id="imgArea">
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { mapState } from "vuex";
var moment = require('moment');
export default {
    props:['domain','user_id'],
    data(){
        return{
            chatMessage:null,
            moment:moment,
            success:false,
            error:false,
            attachments:[],
            img:null
        }
    },
    computed:{
        ...mapState('project',['project']),
        ...mapState('discussion',['discussions'])
    },
    created(){
        this.$store.dispatch('discussion/getAll',this.project.id)
    },
    watch:{
        success(){
            if (this.success) {
                setTimeout(() => {
                    this.success = false
                }, 3000);
            }
        },
        error(){
            if (this.error) {
                setTimeout(() => {
                    this.error = false
                }, 3000);
            }
        }
    },
    methods:{
        showFileSelector(){
            $('#messageFiles').trigger('click');
        },
        onFileChange(e){
            this.attachments = []; // Emptying files array this will reset it
            var selectedFiles = e.target.files;
            for (let i = 0; i < selectedFiles.length; i++) {
                if(selectedFiles[i].size > 3145728){
                    swal("Oops! Somthing went wrong","Selected File(s) should be less than 3MB","error")
                    document.getElementById("messageFiles").value = "" // Emptying file input
                    this.attachments = [] // Emptying image value
                }else{
                    this.attachments.push(selectedFiles[i]);
                }
            }
        },

        saveChat(){
            if (!this.chatMessage && this.attachments.length < 1) {
                this.error = true
            }else{
                var fdata = new FormData
                fdata.append('project_id',this.project.id)
                fdata.append('message',this.chatMessage)
                // If user selects a file, we attach to the attachement and save
                if(this.attachments.length > 0){  
                    for(let file of this.attachments){
                        fdata.append('attachments[]', file);
                    }
                }
                this.$store.dispatch('discussion/create',fdata).then(()=>{
                    this.chatMessage = null
                    this.attachments = []
                })
            }
        },

        deleteChat(id){
            var fdata = new FormData
            fdata.append('project_id',this.project.id)
            fdata.append('id',id)
            this.$store.dispatch('discussion/delete',fdata)
        },

        showImg(img){
            this.img = img
            $('#imageModal').modal('show');
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
