<template>
    <v-container>
    <p class="text-h4">Request Summary</p>
        <v-tabs
            align-tabs="title"
            color="blue-darken-1"
            fixed-tabs
            v-model="tab">
            <v-tab>Leave Request Summary</v-tab>
            <v-tab>OT Request Summary</v-tab>
            <v-tab>OB Request Summary</v-tab>
        </v-tabs>
        <v-tabs-window v-model="tab">
            <v-tabs-window-item value="leave_dets">
                <v-data-table
                    density="compact"
                    fixed-footer
                    fixed-header
                    :headers="leaveHeaders"
                    :items="leaveDataReq">
                    <template v-slot:item.status="{ item }">
                        <span v-if="item.status === 0" style="color: green;">For Approval</span>
                        <span v-else-if="item.status === 1" style="color: green;">Approved</span>
                        <span v-else-if="item.status === 2" style="color: red;">Disapproved</span>
                        <span v-else-if="item.status === 3" style="color: orange;">Cancelled</span>
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
            </v-tabs-window-item>
            <v-tabs-window-item value="ot_req">
                <v-data-table
                    density="compact"
                    :headers="otHeaders"
                    :items="otReqData">
                    <template v-slot:item.status="{ item }">
                        <span v-if="item.status === 1" style="color: green;">Approved</span>
                        <span v-else-if="item.status === 2" style="color: red;">Disapproved</span>
                        <span v-else-if="item.status === 3" style="color: orange;">Cancelled</span>
                    </template>
                </v-data-table>
            </v-tabs-window-item>
            <v-tabs-window-item value="ob_req">
                <v-data-table
                    density="compact"
                    :headers="obHeaders"
                    :items="obReqData">
                    <template v-slot:item.status="{ item }">
                        <span v-if="item.status === 1" style="color: green;">Approved</span>
                        <span v-else-if="item.status === 2" style="color: red;">Disapproved</span>
                        <span v-else-if="item.status === 3" style="color: orange;">Cancelled</span>
                    </template>
                </v-data-table>
            </v-tabs-window-item>
        </v-tabs-window>
    </v-container>
</template>
    
<script setup>
import { defineProps, ref } from 'vue';

const { leaveHeaders, leaveDataReq, otHeaders, otReqData, obHeaders, obReqData } = defineProps({
    leaveHeaders: Array,
    leaveDataReq: Array,
    otHeaders: Array,
    otReqData: Array,
    obHeaders: Array,
    obReqData: Array
});


const tab = ref(null);
</script>

<style scoped>

</style>