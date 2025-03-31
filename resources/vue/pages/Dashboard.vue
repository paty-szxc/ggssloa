<template>
    <v-container>
        <DashboardTable
            :leaveHeaders="leaveHeaders"
            :leaveDataReq="leaveDataReq"
            :otReqData="otReqData"
            :otHeaders="otHeaders">
        </DashboardTable>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DashboardTable from '../components/DashboardTable.vue';

const leaveDataReq = ref([])
const leaveHeaders = ref([
    { title: 'Name', value: 'name', sortable: true },
    { title: 'Date Filed', value: 'date_filed', sortable: true },
    { title: 'Leave From', value: 'leave_from', sortable: true },
    { title: 'Leave To', value: 'leave_to', sortable: true },
    { title: 'No. of Days', value: 'no_of_days', sortable: true },
    { title: 'Leave Type', value: 'leave_name', sortable: true },
    { title: 'Filed', value: 'filed', sortable: true },
    { title: 'W/Pay', value: 'with_pay', sortable: true },
    { title: 'Reasons', value: 'reasons', sortable: true },
    { title: 'Status', value: 'status', sortable: true },
    { title: 'Approved By', value: 'updated_by', sortable: true },
]);

const fetchLeaveReq = async () => {
    try{
        const res = await axios.get('leave_req_details');
        leaveDataReq.value = res.data;
        console.log(res.data);
    }
    catch(error){
        console.error('Error fetching leave data:', error);
        console.log('error');
        
    }
};

const otReqData = ref([])
const otHeaders = ref([
    { title: 'Name', value: 'name', sortable: true },
    { title: 'Reason', value: 'reason' },
	{ title: 'Time', value: 'time_duration' },
	{ title: 'Status', value: 'status' },
	{ title: 'Approved By', value: 'approved_by' },
])

const fetchApprovedOt = async () => {
    try{
        const res = await axios.get('ot_request_details')
        otReqData.value = res.data
        console.log(res.data);
        
    } 
    catch(error){
        console.error('Error fetching OT data');
    }
}


//onmounted
onMounted(async () => {
    await fetchLeaveReq()
    await fetchApprovedOt()
});
</script>