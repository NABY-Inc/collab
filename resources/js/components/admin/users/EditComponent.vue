<template>
    <div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="title" id="defaultModalLabel">Update user profile | {{userData.name}}</h6>
                </div>
                <form @submit.prevent="updateProfile()">
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="text" id="username" class="form-control" placeholder="username" v-model="userData.name" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="number" id="contact" class="form-control" placeholder="Contact" v-model="userData.contact" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="email" id="email" class="form-control" placeholder="Email" v-model="userData.email" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <select class="form-control show-tick" v-model="userData.role" required>
                                        <option :value="user.role" selected>{{user.role == 1 ? 'Administrator' : 'User'}}</option>
                                        <option v-if="user.role == 1" value="2">User</option>
                                        <option v-else value="1">Administrator</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Reset Password</label>
                                    <select class="form-control show-tick" v-model="userData.resetPass" required>
                                        <option value="0" selected>No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label>Image</label>
                                <input type="file" class="form-control" id="userProfile" name="profile" @change="onImageChange">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-if="!isLoadingUpdate" type="submit" class="btn btn-primary">Update</button>
                        <button v-else class="btn btn-primary" disabled><i class="fa fa-spinner fa-spin"></i> Updating...</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    data(){
        return{
            isLoadingUpdate:false,
            resetPass:0,
            userData:this.updateFields()
        }
    },

    computed:{
        ...mapState('user',['user']),
    },

    watch:{
        user(){
            this.userData.name = this.user.name
            this.userData.contact = this.user.contact
            this.userData.email = this.user.email
            this.userData.role = this.user.role
        }
    },

    methods:{
        onImageChange(e){
            if (!this.validateFile('userProfile', ['.jpg','.png','.jpeg','.JPG','.PNG','.JPEG'])) {
                swal("Oops! Invalid Image","Please select an image with extension .jpg, .png, or .jpeg!","error")
                document.getElementById("userProfile").value = "" // Emptying file input
                this.userData.profile = null // Emptying data value
            }else{
                var img = e.target.files[0]
                if(img.size > 3145728){
                    swal("Oops! Somthing went wrong","Image should be less than 3MB","error")
                    document.getElementById("userProfile").value = "" // Emptying file input
                    this.userData.profile = null // Emptying data value
                }else{
                    this.userData.profile = img
                }
            }
        },

        validateFile(inputID, exts) {
            var fileName = document.getElementById(inputID).value;
            return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
        },

        updateProfile(){
            this.isLoadingUpdate = true
            if (!this.userData.name || !this.userData.contact || !this.userData.email) {
                swal('Error!','Username, Contact and Email required!','error')
                this.isLoadingUpdate = false
            } else {
                var fdata = new FormData
                fdata.append('name',this.userData.name)
                fdata.append('email',this.userData.email)
                fdata.append('contact',this.userData.contact)
                fdata.append('resetPass',this.userData.resetPass)
                fdata.append('role',this.userData.role)
                fdata.append('profile',this.userData.profile)

                this.$store.dispatch('user/update',{id:this.user.id, userData:fdata}).then(()=>{
                    swal('Success!','User account updated.','success')
                    this.isLoadingUpdate = false
                    this.userData.resetPass = 0 // Reseting reset password field
                    document.getElementById("userProfile").value = '';    // Reseting profile field
                    $('#editUser').modal('hide')
                })
            }
        },
        updateFields(){
            return{
                name:null,
                contact:null,
                email:null,
                role:null,
                resetPass:0,
                profile:null,
            }
        }
    }
}
</script>

<style>

</style>