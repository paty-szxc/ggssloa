<template>
    <v-container>
        <p class="text-h4">Leave Approval List</p>
        <v-data-table
            density="compact"
            :headers="headers" 
            :items="leaveData"> 
            <template v-slot:item.actions="{ item }">
                <v-tooltip location="bottom">
                    <template v-slot:activator="{ props }">
                        <v-icon v-bind="props" @click="approve(item.id, 1)" color="green">mdi-check-circle</v-icon>
                    </template>
                    <span>Approve</span>
                </v-tooltip>
                <v-tooltip location="bottom">
                    <template v-slot:activator="{ props }">
                        <v-icon v-bind="props" @click="disapprove(item.id, 2)" color="red">mdi-alert-circle</v-icon>
                    </template>
                    <span>Disapprove</span>
                </v-tooltip>
                <v-tooltip location="bottom">
                    <template v-slot:activator="{ props }">
                        <v-icon v-bind="props" @click="cancel(item.id, 3)" color="orange">mdi-cancel</v-icon>
                    </template>
                    <span>Cancel</span>
                </v-tooltip>
            </template>
            <template v-slot:item.filed="{ item }">
                <v-icon v-if="item.filed === 1" color="blue">mdi-check-circle</v-icon>
                <v-icon v-else color="red">mdi-close-circle</v-icon>
            </template>
            <template v-slot:item.with_pay="{ item }">
                <v-icon v-if="item.with_pay === 1" color="blue">mdi-check-circle</v-icon>
                <v-icon v-else color="red">mdi-close-circle</v-icon>
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