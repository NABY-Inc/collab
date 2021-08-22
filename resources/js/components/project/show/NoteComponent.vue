<template>
  <div class="tab-pane fade" id="note" role="tabpanel">
      <div class="section-body mt-3">
            <div class="container-fluid">
                
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="card bg-primary">
                            <a href="#" data-toggle="modal" data-target="#addNote">
                                <div class="card-body">
                                    <div class="card-status"></div>
                                    <div class="text-white">
                                        <h5 class="mb-0 text-center" style="padding:5%"><i class="fa fa-plus"></i> Add New</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12">
                        <div class="row" v-if="allNotes.length > 0">
                            <div class="col-lg-4 col-md-6 col-sm-12" v-for="note in allNotes" :key="note.id">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-status bg-blue"></div>
                                        <div class="mb-2">
                                            <h5 class="mb-0">{{note.title}}</h5>
                                            <p class="text-muted"><sup>owner</sup>@ {{note.user.name}}</p>
                                            <span v-html="note.text"></span>
                                        </div>
                                        <span class="font-12 text-muted">Note Shared with</span>
                                        <ul class="list-unstyled team-info margin-0 pt-2">
                                            <li v-for="member in note.note_members" :key="member.id">
                                                <img :src="domain+'uploads/users/'+member.user.profile" alt="Avatar" data-toggle="tooltip" title="" :data-original-title="member.user.name">
                                            </li>
                                            <li v-if="note.user_id == user_id" @click="showShare(note)"><span data-toggle="tooltip" title="Share" data-original-title="Share" class="avatar avatar-sm"><i class="fa fa-share"></i></span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="">
                            <p class="mt-30 ml-20">Note is empty.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Create Note -->
        <div class="modal fade" id="addNote" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary" style="justify-content:center !important">
                        <h6 class="title text-white">Add New Note</h6>
                    </div>
                    <form @submit.prevent="create()">
                        <div class="modal-body">
                            <div class="row clearfix">
                                <div class="col-lg-12">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group">
                                                <label>Note Heading <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" placeholder="Enter Title here" name="name" v-model="title" required autofocus autocomplete>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group">
                                                <label>Note <span class="text-danger">*</span></label>
                                                <vue-editor 
                                                    :editorToolbar="editorOptions"
                                                    required
                                                    v-model="text">
                                                </vue-editor>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-primary">
                            <button type="button" class="btn bg-danger" @click="description=null,title=null" data-dismiss="modal">Cancel</button>
                            <button type="submit" v-if="!isLoading" class="btn bg-white">Create</button>
                            <button type="button" v-else class="btn btn-primary" disabled><i class="fa fa-spinner fa-spin"></i> creating...</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Share Note -->
        <div class="modal fade" data-backdrop="static" id="shareNote" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="justify-content:center !important">
                        <h6 class="title">Share Note</h6>
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
                            <button type="submit" v-if="!isLoading" class="btn btn-sm btn-primary float-right mb-2">Share</button>
                            <button type="button" v-else class="btn btn-sm btn-primary float-right mb-2" disabled><i class="fa fa-spinner fa-spin"></i> please wait...</button>
                            <button type="button" class="btn btn-sm btn-danger float-right mr-2 mb-2" @click="description=null,title=null" data-dismiss="modal">Cancel</button>
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
import Multiselect from 'vue-multiselect'
const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],  
    ['blockquote','clean'],

    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent

    [{ 'header': [1, 2, 3, 4, 5, 6, false] }], [{ 'color': [] }], [{ 'font': [] }],
    [{ 'align': [] }],
];
export default {
    components:{
        VueEditor,
        Multiselect
    },

    data(){
        return{
            isLoading:false,
            title:null,
            text:null,
            editorOptions: toolbarOptions,
            team:[],
            members:[],
            note_id:null
        }
    },
    props:['domain','user_id'],

    created(){
        this.$store.dispatch('note/getAll',this.project.id)
        this.project.members.forEach(element => {
            this.members.push({id:element.user.id,name:element.user.name})
        });
    },

    computed:{
        ...mapState('project',['project']),
        ...mapState('note',['notes']),
        allNotes(){
            var notes = this.notes.filter(note => (note.note_members.some(member => member.user_id == this.user_id)))
            return notes
        }
    },

    watch:{
    },

    methods:{
        create(){
            this.isLoading = true
            if (!this.title || !this.text) {
                swal('Oops!','Title and Note Text Required!','error')
                this.isLoading = false
            } else {
                var fdata = new FormData
                fdata.append('title',this.title)
                fdata.append('text',this.text)
                fdata.append('project_id',this.project.id)
                this.$store.dispatch('note/create',fdata).then(()=>{
                    swal('Successful','Note Created!','success')
                    $('#addNote').modal('hide')
                    this.title = null
                    this.text = null
                    this.isLoading = false
                })
            }
        },
        showShare(note){
            this.team = []
            $('#shareNote').modal('show')
            this.note_id = note.id
            
            note.note_members.forEach(element => {
                this.team.push({id:element.user.id,name:element.user.name})
            });
        },
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
                fdata.append('project_note_id',this.note_id)
                fdata.append('members',ids)
                fdata.append('project_id',this.project.id)
                this.$store.dispatch('note/saveNoteMembers',fdata).then(()=>{
                    swal('Successful','Note Shared!','success')
                    $('#shareNote').modal('hide')
                    this.team = []
                    this.isLoading = false
                })
            }
        }
    }
}
</script>

<style>

</style>