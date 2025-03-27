<template>
	<v-container>
		<v-card>
			<v-card-title>OT Request</v-card-title>
			<v-card-text>
				<v-form ref="form" v-model="valid">
					<v-text-field
						label="Requester Name"
						v-model="empData.emp_name"
						readonly>
					</v-text-field>
					<v-text-field
						v-model="reason"
						label="Reason for OT">
					</v-text-field>
					<v-text-field
						v-model="duration"
						label="Duration (HH:MM)"
						placeholder="e.g., 0:30">
					</v-text-field>
					<v-btn 
						@click="submitRequest"
						color="green-lighten-1"
						:disabled="!valid">
						Submit Request
					</v-btn>
				</v-form>

				<v-data-table
					density="compact"
					:headers="headers"
					:items="otReqData">
					<template v-slot:item.status="{ item }">
						<span v-if="item.status === 0" style="color: blue;">For Approval</span>
						<span v-else-if="item.status === 1" style="color: green;">Approved</span>
						<span v-else-if="item.status === 2" style="color: red;">Disapproved</span>
						<span v-else-if="item.status === 3" style="color: orange;">Cancelled</span>
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
import Snackbar from '../components/Snackbar.vue';

const valid = ref(false);
const reason = ref('');
const duration = ref('');
const empData = ref({});
const otReqData = ref([])

const snackbar = ref(null)
const headers = ref([
	{ title: 'Reason', value: 'reason' },
	{ title: 'Time', value: 'time_duration' },
	{ title: 'Status', value: 'status' },
])

// const rules = {
// 	validDuration: (value) => {
// 		const regex = /^(0|[1-9]\d*):[0-5]\d$/; // Regex to match HH:MM format
// 		return regex.test(value) || 'Invalid duration format. Use HH:MM.';
// 	},
// };
const rules = {
    validDuration: (value) => {
        const regex = /^(0|[1-9]\d*):[0-5]\d$/; // Regex to match HH:MM format
        return regex.test(value) || 'Invalid duration format. Use HH:MM.';
    },
};

// const submitRequest = async () => {
// 	const [hours, minutes] = duration.value.split(':').map(Number); //split and convert to numbers
// 	const totalDuration = (hours * 60) + minutes; //calculate total duration in minutes

// 	if(totalDuration < 30){
// 		snackbar.value.alertCustom('Duration must be at least 30 minutes.')
// 		return; //prevent submission if duration is less than 30 minutes
// 	}
// 	try{
// 		const response = await axios.post('/submit_ot_request', {
// 			reason: reason.value,
// 			time_duration: duration.value, //send the duration in HH:MM format
// 		});
// 		snackbar.value.alertSuccess()
// 		console.log(response.data.message);
// 		reason.value = '';
// 		duration.value = '';
// 		fetchOTReq();
// 	} 
// 	catch(error){
// 		snackbar.value.alertError()
// 		console.error('Error submitting OT request:', error.response.data);
// 	}
// };

const submitRequest = async () => {
    const [hours, minutes] = duration.value.split(':').map(Number); // Split and convert to numbers
    const totalDuration = (hours * 60) + minutes; // Calculate total duration in minutes

    if (totalDuration < 30) {
        snackbar.value.alertCustom('Duration must be at least 30 minutes.');
        return; // Prevent submission if duration is less than 30 minutes
    }

    // Validate the format before sending the request
    const regex = /^(0|[1-9]\d*):[0-5]\d$/; // Regex to match HH:MM format
    if (!regex.test(duration.value)) {
        snackbar.value.alertCustom('Invalid duration format. Use HH:MM.');
        return; // Prevent submission if format is invalid
    }

    try {
        const response = await axios.post('submit_ot_request', {
            reason: reason.value,
            time_duration: duration.value, // Send the duration in HH:MM format
        });
        snackbar.value.alertSuccess();
        console.log(response.data.message);
        reason.value = '';
        duration.value = '';
        fetchOTReq();
    } catch (error) {
        snackbar.value.alertError();
        console.error('Error submitting OT request:', error.response.data);
    }
};


// Function to load employee data
const loadEmployeeData = async () => {
	try{
		const res = await axios.get('employee'); 
		empData.value = res.data;
	} 
	catch(error){
		console.error('Error fetching employee data:', error);
	}
};

const fetchOTReq = async () => {
	try{
		const res = await axios.get('get_ot_request')
		otReqData.value = res.data
		console.log(res.data)
	}
	catch(error){
		console.error('Error fetching OT Request data', error)
	}
}

// onMounted lifecycle hook
onMounted(() => {
	loadEmployeeData()
	fetchOTReq()
});
</script>

<style scoped>
/* Add any specific styles here */
</style>
