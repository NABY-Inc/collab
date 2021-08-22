<template>
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="addUser()">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="name">Username</label>
                                    <input type="text" class="form-control" placeholder="Enter Name Here" name="name" required autofocus autocomplete v-model="userData.name">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <label for="contact">Mobile Contact</label>
                                <div class="form-group">
                                    <input type="number" class="form-control" placeholder="Mobile Contact Here" name="contact" required v-model="userData.contact">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <label for="email">Email</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Enter Email Here" name="email" required v-model="userData.email">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="role">Select Role</label>
                                    <select class="form-control" name="role" required v-model="userData.role"> 
                                        <option value="" selected disabled>-- Select an option --</option>
                                        <option value="0">User</option>
                                        <option value="1">Administrator</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label for="role">Insert Profile Picture (optional)</label>
                                <input type="file" class="form-control" id="profile" name="profile" @change="onImageChange">
                            </div>
                            <div class="col-lg-12 mt-3">
                                <button v-if="!isLoading" type="submit" class="btn btn-primary">Create</button>
                                <button v-else class="btn btn-primary" disabled><i class="fa fa-spinner fa-spin"></i> Saving...</button>
                                <button type="submit" class="btn btn-default">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            isLoading:false,
            userData:this.inputFields()
        }
    },

    methods:{
        onImageChange(e){
            if (!this.validateFile('profile', ['.jpg','.png','.jpeg','.JPG','.PNG','.JPEG'])) {
                swal("Oops! Invalid Image","Please select an image with extension .jpg, .png, or .jpeg!","error")
                document.getElementById("profile").value = "" // Emptying file input
                this.userData.profile = null // Emptying data value
            }else{
                var img = e.target.files[0]
                if(img.size > 3145728){
                    swal("Oops! Somthing went wrong","Image should be less than 3MB","error")
                    document.getElementById("profile").value = "" // Emptying file input
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

        addUser(){
            this.isLoading = true
            if (!this.userData.name || !this.userData.contact || !this.userData.email) {
                swal('Error!','Username, Contact and Email required!','error')
                this.isLoading = false
            } else {
                var fdata = new FormData
                fdata.append('name',this.userData.name)
                fdata.append('email',this.userData.email)
                fdata.append('contact',this.userData.contact)
                fdata.append('role',this.userData.role)
                fdata.append('profile',this.userData.profile)

                this.$store.dispatch('user/create',fdata).then(()=>{
                    swal('Success!','User account created.','success')
                    this.isLoading = false
                    this.resetFields() // reseting fields
                })
            }
        },
        inputFields(){
            return{
                name:null,
                contact:null,
                email:null,
                role:null,
                profile:null,
            }
        },
        resetFields(){
            this.userData.name      = null
            this.userData.contact   = null
            this.userData.email     = null
            this.userData.role      = null
            this.userData.profile   = null
        }
    }
}
</script>

<style>

</style>