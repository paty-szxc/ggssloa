<template>
    <v-container>
        <AdminTable
            :headers="headers"
            :leaveData="leaveData"
            :approve="approve"
            :disapprove="disapprove"
            :cancel="cancel">
        </AdminTable>

        <Snackbar ref="snackbar"></Snackbar>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import AdminTable from '../components/AdminTable.vue';
import Snackbar from '../components/Snackbar.vue';

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

const snackbar = ref(null);

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
    const result = await Swal.fire({
        text: "Are you sure you want to approve this leave request?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: "#32cd32",
        cancelButtonColor: '#ff3131'
    });

    if(result.isConfirmed){
        console.log(id, 'approve', status)
        axios({
            url: 'handle_leave_request',
            method: 'post',
            data: {
                leave_detail_id : id,
                status : status,
            }
        }).then((res) => {
            console.log(res)
            fetchLeaveData()
            snackbar.value.alertApproved()
        }).catch((error) =>  {
            console.error(error)
        })
    }
};


const disapprove = async (id, status) => {
    const result = await Swal.fire({
        text: "Are you sure you want to disapprove this leave request?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: "#32cd32",
        cancelButtonColor: '#ff3131'
    });

    if(result.isConfirmed){
        console.log(id, 'disapprove', status)
        axios({
            url: 'handle_leave_request',
            method: 'post',
            data: {
                leave_detail_id : id,
                status : status,
            }
        }).then((res) => {
            console.log(res)
            fetchLeaveData()
            snackbar.value.alertDisapproved()
        }).catch((error) =>  {
            console.error(error)
        })
    }
};

const cancel = async (id, status) => {
    const result = await Swal.fire({
        text: "Are you sure you want to cancel this leave request?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: "#32cd32",
        cancelButtonColor: '#ff3131'
    });

    if(result.isConfirmed){
        console.log(id, 'cancel', status)
        axios({
            url: 'handle_leave_request',
            method: 'post',
            data: {
                leave_detail_id : id,
                status : status,
            }
        }).then((res) => {
            console.log(res)
            fetchLeaveData()
            snackbar.value.alertCancelled()
        }).catch((error) =>  {
            console.error(error)
        })
    }
};


//onmounted
onMounted(async () => {
    await fetchLeaveData();
});

</script>
