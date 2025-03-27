<template>
    <v-container class="background-gradient">
        <v-sheet elevation="2" class="pa-5">
            <div class="d-flex justify-center">
                <v-img
                    :width="300"
                    aspect-ratio="16/9"
                    cover
                    src="images/logo.png"
                ></v-img>
            </div>
            <v-tabs fixed-tabs v-model="tab">
                <v-tab text="Login"></v-tab>
                <v-tab text="Register"></v-tab>
            </v-tabs>

                <v-tabs-window v-model="tab">
                    <v-tabs-window-item value="login">
                        <v-text-field
                            class="mt-3"
                            label="Username"
                            style="width: 350px;"
                            v-model="credential.username">
                        </v-text-field>
                        <v-text-field
                            label="Password"
                            type="password"
                            style="width: 350px;"
                            v-model="credential.password">
                        </v-text-field>
                        <v-btn 
                            block
                            @click="login()"
                            color="primary"
                            class="mb-3 gradient-btn">
                            LOGIN
                        </v-btn>
                    </v-tabs-window-item>
                    <v-tabs-window-item value="register">
                        <v-text-field
                            class="mt-3"
                            label="Name"
                            style="width: 350px;"
                            v-model="credential.name">
                        </v-text-field>
                        <v-text-field
                            label="Username"
                            style="width: 350px;"
                            v-model="credential.username">
                        </v-text-field>
                        <v-text-field
                            label="Password"
                            style="width: 350px;"
                            type="password"
                            v-model="credential.password">
                        </v-text-field>
                        <v-btn 
                            block
                            @click="registerEmp()"
                            color="primary"
                            class="mb-3 gradient-btn">
                            Register
                        </v-btn>
                    </v-tabs-window-item>
                </v-tabs-window>
        </v-sheet>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"

const tab = ref(null)
const credential = ref({})

//functions
function registerEmp(){
    if(!credential.value.name || !credential.value.username || !credential.value.password){
        notifications.notifyWarning('Fill up empty fields')
    } 
    else{
        axios.get('sanctum/csrf-cookie').then(() => {
            axios.post('register', credential.value)
            .then(res => { // Corrected arrow function syntax
                if (res.data.message) {
                    alert(res.data.message)
                    credential.value = {}
                }
            })
            .catch(err => {
                console.error('Error registering user:', err.response.data.message || err.message)
                // Handle error response
            })
        })
    }
}

function login(){
    if(!credential.value.username || !credential.value.password){
        notifications.notifyWarning('Fill up empty fields')
    }
    else{
        axios.get('sanctum/csrf-cookie').then(() => {
            axios.post('login', credential.value)
            .then(() => {
                // notifications.notifySuccess(`Welcome ${credential.value.username}`, '3500')
                credential.value = {}
                location.reload()
            })
            .catch(err => {
                console.error('Error logging user:', err.response.data.message || err.message)
                notifications.notifyError(err.response.data.message || 'Login failed');
            })
        })
    }
}

//onmounted
onMounted(async () => {
    loadData()
});


</script>

<style scoped>
/* .background-gradient {
    background: linear-gradient(135deg, #0047AB, #50C878);
    height: 100vh;
    display: flex;
    align-items: center; 
    justify-content: center;
} */

.pa-5{
    max-width: 400px;
    margin: auto;
}
.gradient-btn {
    background: linear-gradient(#3d6fcc, #79e8ad);
    color: white; /* Optional: Change text color for better contrast */
    border: none; /* Remove default border */
}

.gradient-btn:hover {
    background: linear-gradient(#79e8ad, #3d6fcc); /* Optional: Change gradient on hover */
}
</style>

