<template>
  <div>
    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-md-flex justify-content-between">
                                <ul class="nav nav-tabs b-none">
                                    <li class="nav-item"><a class="nav-link active" id="list-tab" data-toggle="tab" href="#list"><i class="fa fa-list-ul"></i> List</a></li>
                                    <li class="nav-item"><a class="nav-link" id="grid-tab" data-toggle="tab" href="#grid"><i class="fa fa-th"></i> Grid</a></li>
                                    <li class="nav-item"><a class="nav-link" id="addnew-tab" data-toggle="tab" href="#addnew"><i class="fa fa-plus"></i> Add New User</a></li>
                                </ul>
                                <div class="d-flex align-items-center sort_stat">
                                    <div class="d-flex">
                                        <div class="ml-2">
                                            <p class="mb-0 font-11">Total Users showing on this page</p>
                                            <h5 class="font-16 mb-0 text-center">{{users.length}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-body">
        <div class="container-fluid">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="list" role="tabpanel">
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="table-responsive" id="users">
                                <table class="table table-hover table-vcenter text-nowrap table_custom border-style list">
                                    <tbody v-if="users.length > 0">
                                        <tr v-for="user in users" :key="user.id">
                                            <td class="width35 hidden-xs">
                                                <a href="javascript:void(0);" :class="user.active == 0 ? 'mail-star' :''" :title="user.active == 0 ? 'Inactive' : 'Active'">
                                                    <i class="fa fa-star"></i>
                                                </a>
                                            </td>
                                            <td class="text-center width40">
                                                <div class="avatar d-block">
                                                    <img class="avatar" :src="domain+'/uploads/users/'+user.profile" alt="avatar">
                                                </div>
                                            </td>
                                            <td>
                                                <div><a @click="showEditModal(user.id)" href="javascript:void(0);">{{user.name}}</a></div>
                                                <div class="text-muted">PCS00{{user.id}}</div>
                                            </td>
                                            <td class="hidden-xs">
                                                <div class="text-muted">{{user.email}}</div>
                                            </td>
                                            <td class="hidden-sm">
                                                <div class="text-muted">{{user.contact}}</div>
                                            </td>
                                            <td class="text-right actions">
                                                <a @click="showEditModal(user.id)" class="btn btn-sm btn-link" href="javascript:void(0)" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                <a @click="toggleUserActiveness(user.id,user.active)" class="btn btn-sm btn-link"  href="javascript:void(0)" data-toggle="tooltip" :data-original-title="user.active == 1 ? 'Deactivate' : 'Activate'"><i class="fa fa-ban"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td class="text-center">There is no registered user on the system.</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="grid" role="tabpanel">
                    <div class="row row-deck" v-if="users.length > 0">
                        <div class="col-lg-4 col-md-6 col-sm-12" v-for="user in users" :key="user.id">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="card-status bg-blue"></div>
                                    <div class="mb-3"> <img :src="domain+'uploads/users/'+user.profile" class="rounded-circle w100" alt=""> </div>
                                    <div class="mb-2">
                                        <h5 class="mb-0">{{user.name}}</h5>
                                        <p class="text-muted"><a @click="showEditModal(user.id)" href="javascript:void(0);">{{user.role == 1 ? 'Admin' : 'User'}} | PCS00{{user.id}}</a></p>
                                        <p class="text-muted">{{user.email}}</p>
                                        <span>Has been a member since {{moment(user.created_at).format('MMMM, YYYY')}}</span> <br/>
                                        <p :class="user.active == 1 ? 'tag-danger' : 'tag-success'">{{user.active == 1 ? 'Active' : 'Inactive'}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" v-else>
                        <div class="card-status bg-blue"></div>
                        <div class="mb-2 text-center">
                            <span>There is no registered user on the system.</span>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="addnew" role="tabpanel">
                    <create-user />
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing -->
    <edit-user />

  </div>
</template>

<script>
import { mapState } from "vuex";
import createUser from "./CreateComponent.vue";
import editUser from "./EditComponent.vue";
var moment = require('moment');
export default {
    components:{
        createUser,
        editUser
    },

    data(){
        return{
            moment:moment,
        }
    },

    props:['allusers','domain'],

    created(){
        this.$store.dispatch('user/all',this.allusers)
    },

    computed:{
        ...mapState('user',['users']),
    },

    methods:{
        showEditModal(id){
            this.$store.dispatch('user/enableUserEdit',id)
            $('#editUser').modal('show')
        },

        toggleUserActiveness(user_id,active){
            var that = this
            if (active == 1) {
                    var text = "User account is activated. Are you sure you want to deactivate ?";
                    var btnText = "Yes, deactivate!";
                    var btnColor = "#dc3545";
            }else{
                var text = "User account is deactivated. Are you sure you want to activate ?";
                var btnText = "Yes, activate!";
                var btnColor = "#21ba45";
            }
            swal({
                title: "Warning!",
                text: text,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: btnColor,
                confirmButtonText: btnText,
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            }, function () {
                that.$store.dispatch('user/toggleUserAccount',[user_id,active]).then(()=>{
                    swal('Success!','User account status changed.','success')
                })
            });
        }
    }
}
</script>