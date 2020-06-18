<template>
    <div class="tab-pane fade" id="pills-timeline" role="tabpanel" aria-labelledby="pills-timeline-tab">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Activity</h3>
            </div>
            <div class="card-body" v-if="this.posts.length > 0">

                <div class="timeline_item " v-for="post in this.posts" :key="post.id">
                    <img class="tl_avatar" src="http://localhost/collab/public/assets/images/xs/avatar4.jpg" alt="">
                    <span><a href="javascript:void(0);" title="">{{post.user.name}}</a> <small class="float-right text-right">{{moment(post.created_at).fromNow()}}</small></span>
                    <h6 class="font600">{{post.title}}</h6>
                    <div class="msg">
                        <p>{{post.message}}</p>
                        <div class="timeline_img mb-20" v-if="post.file !== 'no_file.jpg'">
                            <img class="width100" src="http://localhost/collab/public/assets/images/gallery/1.jpg" alt="Awesome Image">
                            <img class="width100" src="http://localhost/collab/public/assets/images/gallery/2.jpg" alt="Awesome Image">
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
                                        <img class="rounded img-fluid" src="http://localhost/collab/public/assets/images/xs/avatar3.jpg" alt="">
                                    </div>
                                    <div class="comment_body">
                                        <h6>{{comment.user.name}} <a v-if="user_id === comment.user.id" @click.prevent="deleteComment(comment.id)" href="" class="fa fa-trash text-danger ml-2"></a> <small class="float-right font-14">{{moment(comment.created_at).fromNow()}}</small></h6>
                                        <p>{{comment.message}}</p>
                                    </div>
                                </li>
                            </ul>
                            <p class="tex-center mt-3" v-else>No comment(s) on this post.</p>
                        </div>
                    </div>
                </div>
                <!-- Pagination from here | We want to hide this at first display -->
            </div>
            <hr>
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
            <p v-else class="text-center">Empty Timeline Activities</p>
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
                // console.log(response.data);
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

        addComment(id){
            axios.post(this.id + '/createComment', {
                project_post_id:id,
                message:this.comment
            })
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

        showModal(user,post_id){
            this.username = user;
            this.post_id = post_id;
            $('#addComment').modal('show');
        }
    },


}
</script>
