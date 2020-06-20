<template>
<div class="card">
    <div class="card-body">
        <form @submit.prevent="createPost">
            <div class="new_post">
                <div class="form-group">
                    <small v-show="errorTitle" style="color:red">Post Title Required</small>
                    <input type="text" class="form-control" placeholder="Enter Post Title here" v-model="title" required>
                </div>
                <div class="form-group">
                    <small v-show="errorMessage" style="color:red">Post Message</small>
                    <textarea rows="4" class="form-control no-resize" placeholder="Please type what you want... " v-model="message" required></textarea>
                </div>
                <div class="row clearfix">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="file" ref="postFiles" class="form-control" @change="onFileChange" multiple>
                            <small for="">Upload File (optional)</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-right">
                            <a href="" v-show="this.changeToUpdate" v-if="edit_id !== null" class="btn btn-default" @click.prevent="deletePost()" style="background-color:red;color:white"><i class="fa fa-trash"></i> Delete</a>
                            <button type="submit" :class="this.changeToUpdate ? 'btn btn-info':'btn btn-primary'">{{this.changeToUpdate ? 'Update':'Post'}}</button>
                            <a href="" v-show="this.changeToUpdate" class="btn btn-default" 
                                    @click.prevent="title = null;message = null; changeToUpdate = false; edit_id = null">Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <p v-show="this.success" class="text-center label label-success font-18" style="color:green">New Post Created!</p>
        <p v-show="this.successEdit" class="text-center label label-success font-18" style="color:green">Post Updated!</p>
        <p v-show="this.successDelete" class="text-center label label-success font-18" style="color:green">Post Deleted!</p>
    </div>
</div>
</template>

<script>
export default {
    mounted(){
        
    },
    props:["id"],
    data(){
        return{
            title:null,
            message:null,
            attachments:[],
            errorTitle:false,
            errorMessage:false,
            success:false,
            successEdit:false,
            successDelete:false,
            edit_id:null,
            changeToUpdate:false
        }
    },
    created(){
        this.$eventBus.$on('edit-post', (parameters)=>{
            this.title = parameters.title
            this.message = parameters.message
            this.edit_id = parameters.id
            this.changeToUpdate = true
        });
    },
    methods:{

        onFileChange(e){
            this.attachments = []; // Emptying files array this will reset it
            var selectedFiles = e.target.files;
            for (let i = 0; i < selectedFiles.length; i++) {
                this.attachments.push(selectedFiles[i]);
            }
            console.log(this.attachments);
        },

        createPost(){
            if (this.title == null) {
                this.errorTitle = true;
                setTimeout(() => {
                    this.errorTitle = false;
                }, 3000);
            }else if(this.message == null){
                this.errorMessage = true;
                setTimeout(() => {
                    this.errorMessage = false;
                }, 3000);
            }else{
                // When its for update
                if (this.edit_id !== null) {
                    this.updatePost()
                }
                // When its for creating
                else{
                    var fdata = new FormData();
                        // If user selects a file, we attach to the attachement and save
                        if(this.attachments.length > 0){  
                            for(let file of this.attachments){
                                fdata.append('uploads[]', file);
                            }
                        }
                        fdata.append('title', this.title);
                        fdata.append('message', this.message);

                    axios.post(this.id + '/post', fdata)
                    .then( response => {
                        if (response.data == true) {
                            this.success = true;
                            setTimeout(() => {
                                this.success = false;
                            }, 5000);
                            this.title = null;
                            this.message = null;
                            this.changeToUpdate = false;
                            this.$eventBus.$emit('newPostIn');
                            this.$refs.postFiles.value = null;
                            console.log(response.data);
                        }else{
                            this.error();
                        }
                    }).catch(err=>{this.err()});
                }
            }
        },

        updatePost(){
            axios.post(this.id + '/post',{
                title:this.title,
                message:this.message,
                message:this.message,
                edit_id:this.edit_id,
            })
            .then(response=>{
                this.successEdit = true;
                setTimeout(() => {
                    this.successEdit = false;
                }, 5000);
                this.title = null;
                this.message = null;
                this.edit_id = null
                this.changeToUpdate = false;
                this.$eventBus.$emit('newPostIn');
                // this.$emit('post-created')
                // console.log(response.data);
            })
        },

        deletePost(){
            var  that = this;
            swal({
                    title: "Warning!",
                    text: 'Are You sure you want to delete Post ?',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, delete!',
                    closeOnConfirm: false
                }, function () {
                    axios.post(that.id + '/deletePost',{
                        edit_id:that.edit_id,
                    })
                    .then(response=>{
                        that.successDelete = true;
                        setTimeout(() => {
                            that.successDelete = false;
                        }, 5000);
                        that.title = null;
                        that.message = null;
                        that.changeToUpdate = false;
                        that.edit_id = null;
                        that.$eventBus.$emit('newPostIn');
                        swal.close();
                        // this.$emit('post-created')
                        console.log(response.data);
                    })
                });
        },

        error(){
            swal({
                title:"Oops Something went wrong!",
                text: "File should be an image,MicroSoft Suite Format or Zip with size not more than 10MB",
                type: "error"
            });
        }


    },


}
</script>
