<template>
    <v-container>
        <p class="text-h4">Leave Approval List</p>
        <v-data-table
            :headers="headers" 
            :items="leaveData"> 
            <template v-slot:item.actions="{ item }">
                <v-icon @click="approve(item.id, 1)" color="green">mdi-check-circle</v-icon>
                <v-icon @click="disapprove(item.id, 2)" color="red">mdi-alert-circle</v-icon>
                <v-icon @click="cancel(item.id, 3)" color="orange">mdi-cancel</v-icon>
            </template>
        </v-data-table>
    </v-container>
</template>

<script setup>
import { defineProps } from 'vue';

const { headers, leaveData, approve, disapprove, cancel } = defineProps({
    headers: Array,
    leaveData: Array,
    approve: Function,
    disapprove: Function,
    cancel: Function
});
</script>