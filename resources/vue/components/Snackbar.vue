<template>
    <v-container>
        <v-snackbar
            :color="snackbarColor"
            location="top right"
            :timeout="5000" 
            v-model="snackbar"
            >
            {{ snackbarMessage }}
        </v-snackbar>
    </v-container>
</template>
    
<script setup>
import { ref, defineExpose } from 'vue';

const snackbar = ref(false);
const snackbarColor = ref('')
const snackbarMessage = ref('');

const alertSuccess = () => {
    snackbarColor.value = 'green-darken-2'
    snackbarMessage.value = 'Your request has been submitted successfully. 🤩'
    snackbar.value = true
}

const alertError = () => {
    snackbarColor.value = 'red-darken-2'
    snackbarMessage.value = 'There was an error submitting your request. 😞'
    snackbar.value = true
}

const alertUpdate = () => {
    snackbarColor.value = 'green-darken-2'
    snackbarMessage.value = 'Leave request updated successfully. 😊'
    snackbar.value = true
}

const alertApproved =  () => {
    snackbarColor.value = 'green-darken-2'
    snackbarMessage.value = 'The leave request has been approved. 🤩'
    snackbar.value = true
}

const alertDisapproved = () => {
    snackbarColor.value = 'deep-orange-darken-3'
    snackbarMessage.value = 'The leave request has been disapproved. 😞'
    snackbar.value = true
}

const alertCancelled = () => {
    snackbarColor.value = 'orange-lighten-1'
    snackbarMessage.value = 'The leave request has been cancelled. 😐'
    snackbar.value = true
}

defineExpose({ 
    alertApproved,
    alertCancelled,
    alertDisapproved,
    alertError,
    alertSuccess,
    alertUpdate,
}) 
</script>
