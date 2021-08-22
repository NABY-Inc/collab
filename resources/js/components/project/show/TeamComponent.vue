<template>
    <div class="tab-pane fade" id="team" role="tabpanel">
        <div class="section-body mt-3">
            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-xl-3 col-lg-3 col-md-6" v-for="member in project.members" :key="member.id">
                        <div class="card">
                            <div class="card-body text-center ribbon">
                                <div :class="user_id == member.user.id ? 'ribbon-box green':'ribbon-box orange'">
                                    {{user_id == member.user.id ? 'Project manager':'Team Member'}}</div>
                                <img class="rounded-circle img-thumbnail w100 mt-5" :src="domain+'/uploads/users/'+member.user.profile" alt="">
                                <h6 class="mt-3 mb-0">{{member.user.name}}</h6>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    props:['domain','user_id'],

    computed:{
        ...mapState('project',['project']),
    },
}
</script>

<style>

</style>