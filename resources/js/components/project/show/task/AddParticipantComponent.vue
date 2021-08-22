<template>
  <div class="modal fade" data-backdrop="static" id="addParticipants" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="justify-content:center !important">
                    <h6 class="title">Share Task</h6>
                </div>
                <form @submit.prevent="share()">
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-lg-12">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Share with <span class="text-danger">*</span></label>
                                            <multiselect v-model="team" placeholder="Select member(s)" 
                                            :options="members" :multiple="true" :close-on-select="false" 
                                            :preserve-search="true" label="name" track-by="name" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" v-if="!isLoading" class="btn btn-sm btn-primary float-right mb-2">Update Members</button>
                        <button type="button" v-else class="btn btn-sm btn-primary float-right mb-2" disabled><i class="fa fa-spinner fa-spin"></i> please wait...</button>
                        <button type="button" class="btn btn-sm btn-danger float-right mr-2 mb-2" @click="team=[]" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
export default {
    components:{
        Multiselect,
    },
    data(){
        return{
            isLoading:false,
            members:[],
            team:[],
            task_id:null
        }
    },
    created(){
        this.$parent.$parent.project.members.forEach(element => {
            this.members.push({id:element.user.id,name:element.user.name})
        });
    },
    methods:{
        share(){
            this.isLoading = true
            if (!this.team) {
                swal('Oops!','Please select member to share with!','error')
                this.isLoading = false
            } else {
                var ids=[];
                this.team.forEach(element => {
                   ids.push(element.id)
                });
                var fdata = new FormData
                fdata.append('project_task_id',this.task_id)
                fdata.append('members',ids)
                fdata.append('project_id',this.$parent.$parent.project.id)
                this.$store.dispatch('task/saveTaskMembers',fdata).then(()=>{
                    swal('Successful','Task Members updated!','success')
                    $('#addParticipants').modal('hide')
                    this.team = []
                    this.isLoading = false
                })
            }
        },
    }

}
</script>

<style>

</style>