<template>
    <v-app>
        <template v-if="!isLoginRoute">
            <v-app-bar style="color: white; background: linear-gradient(135deg, #0047AB, #50C878) ;">
                <v-app-bar-nav-icon @click="navDrawer = !navDrawer"></v-app-bar-nav-icon>
                <v-toolbar-title >Leave of Absence Form</v-toolbar-title>
                <v-spacer></v-spacer>
                <p>Hi, {{ credential.username }}</p>
                <v-btn @click="logout()">Logout</v-btn>
            </v-app-bar>
    
            <v-navigation-drawer v-model="navDrawer">
                <v-list nav>
                    <v-list-item 
                        v-for="(item, index) in drawerItems" :key="index"
                        :to="item.path"
                    >
                        {{ item.title }}
                    </v-list-item>
                </v-list>
            </v-navigation-drawer>
    
            <v-main>
                <v-container>
                    <router-view></router-view>
                </v-container>
            </v-main>
        </template>

        <template v-else>
            <router-view></router-view>
        </template>

    </v-app>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router"

const navDrawer = ref(false);
const drawers = ref([
    {title: 'Home', path: '/'},
    {title: 'Pending Leave Requests', path: '/pending_leave_req'},
    {title: 'Leave Status Dashboard', path: '/leave_status_dashboard'}
]);

const route = useRoute();
const isLoginRoute = computed(() => route.path === '/login');

const drawerItems = computed (() => {
    let items = []
    drawers.value.forEach(el => {
        if(credential.value.id == 3){
            items.push(el)
        }else if(el.title == 'Home'){
            items.push(el)
        }
    });
    return items
});

const credential = ref({ username: '', first_name: '', last_name: '' });

function logout() {
    axios.post('/logout').then(() => {
        location.reload()
    });
}

onMounted(() => {
    // Fetch user info when the component is mounted
    axios.get('/api/user') // Adjust the URL based on your API structure
        .then(response => {
            // console.log(response.data, 'resdata')
            credential.value = response.data; // Set the user info
            // console.log(credential.value, 'credential')
        })
        .catch(error => {
            console.error('Error fetching user info:', error);
        });
});

</script>

<style lang="scss" scoped>

</style>