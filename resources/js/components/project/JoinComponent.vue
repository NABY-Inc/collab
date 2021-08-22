<template>
  <div class="modal fade" id="joinProject" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <form>
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="title" id="defaultModalLabel">Join Project</h6>
                    </div>
                    <form @submit.prevent="joinProject()">
                        <div class="modal-body">
                            <div class="row clearfix">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" id="code" class="form-control" placeholder="Enter Project ID" autofocus required v-model="code">
                                        <small style="color: blue">Project ID's are case sensitive. Type them as giving.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" v-if="!isLoading" class="btn btn-primary">Join</button>
                            <button type="button" v-else class="btn btn-primary" disabled><i class="fa fa-spinner fa-spin"></i> Validating...</button>
                            <button type="button" class="btn btn-default" @click="code=null" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    data(){
        return{
            code:null,
            isLoading:false
        }
    },

    computed:{
        ...mapState('project',['error_join'])
    },

    watch:{
        error_join(){
            return this.error_join
        }
    },

    methods:{
        joinProject(){
            this.isLoading = true
            if (!code) {
               swal('Error!','Please Enter Project Code','error') 
                this.isLoading = false
            } else {
                this.$store.dispatch('project/joinProject',[this.code,this.$parent.user_role]).then(()=>{
                    if (this.error_join == 111) {
                        swal('Oops!','You are already part of this project','error')
                    } else if(this.error_join == 212) {
                        swal('Error!','INVALID PROJECT CODE','error')
                    } else {
                        swal('Successful!','You have been added to the Project.','success')
                        $('#joinProject').modal('hide')
                        this.code = null
                    }
                    this.isLoading = false
                })
            }
        }
    }
}
</script>

<style>

</style>