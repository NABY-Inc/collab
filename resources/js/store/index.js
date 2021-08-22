import Vue from 'vue'   // Bringing in Vue
import Vuex from "vuex";    // Bringing in Vuex

// ADMIN MODULES
import user from "./modules/admin/user";
// GLOBAL MODULES
import project from "./modules/global/project";
import announcement from "./modules/global/announcement";
import note from "./modules/global/note";
import task from "./modules/global/task";
import discussion from "./modules/global/discussion";
import file from "./modules/global/file";

Vue.use(Vuex)   // Using vuex in vue

// Exporting vuex store
export default new Vuex.Store({
    modules : {
        // Admin modules
        user,
        
        // Global modules
        project,
        announcement,
        note,
        task,
        discussion,
        file,
    }

})