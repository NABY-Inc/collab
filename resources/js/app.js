require('./bootstrap');

window.Vue = require('vue');


Vue.component('projectedit-component', require('./components/admin/project/EditComponent.vue').default);
Vue.component('mypost-component', require('./components/admin/project/MyPostComponent.vue').default);
Vue.component('timelinepost-component', require('./components/admin/project/TimelinePostComponent.vue').default);
Vue.component('project-component', require('./components/admin/project/IndexComponent.vue').default);

Vue.prototype.$eventBus = new Vue();
const app = new Vue({
    el: '#app',
});
