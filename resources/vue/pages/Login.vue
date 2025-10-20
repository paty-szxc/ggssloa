<template>
    <v-container class="background-gradient">
        <v-sheet elevation="2" class="pa-5">
            <div class="d-flex justify-center">
                <v-img
                    :width="300"
                    aspect-ratio="16/9"
                    cover
                    src="/images/logo.png"
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
                            density="compact"
                            label="Employee Code"
                            placeholder="0000-0000"
                            style="width: 350px;"
                            v-model="credential.emp_code">
                        </v-text-field>
                        <v-date-input
                            clearable
                            density="compact"
                            label="Date Hired"
                            prepend-icon=""
                            prepend-inner-icon="mdi-calendar"
                            style="width: 350px;"
                            variant="outlined"
                            v-model="credential.date_hired"
                            :max="new Date().toISOString().split('T')[0]"
                        />
                        <v-text-field
                            density="compact"
                            label="Position"
                            style="width: 350px;"
                            v-model="credential.position">
                        </v-text-field>
                        <v-text-field
                            density="compact"
                            label="Name"
                            style="width: 350px;"
                            v-model="credential.name">
                        </v-text-field>
                        <v-text-field
                            density="compact"
                            label="Username"
                            style="width: 350px;"
                            v-model="credential.username">
                        </v-text-field>
                        <v-text-field
                            density="compact"
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

        <Snackbar ref="snackbar"></Snackbar>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"
import Snackbar from "../components/Snackbar.vue"

const tab = ref(null)
const credential = ref({})
const snackbar = ref(null)

//functions
// function registerEmp(){
//     if(!credential.value.name || !credential.value.username || !credential.value.password){
//         notifications.notifyWarning('Fill up empty fields')
//     } 
//     else{
//         axios.get('sanctum/csrf-cookie').then(() => {
//             axios.post('register', credential.value)
//             .then(res => { // Corrected arrow function syntax
//                 if (res.data.message) {
//                     snackbar.value.alertCustom('Registration Successful! 🙂')
//                     credential.value = {}
//                 }
//             })
//             .catch(err => {
//                 console.error('Error registering user:', err.response.data.message || err.message)
//                 // Handle error response
//             })
//         })
//     }
// }

// function login(){
//     if(!credential.value.username || !credential.value.password){
//         notifications.notifyWarning('Fill up empty fields')
//     }
//     else{
//         axios.get('sanctum/csrf-cookie').then(() => {
//             axios.post('/login', credential.value)
//             .then(() => {
//                 // notifications.notifySuccess(`Welcome ${credential.value.username}`, '3500')
//                 // snackbar.value.alertCustom(`Welcome ${credential.value.username}`)
//                 credential.value = {}
//                 location.reload()
//             })
//             .catch(err => {
//                 console.error('Error logging user:', err.response.data.message || err.message)
//                 // notifications.notifyError(err.response.data.message || 'Login failed');
//             })
//         })
//     }
// }

//onmounted
// onMounted(async () => {
//     loadData()
// });

function registerEmp(){
    // helper: format to YYYY-MM-DD in local time
    const formatDateLocal = (value) => {
        if (!value) return null
        if (typeof value === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(value)) return value
        const d = new Date(value)
        if (isNaN(d.getTime())) return null
        const y = d.getFullYear()
        const m = String(d.getMonth() + 1).padStart(2, '0')
        const day = String(d.getDate()).padStart(2, '0')
        return `${y}-${m}-${day}`
    }

    if(!credential.value.name || !credential.value.username || !credential.value.password){
        notifications.notifyWarning('Fill up empty fields')
    } 
    else{
        //map frontend keys to backend and normalize date
        const payload = {
            employee_code: credential.value.emp_code || undefined,
            date_hired: formatDateLocal(credential.value.date_hired),
            position: credential.value.position,
            name: credential.value.name,
            username: credential.value.username,
            password: credential.value.password
        }

        //use window.axios which has the correct configuration
        window.axios.get('sanctum/csrf-cookie').then(() => {
            window.axios.post('register', payload)
            .then(res => {
                if (res.data.message) {
                    snackbar.value.alertCustom('Registration Successful! 🙂')
                    credential.value = {}
                }
            })
            .catch(err => {
                console.error('Error registering user:', err.response?.data?.message || err.message)
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
            axios.post('/login', credential.value)
            .then((response) => {
                if (response.data.redirect) {
                    // If server returns redirect URL
                    window.location.href = response.data.redirect;
                } else {
                    // Fallback: redirect to home
                    window.location.href = '/';
                }
            })
            .catch(err => {
                console.error('Error logging user:', err.response?.data?.message || err.message)
                snackbar.value.alertCustom(err.response?.data?.message || 'Login failed', 'error')
            })
        }).catch(csrfError => {
            console.error('CSRF token error:', csrfError)
        })
    }
}
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

