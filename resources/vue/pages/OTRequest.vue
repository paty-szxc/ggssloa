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
						:disabled="!valid">
						Submit Request
					</v-btn>
				</v-form>

				<v-data-table
					:headers="headers"
					:items="otReqData">
					<template v-slot:item.status="{ item }">
						<span v-if="item.status === 0" style="color: green;">For Approval</span>
						<span v-else-if="item.status === 1" style="color: green;">Approved</span>
						<span v-else-if="item.status === 2" style="color: red;">Disapproved</span>
						<span v-else-if="item.status === 3" style="color: orange;">Cancelled</span>
					</template>
				</v-data-table>
			</v-card-text>
		</v-card>
	</v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const valid = ref(false);
const reason = ref('');
const duration = ref('');
const empData = ref({});
const otReqData = ref([])

const headers = ref([
	{ title: 'Reason', value: 'reason' },
	{ title: 'Time', value: 'time_duration' },
	{ title: 'Status', value: 'status' },
])

const rules = {
	validDuration: (value) => {
		const regex = /^(0|[1-9]\d*):[0-5]\d$/; // Regex to match HH:MM format
		return regex.test(value) || 'Invalid duration format. Use HH:MM.';
	},
};

const submitRequest = async () => {
	const [hours, minutes] = duration.value.split(':').map(Number); // Split and convert to numbers
	const totalDuration = (hours * 60) + minutes; // Calculate total duration in minutes

	if (totalDuration < 30) {
		console.error('Duration must be at least 30 minutes.');
		return; // Prevent submission if duration is less than 30 minutes
	}

	try {
		const response = await axios.post('/submit_ot_request', {
			reason: reason.value,
			time_duration: duration.value, // Send the duration in HH:MM format
		});
		console.log(response.data.message); // Log success message
		// Optionally, reset the form or fetch OT requests again
		reason.value = '';
		duration.value = '';
		fetchOTReq(); // Refresh the OT requests
	} catch (error) {
		console.error('Error submitting OT request:', error.response.data);
	}
};


// Function to load employee data
const loadEmployeeData = async () => {
	try{
		const res = await axios.get('/employee'); 
		empData.value = res.data;
	} 
	catch(error){
		console.error('Error fetching employee data:', error);
	}
};

const fetchOTReq = async () => {
	try{
		const res = await axios.get('/get_ot_request')
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
