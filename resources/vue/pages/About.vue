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
import notifications from '../../js/notifications';
import Swal from 'sweetalert2';
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
            url: '/update_leave',
            method: 'post',
            data: {
                leave_detail_id : id,
                status : status,
            }
        }).then((res) => {
            console.log(res)
            fetchLeaveData()
            notifications.notifySuccess('The leave request has been approved')
        }).catch((error) =>  {
            console.error(error)
            notifications.notifyError('There was an error approving the leave request')
        })
    }

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

</script>
