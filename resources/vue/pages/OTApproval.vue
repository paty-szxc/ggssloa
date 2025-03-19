<template>
    <v-container>
        <v-card>
            <v-card-title><p class="text-h4">Overtime Approval List</p></v-card-title>
            <v-card-text>
                <v-data-table
                    :headers="headers"
                    :items="allotReqData">
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

        <v-snackbar
            color="success"
            location="top right"
            :timeout="3500" 
            variant="outlined"
            v-model="snackbar"
            >
            {{ snackbarMessage }}
        </v-snackbar>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const snackbar = ref(false);
const snackbarMessage = ref('');
const headers = ref([
    { title: 'Name', value: 'name'},
	{ title: 'Reason', value: 'reason' },
	{ title: 'Time', value: 'time_duration' },
	{ title: 'Actions', value: 'actions' },
])
const allotReqData = ref([])

const fetchOTReq = async () => {
	try{
		const res = await axios.get('/get_all_ot_request')
		allotReqData.value = res.data
		console.log(res.data)
	}
	catch(error){
		console.error('Error fetching OT Request data', error)
	}
}

const approve = async (id, status) => {
    const result = await Swal.fire({
        text: "Are you sure you want to approve this OT request?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: "#32cd32",
        cancelButtonColor: '#ff3131'
    });

    if(result.isConfirmed){
        console.log(id, 'approve', status)
        axios({
            url: '/update_ot_req',
            method: 'post',
            data: {
                ot_req_id : id,
                status : status,
            }
        }).then((res) => {
            console.log(res)
            fetchLeaveData()
            snackbarMessage.value = 'The leave request has been approved.';
            snackbar.value = true;
        }).catch((error) =>  {
            console.error(error)
            snackbarMessage.value = 'There was an error approving the leave request.';
            snackbar.value = true;
        })
    }
};

onMounted(async () => {
    fetchOTReq()
})
</script>