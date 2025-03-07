<template>
    <v-container>
        <AdminTable
            :headers="headers"
            :leaveData="leaveData"
            :approve="approve"
            :disapprove="disapprove"
            :cancel="cancel">
        </AdminTable>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AdminTable from '../components/AdminTable.vue';

const leaveData = ref([]);
const headers = ref([
    { title: 'Name', value: 'name'},
    { title: 'Date Filed', value: 'date_filed' },
    { title: 'Leave From', value: 'leave_from' },
    { title: 'Leave To', value: 'leave_to' },
    { title: 'No. of Days', value: 'no_of_days' },
    { title: 'Leave Type', value: 'leave_name' },
    { title: 'Filed', value: 'filed' },
    { title: 'W/Pay', value: 'with_pay' },
    { title: 'Reasons', value: 'reasons' },
    { title: 'Actions', value: 'actions' }
]);

//onmounted
onMounted(async () => {
    // try {
    //     const res = await axios.get('/leave_data');
    //     leaveData.value = res.data;
    //     console.log(res.data);
    // } catch (err) {
    //     console.error('Error fetching leave data:', err);
    // }
    await fetchLeaveData();
});

//functions
const fetchLeaveData = async () => {
    try {
        const res = await axios.get('/leave_data');
        leaveData.value = res.data;
        console.log(res.data);
    } catch (err) {
        console.error('Error fetching leave data:', err);
    }
};

const approve = async (id, status) => {
    console.log(id, 'approve', status)
    // try {
    //     await axios.post(`/update_leave${id}`);
    //     await fetchLeaveData();
    // } catch (err) {
    //     console.error('Error approving leave:', err);
    // }
    axios({
        url: '/update_leave',
        method: 'post',
        data: {
            leave_detail_id : id,
            status : status,
        }
    }).then((res) => {
        console.log(res)
        fetchLeaveData()
    })

};


const disapprove = async (id, status) => {
    console.log(id, 'disapprove', status)
    axios({
        url: '/update_leave',
        method: 'post',
        data: {
            leave_detail_id : id,
            status : status,
        }
    }).then((res) => {
        console.log(res)
        fetchLeaveData()
    })
};

const cancel = async (id, status) => {
    console.log(id, 'cancel', status)
    axios({
        url: '/update_leave',
        method: 'post',
        data: {
            leave_detail_id : id,
            status : status,
        }
    }).then((res) => {
        console.log(res)
        fetchLeaveData()
    })
};


</script>
