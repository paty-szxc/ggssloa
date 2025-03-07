import { createRouter, createWebHistory  } from 'vue-router'

const router = createRouter({
    history: createWebHistory (),
    routes: [
        { 
            path: '/', 
            name: 'Home',  
            component:  () => import('../vue/pages/Home.vue')  
        },
        { 
            path: '/pending_leave_req', 
            name: 'Pending Leave Request', 
            component: () => import('../vue/pages/About.vue') 
        },
        { 
            path: '/leave_status_dashboard', 
            name: 'Leave Status Dashboard', 
            component: () => import('../vue/pages/Dashboard.vue') 
        },
        { 
            path: '/login', 
            name: 'Login', 
            component: () => import('../vue/pages/Login.vue') 
        },
    ],
})

export default router;