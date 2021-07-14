<template>
    <div class="tab-pane fade active show" id="pills-blog" role="tabpanel" aria-labelledby="pills-blog-tab">
        
        <createPost v-if="this.project_id != null" :id = "this.project_id"/>
        <div  v-if="this.posts.length > 0">
            <div class="card blog_single_post" v-for="post in Object.assign([],this.posts).reverse()" :key="post.id">
                <div class="img-post container" style="max-height:none">
                    <div class="media_img mt-20 mb-20 ml-20 mr-20 row">
                        <div v-for="file in post.post_resources" :key="file.id" class="col-md-6 col-xs-6 col-lg-4 mt-3">
                            <div v-if="file.file.split('.').pop() == 'jpg' || file.file.split('.').pop() == 'png' || file.file.split('.').pop() == 'jpeg'">
                                <a :href="fileUrl + file.file" target="_blank" data-toggle="tooltip" title="" data-original-title="View Image">
                                    <img class="w200 img-thumbnail img-responsive"  :src="fileUrl + file.file" alt="Awesome Image">
                                </a><br>
                                <a href="#" class="fa fa-trash mt-2" @click="deleteFile(file.id, file.file)"> Delete</a>
                            </div>
                        <div class="file_folder responsive" v-else>
                            <a href="javascript:void(0);">
                                <div class="icon">
                                    <i class="fa fa-file-o text-success"></i>
                                </div>
                                <div class="file-name">
                                    <p class="mb-0 text-muted">{{file.file}}</p>
                                    <div class="mt-3">
                                        <a :href="project_id + '/post-downloadFile/'+file.file" title="download"><i class="fa fa-download"></i></a>
                                        <a href="#" @click.prevent="deleteFile(file.id, file.file)" title="delete"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </a>
                        </div>

                        </div>
                    </div>
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
                            <img class="media-object avatar avatar-md mr-4" :src="userImg + post.user.img" alt="">
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
                                        <img class="media-object avatar mr-4" :src="userImg + comment.user.img" alt="">
                                        <div class="media-body">
                                            <strong>{{comment.user.name}}: </strong>
                                            {{comment.message}}
                                             <div class="timeline_img row">
                                                <div v-for="file in comment.comment_resources" :key="file.id" class="col-md-6 col-xs-6 col-lg-4">
                                                    <div v-if="file.file.split('.').pop() == 'jpg' || file.file.split('.').pop() == 'png' || file.file.split('.').pop() == 'jpeg'">
                                                        <a :href="commentFileUrl + file.file" target="_blank" data-toggle="tooltip" title="" data-original-title="View Image">
                                                            <img class="width150 img-responsive"  :src="commentFileUrl + file.file" alt="Awesome Image" height="80" width="50">
                                                        </a><br>
                                                        <a v-if="user_id === file.user_id" href="#" class="fa fa-trash mt-2" @click="deleteFile(file.id, file.file)"> Delete</a>
                                                    </div>
                                                    <div class="file_folder responsive mt-10" v-else>
                                                        <a href="javascript:void(0);">
                                                            <div class="icon">
                                                                <i class="fa fa-file-o text-success"></i>
                                                            </div>
                                                            <div class="file-name">
                                                                <p class="mb-0 text-muted">{{file.file}}</p>
                                                                <div>
                                                                    <a :href="project_id + '/comment-downloadFile/'+file.file"><i class="fa fa-download font-120"></i></a>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <p class="text-center mt-5 text-blue" v-else>No comments for this Post</p>
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
    props:['project_id'],
    data(){
        return{
            pagination:{},
            posts:[],
            moment:moment,
            showEditMessage:false,
            fileUrl:'http://localhost/collab/public/uploads/post_resource/',
            userImg:'http://localhost/collab/public/uploads/users/',
        }
    },
    methods:{

        userPosts(url){
            axios.get(url || this.project_id + '/userPosts')
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

        editPost(title,message,id){
            this.showEditMessage = true
            setTimeout(() => {
                this.showEditMessage = false
            }, 3000);
            this.$eventBus.$emit('edit-post', {title,message,id});
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
                    data:data,
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

        deleteFile(id,url){
            var that = this;
            swal({
                title:"warning!",
                text: "You are about to delete this file.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, proceed!',
                closeOnConfirm: false
            }, function(){
                axios.post(that.project_id + '/deleteFile',{resource_id:id, url:url})
                .then(response=>{
                    that.$eventBus.$emit('newPostIn');
                    swal({
                        title:"Success!",
                        text: "Resource Deleted Successfully!",
                        type: "success",
                    })
                })
            });
        }
    }


}
</script>
