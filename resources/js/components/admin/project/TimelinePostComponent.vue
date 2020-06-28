<template>
    <div class="tab-pane fade" id="pills-timeline" role="tabpanel" aria-labelledby="pills-timeline-tab">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Activity</h3>
            </div>
            <div class="card-body" v-if="this.posts.length > 0">

                <div class="timeline_item " v-for="post in this.posts" :key="post.id">
                    <img class="tl_avatar" :src="userImg + post.user.img" alt="">
                    <span><a href="javascript:void(0);" title="">{{post.user.name}}</a> <small class="float-right text-right">{{moment(post.created_at).fromNow()}}</small></span>
                    <h6 class="font600">{{post.title}}</h6>
                    <div class="msg">
                        <p>{{post.message}}</p>
                        <div class="timeline_img row">
                            <div v-for="file in post.post_resources" :key="file.id" class="col-md-6 col-xs-6 col-lg-4 mt-3 mb-3">
                                <div v-if="file.file.split('.').pop() == 'jpg' || file.file.split('.').pop() == 'png' || file.file.split('.').pop() == 'jpeg'">
                                    <a :href="postFileUrl + file.file" target="_blank" data-toggle="tooltip" title="" data-original-title="View Image">
                                        <img class="w200 img-thumbnail img-responsive"  :src="postFileUrl + file.file" alt="Awesome Image">
                                    </a>
                                </div>
                            <div class="file_folder responsive mr-10" v-else>
                                <a href="javascript:void(0);">
                                    <div class="icon">
                                        <i class="fa fa-file-o text-success"></i>
                                    </div>
                                    <div class="file-name">
                                        <p class="mb-0 text-muted">{{file.file}}</p>
                                        <div>
                                            <i class="fa fa-download font-120" @click="downloadFile(file.file, 'post')"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            </div>
                        </div>
                        <a class="text-muted" role="button" data-toggle="collapse" href="#collapseExample1" aria-expanded="false" aria-controls="collapseExample1">
                            <i class="fa fa-comments text-blue"></i> {{post.comments.length > 0 ? post.comments.length : 0}} Comment
                        </a>
                        <!-- <a href="javascript:void(0);" class="ml-20 text-muted"><i class="fa fa-eye text-cyan"></i> show comments</a> -->
                        <div class="collapse p-4 section-gray show mt-3" id="collapseExample1">
                            <a href="" @click.prevent="showModal(post.user.name + '\'s post', post.id)"><i class="fa fa-plus"></i> Add Comment</a>
                            <ul class="recent_comments list-unstyled mt-4 mb-0" v-if="post.comments.length > 0">
                                <li v-for="comment in Object.assign([],post.comments).reverse()" :key="comment.id">
                                    <div class="avatar_img">
                                        <img class="rounded img-fluid" :src="userImg + comment.user.img" alt="">
                                    </div>
                                    <div class="comment_body">
                                        <h6>{{comment.user.name}} <a v-if="user_id === comment.user.id" @click.prevent="deleteComment(comment.id)" href="" class="fa fa-trash text-danger ml-2"></a> <small class="float-right font-14">{{moment(comment.created_at).fromNow()}}</small></h6>
                                        <p>{{comment.message}}</p>
                                        <div class="timeline_img row">
                                            <div v-for="file in comment.comment_resources" :key="file.id" class="col-md-6 col-xs-6 col-lg-4">
                                                <div v-if="file.file.split('.').pop() == 'jpg' || file.file.split('.').pop() == 'png' || file.file.split('.').pop() == 'jpeg'">
                                                    <a :href="commentFileUrl + file.file" target="_blank" data-toggle="tooltip" title="" data-original-title="View Image">
                                                        <img class="width150 img-responsive"  :src="commentFileUrl + file.file" alt="Awesome Image" height="80" width="50">
                                                    </a><br>
                                                    <a v-if="user_id === file.user_id" href="#" class="fa fa-trash mt-2" @click="deleteFile(file.id, file.file)"> Delete</a>
                                                </div>
                                            <div class="file_folder responsive mr-10" v-else>
                                                <a href="javascript:void(0);">
                                                    <div class="icon">
                                                        <i class="fa fa-file-o text-success"></i>
                                                    </div>
                                                    <div class="file-name">
                                                        <p class="mb-0 text-muted">{{file.file}}</p>
                                                        <div>
                                                            <i class="fa fa-download font-120" @click="downloadFile(file.file, 'comment')"></i>
                                                            <i v-if="user_id === file.user_id" class="fa fa-trash pull-right" @click="deleteFile(file.id, file.file)"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                            </div>
                                        </div>

                                    </div>
                                </li>
                            </ul>
                            <p class="tex-center mt-3" v-else>No comment(s) on this post.</p>
                        </div>
                    </div>
                </div>
                <!-- Pagination from here | We want to hide this at first display -->
            <hr>
            </div>
            <p v-else class="text-center mt-3">Empty Timeline Activities</p>
            <div v-if="pagination.prev_page_url != null || pagination.next_page_url != null">
                <div class="col-sm-12 text-center">
                    <div class="paging_bootstrap text-center" style="margin-left:35%">
                        <ul class="pagination">
                            <li :class="[{disabled: !pagination.prev_page_url}]" class="prev">
                                <a href="#" class="btn btn-info btn-xs" @click.prevent="allPosts(pagination.prev_page_url)">Prev</a>
                            </li>
                            <span class="ml-3 mt-2">Page {{pagination.current_page}} of {{pagination.last_page}}</span>
                            <li :class="[{disabled: !pagination.next_page_url}]" class="next ml-3">
                                <a href="#" class="btn btn-info btn-xs" @click.prevent="allPosts(pagination.next_page_url)">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- MOdal for adding comment -->
            <div class="modal fade" id="addComment" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="title" id="defaultModalLabel">Add comment to {{this.username}}</h6>
                        </div>
                        <form class="well" @submit.prevent="addComment(post_id)">
                            <div class="modal-body">
                                <div class="form-group">
                                    <textarea rows="5" class="form-control no-resize" placeholder="Enter comment here..." required v-model="comment" autofocus></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="file" ref="postFiles" class="form-control" @change="onFileChange" multiple>
                                    <small for="">Upload Files (optional)</small>
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
var moment = require('moment');
export default {
    mounted(){
        this.allPosts();
        
    },
    props:['user_id','project_id'],
    data(){
        return{
            pagination:{},
            posts:[],  
            comment:null,
            moment:moment,
            username:null,
            post_id: null,
            postFileUrl:'http://localhost/collab/public/uploads/post_resource/',
            commentFileUrl:'http://localhost/collab/public/uploads/comment_resource/',
            userImg:'http://localhost/collab/public/uploads/users/',
            attachments:[],
        }
    },
    created(){
        this.$eventBus.$on('newPostIn', this.allPosts);
    },
    // beforeDestroy(){
    //     this.$eventBus.$off('newPostIn');
    // },
    methods:{

        allPosts(url){
            axios.get(url || this.project_id + '/allPosts')
            .then(response=>{
                this.paginate(response.data);
                console.log(response.data);
            })
        },

        paginate(data){
            let pagination = {
                current_page: data.current_page,
                last_page:data.last_page,
                next_page_url:data.next_page_url,
                prev_page_url:data.prev_page_url,
            }
            this.pagination = pagination;
            this.posts = data.data;
        },

        onFileChange(e){
            this.attachments = []; // Emptying files array this will reset it
            var selectedFiles = e.target.files;
            for (let i = 0; i < selectedFiles.length; i++) {
                this.attachments.push(selectedFiles[i]);
            }
            console.log(this.attachments);
        },

        addComment(id){
            var fdata = new FormData();
            // If user selects a file, we attach to the attachement and save
            if(this.attachments.length > 0){  
                for(let file of this.attachments){
                    fdata.append('uploads[]', file);
                }
            }
            fdata.append('project_post_id', id);
            fdata.append('message', this.comment);
            axios.post(this.id + '/createComment', fdata)
            .then(response => {
                this.comment = null;
                this.allPosts();
                this.$eventBus.$emit('comment-added');
                $('#addComment').modal('hide');
            })
        },
        
        deleteComment(comment_id){
            var that = this;
            swal({
                title:"Warning!",
                text: "You are about to delete this comment.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, delete!',
                closeOnConfirm: false
            }, function(){
                axios.post(this.id + '/deleteComment', {id:comment_id})
                .then(response => {
                    swal.close();
                    that.allPosts();
                    that.$eventBus.$emit('comment-deleted');
                });
            });
        },

        downloadFile(url,data){
            var that = this;
            swal({
                title:"File downloader!",
                text: "You are about to download "+url,
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, start download!',
                closeOnConfirm: false
            }, function(){
                axios.post(that.project_id + '/downloadFile',{
                    url:url,
                    data: data,
                    responseType: 'arraybuffer'
                })
                .then(response=>{
                    that.startDownload(response, url)
                    swal.close();
                })
            });
        },

        startDownload(response, fileName){
            var fileType = fileName.split('.').pop();
            var newBlob = new Blob([response.data], {type: 'application/'+fileType});
            let link = document.createElement('a');
            link.href = window.URL.createObjectURL(newBlob);
            link.download = fileName;
            link.click();
        },

        showModal(user,post_id){
            this.username = user;
            this.post_id = post_id;
            $('#addComment').modal('show');
        },

        deleteFile(id,url){
            var that = this;
            swal({
                title:"warning!",
                text: "You are about to delete this file.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, Delete!',
                closeOnConfirm: false
            }, function(){
                axios.post(that.project_id + '/deleteCommentFile',{resource_id:id, url:url})
                .then(response=>{
                    that.$eventBus.$emit('newPostIn');
                    swal.close();
                })
            });
        }
    },


}
</script>
