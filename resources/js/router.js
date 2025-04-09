import { createRouter, createWebHistory  } from 'vue-router'

const router = createRouter({
    history: createWebHistory('/leave_of_absence/'),
    base: '/leave_of_absence/',
    routes: [
        { 
            path: '/', 
            name: 'Home',  
            component:  () => import('../vue/pages/Home.vue')  
        },
        { 
            path: '/pending_leave_req', 
            name: 'Pending Leave Request', 
            component: () => import('../vue/pages/PendingLeaveReq.vue') 
        },
        { 
            path: '/ot_request', 
            name: 'Overtime Request', 
            component: () => import('../vue/pages/OTRequest.vue') 
        },
        { 
            path: '/ot_approval', 
            name: 'Pending Overtime Request', 
            component: () => import('../vue/pages/OTApproval.vue') 
        },
        { 
            path: '/status_dashboard', 
            name: 'Leave Status Dashboard', 
            component: () => import('../vue/pages/Dashboard.vue') 
        },
        {
            path: '/official_business_form',
            name: 'Official Business Form',
            component: () => import('../vue/pages/OBForm.vue')
        },
        {
            path: '/pending_ob_req',
            name: 'Pending OB Request',
            component: () => import('../vue/pages/OBFormReq.vue')
        },
        { 
            path: '/login', 
            name: 'Login', 
            component: () => import('../vue/pages/Login.vue') 
        },
    ],
})

export default router;