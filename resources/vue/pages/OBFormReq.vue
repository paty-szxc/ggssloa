<template>
    <v-container>
        <v-card>
            <v-card-title><p class="text-h4">Official Business Request List</p></v-card-title>
            <v-card-text>
                <v-data-table
                    density="compact"
                    :headers="headers"
                    :items="allObReqData">
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
                </v-data-table>
            </v-card-text>
        </v-card>
        
        <Snackbar ref="snackbar"></Snackbar>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import Snackbar from '../components/Snackbar.vue';

const snackbar = ref(null);
const headers = ref([
    { title: 'Name', value: 'name'},
	{ title: 'Date', value: 'date' },
	{ title: 'Destination', value: 'destination' },
	{ title: 'Purpose', value: 'purpose' },
	{ title: 'Departure', value: 'time_departure' },
	{ title: 'Return', value: 'time_return' },
    { title: 'Actions', value: 'actions' },
])
const allObReqData = ref([])

const fetchObReq = async () => {
	try{
		const res = await axios.get('get_all_ob')
		allObReqData.value = res.data
		console.log(res.data)
	}
	catch(error){
		console.error('Error fetching OT Request data', error)
	}
}

const approve = async (id, status) => {
    const result = await Swal.fire({
        text: "Are you sure you want to approve this OB request?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: "#32cd32",
        cancelButtonColor: '#ff3131'
    });

    if(result.isConfirmed){
        console.log(id, 'approve', status)
        axios({
            url: 'handle_ob_request',
            method: 'post',
            data: {
                ob_req_id : id,
                status : status,
            }
        }).then((res) => {
            console.log(res)
            fetchObReq()
            snackbar.value.alertApproved()
        }).catch((error) =>  {
            console.error(error)
            snackbar.value.alertCustom('There was an error approving the request.')
        })
    }
}

const disapprove = async (id, status) => {
    const result = await Swal.fire({
        text: "Are you sure you want to disapprove this OB request?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: "#32cd32",
        cancelButtonColor: '#ff3131'
    });

    if(result.isConfirmed){
        console.log(id, 'disapprove', status)
        axios({
            url: 'handle_ob_request',
            method: 'post',
            data: {
                ob_req_id : id,
                status : status,
            }
        }).then((res) => {
            console.log(res)
            fetchObReq()
            snackbar.value.alertDisapproved()
        }).catch((error) =>  {
            console.error(error)
            snackbar.value.alertCustom('There was an error approving the request.')
        })
    }
}

const cancel = async (id, status) => {
    const result = await Swal.fire({
        text: "Are you sure you want to cancel this OB request?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: "#32cd32",
        cancelButtonColor: '#ff3131'
    });

    if(result.isConfirmed){
        console.log(id, 'cancel', status)
        axios({
            url: 'handle_ob_request',
            method: 'post',
            data: {
                ob_req_id : id,
                status : status,
            }
        }).then((res) => {
            console.log(res)
            fetchObReq()
            snackbar.value.alerCancelled()
        }).catch((error) =>  {
            console.error(error)
            snackbar.value.alertCustom('There was an error approving the request.')
        })
    }
}

onMounted(async () => {
    fetchObReq()
})
</script>