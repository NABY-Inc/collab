<template>
    <div class="tab-pane fade active show" id="pills-blog" role="tabpanel" aria-labelledby="pills-blog-tab">
        
        <createPost v-if="this.project_id != null" :id = "this.project_id"/>
        <div  v-if="this.posts.length > 0">
            <div class="card blog_single_post" v-for="post in Object.assign([],this.posts).reverse()" :key="post.id">
                <div class="img-post">
                    <img class="d-block img-fluid" src="http://localhost/collab/public/assets/images/gallery/6.jpg" alt="First slide">
                </div>
                <div class="card-body">
                    <h4><a href="#">{{post.title}}</a></h4>
                    <span>
                        <a href="" @click.prevent="editPost(post.title,post.message,post.id)" class="btn btn-xs btn-success" style="float:right"><i class="fa fa-pencil"></i> Edit</a>
                        <p v-show="showEditMessage" class="text-center" style="color:red">Check in the create form to edit</p>
                    </span>
                </div>
                <div class="footer" style="margin-top:-8%">
                    <ul class="stats list-unstyled">
                        <li><a href="javascript:void(0);"><i class="fa fa-comments text-blue"></i> {{post.comments.length > 0 ? post.comments.length : 0}} comments</a></li>
                    </ul>
                </div>
                <!-- <hr> -->
                <ul class="list-group card-list-group" style="margin-top:-5%">
                    <li class="list-group-item py-5">
                        <div class="media">
                            <img class="media-object avatar avatar-md mr-4" src="http://localhost/collab/public/assets/images/xs/avatar3.jpg" alt="">
                            <div class="media-body">
                                <div class="media-heading">
                                    <small class="float-right text-muted">{{moment(post.created_at).format('MMM. Do, YYYY')}}</small>
                                    <h5>{{post.user.name}}</h5>
                                </div>
                                <div>
                                    {{post.message}}
                                </div>
                                <ul class="media-list" v-if="post.comments.length > 0">
                                    <li class="media mt-4" v-for="comment in Object.assign([],post.comments).reverse()" :key="comment.id">
                                        <img class="media-object avatar mr-4" src="http://localhost/collab/public/assets/images/xs/avatar1.jpg" alt="">
                                        <div class="media-body">
                                            <strong>{{comment.user.name}}: </strong>
                                            {{comment.message}}
                                        </div>
                                    </li>
                                </ul>
                                <p class="text-center" v-else>No comments for this Post</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Pagination from here | We want to hide this at first display -->
            <div v-if="pagination.prev_page_url != null || pagination.next_page_url != null" class="card">
                <div class="col-sm-12 text-center mt-20">
                    <div class="paging_bootstrap text-center" style="margin-left:35%">
                        <ul class="pagination">
                            <li :class="[{disabled: !pagination.prev_page_url}]" class="prev">
                                <a href="#" class="btn btn-info btn-xs" @click.prevent="userPosts(pagination.prev_page_url)">Prev</a>
                            </li>
                            <span class="ml-3 mt-2">Page {{pagination.current_page}} of {{pagination.last_page}}</span>
                            <li :class="[{disabled: !pagination.next_page_url}]" class="next ml-3">
                                <a href="#" class="btn btn-info btn-xs" @click.prevent="userPosts(pagination.next_page_url)">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card blog_single_post text-center" v-else>
            <p class="mt-3">No Post Available</p>
        </div>
            
    </div>
</template>

<script>
import createPost from "./CreatePostComponent";
var moment = require('moment');
export default {
    components:{
        createPost
    },
    mounted(){
        this.userPosts();
    },
    created(){
        this.$eventBus.$on('newPostIn', this.userPosts);
        this.$eventBus.$on('comment-deleted', this.userPosts);
        this.$eventBus.$on('comment-added', this.userPosts);
    },
    // beforeDestroy(){
    //     this.$eventBus.$off('comment-deleted');
    //     this.$eventBus.$off('comment-added');
    // },
    props:['project_id'],
    data(){
        return{
            pagination:{},
            posts:[],
            moment:moment,
            showEditMessage:false
        }
    },
    methods:{

        userPosts(url){
            axios.get(url || this.project_id + '/userPosts')
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

        editPost(title,message,id){
            this.showEditMessage = true
            setTimeout(() => {
                this.showEditMessage = false
            }, 3000);
            this.$eventBus.$emit('edit-post', {title,message,id});
        }
    }


}
</script>
