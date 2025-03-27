<template>
    <v-container>
        <p class="text-h4">Leave Request Summary</p>
        <v-data-table
            density="compact"
            :headers="leaveHeaders"
            :items="leaveDataReq">
            <template v-slot:item.status="{ item }">
                <span v-if="item.status === 0" style="color: green;">
                    For Approval
                </span>
                <span v-else-if="item.status === 1" style="color: green;">
                    Approved
                </span>
                <span v-else-if="item.status === 2" style="color: red;">
                    Disapproved
                </span>
                <span v-else-if="item.status === 3" style="color: orange;">
                    Cancelled
                </span>
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

        <p class="text-h4">OT Request Summary</p>
        <v-data-table
            density="compact"
            :headers="otHeaders"
            :items="otReqData">
            <template v-slot:item.status="{ item }">
                <span v-if="item.status === 1" style="color: green;">
                    Approved
                </span>
                <span v-else-if="item.status === 2" style="color: red;">
                    Disapproved
                </span>
                <span v-else-if="item.status === 3" style="color: orange;">
                    Cancelled
                </span>
            </template>
        </v-data-table>
    </v-container>
</template>

<script setup>
import { defineProps } from 'vue';

const { leaveHeaders, leaveDataReq, otHeaders, otReqData } = defineProps({
    leaveHeaders: Array,
    leaveDataReq: Array,
    otHeaders: Array,
    otReqData: Array
})
</script>