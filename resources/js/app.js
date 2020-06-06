require('./bootstrap');

window.Vue = require('vue');


Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('project-component', require('./components/admin/project/IndexComponent.vue').default);


const app = new Vue({
    el: '#app',
});
