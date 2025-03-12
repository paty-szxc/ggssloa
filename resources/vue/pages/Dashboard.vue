<template>
    <v-container>
        <DashboardTable
            :headers="headers"
            :leaveDataReq="leaveDataReq">
        </DashboardTable>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DashboardTable from '../components/DashboardTable.vue';

const leaveDataReq = ref([])
const headers = ref([
    { title: 'Name', value: 'name', sortable: true },
    { title: 'Date Filed', value: 'date_filed', sortable: true },
    { title: 'Leave From', value: 'leave_from', sortable: true },
    { title: 'Leave To', value: 'leave_to', sortable: true },
    { title: 'No. of Days', value: 'no_of_days', sortable: true },
    { title: 'Leave Type', value: 'leave_name', sortable: true },
    { title: 'Filed', value: 'filed', sortable: true },
    { title: 'W/Pay', value: 'with_pay', sortable: true },
    { title: 'Reasons', value: 'reasons', sortable: true },
    { title: 'Status', value: 'status', sortable: true }
]);

const fetchLeaveReq = async () => {
    try {
        const res = await axios.get('/leave_req_details');
        leaveDataReq.value = res.data;
        console.log(res.data);
    } catch (err) {
        console.error('Error fetching leave data:', err);
    }
};


//onmounted
onMounted(async () => {
    await fetchLeaveReq();
});
</script>