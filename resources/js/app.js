require('./bootstrap');

window.Vue = require('vue');


Vue.component('projectedit-component', require('./components/admin/project/EditComponent.vue').default);
Vue.component('project-component', require('./components/admin/project/IndexComponent.vue').default);


const app = new Vue({
    el: '#app',
});
