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
					<!-- <v-text-field
						v-model="start"
						label="Start (HH:MM)"
						placeholder="e.g., 5:00">
					</v-text-field>
					<v-text-field
						v-model="end"
						label="End (HH:MM)"
						placeholder="e.g., 6:30">
					</v-text-field> -->
					<v-text-field
						v-model="time_duration"
						:active="menuStart"
						:focus="menuStart"
						label="Time Start"
						prepend-inner-icon="mdi-clock-time-four-outline"
						readonly
					>
						<v-menu
							v-model="menuStart"
							:close-on-content-click="false"
							activator="parent"
							transition="scale-transition">
							<v-time-picker
								format="24hr"
								v-if="menuStart"
								v-model="time_duration"
								full-width>
							</v-time-picker>
						</v-menu>
					</v-text-field>
					<v-text-field
						v-model="time_end"
						:active="menuEnd"
						:focus="menuEnd"
						label="Time End"
						prepend-inner-icon="mdi-clock-time-four-outline"
						readonly
					>
						<v-menu
							v-model="menuEnd"
							:close-on-content-click="false"
							activator="parent"
							transition="scale-transition">
							<v-time-picker
								format="24hr"
								v-if="menuEnd"
								v-model="time_end"
								full-width>
							</v-time-picker>
						</v-menu>
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
const time_duration = ref('');
const time_end = ref('');
const empData = ref({});
const otReqData = ref([])

const menuStart = ref(false)
const menuEnd = ref(false)

const snackbar = ref(null)
const headers = ref([
	{ title: 'Reason', value: 'reason' },
	{ title: 'Time Start', value: 'time_duration' },
	{ title: 'Time End', value: 'time_end' },
	{ title: 'Total Hours/Minutes', value: 'total_hrs_mins' },
	{ title: 'Status', value: 'status' },
])

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
    const startTime = time_duration.value
    const endTime = time_end.value

	console.log(startTime, '&', endTime)

    // const regex = /^(0?[1-9]|1[0-2]):[0-5]\d\s?(AM|PM)?$/i;
	const regex = /^(0?[0-9]|1[0-9]|2[0-3]):[0-5]\d$/
    if (!regex.test(startTime) || !regex.test(endTime)) {
        snackbar.value.alertCustom('Invalid duration format. Use HH:MM.')
        return
    }

    try {
        const response = await axios.post('submit_ot_request', {
            reason: reason.value,
            time_duration: startTime,
            time_end: endTime,
			// total_hrs_mins: total_hrs_mins
        })
        snackbar.value.alertSuccess()
        console.log(response.data.message)
        reason.value = ''
        time_duration.value = ''
        time_end.value = ''
        fetchOTReq()
    } catch (error) {
        snackbar.value.alertError()
        console.error('Error submitting OT request:', error.response.data)
    }
}


const loadEmployeeData = async () => {
	try{
		const res = await axios.get('employee') 
		empData.value = res.data
	} 
	catch(error){
		console.error('Error fetching employee data:', error)
	}
}

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
