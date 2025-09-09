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
					<v-date-input
                        density="compact"
                        label="Date"
                        prepend-icon=""
                        prepend-inner-icon="mdi-calendar"
                        variant="outlined"
						v-model="add.date"
                    />					
					<v-text-field
						v-model="add.reason"
						label="Reason for OT">
					</v-text-field>
					<v-text-field
						v-model="add.time_duration"
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
								v-model="add.time_duration"
								full-width>
							</v-time-picker>
						</v-menu>
					</v-text-field>
					<v-text-field
						v-model="add.time_end"
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
								v-model="add.time_end"
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

				<div class="pa-5 text-h6">
                LEGENDS: 
					<span v-for="(item, index) in legendItems" :key="index">
						<v-chip :color="item.color" dark style="margin-right: 4px;">
						{{ item.label }}
						</v-chip>
					</span>
					<span style="margin-left: 50px; font-style: italic; color: red;">
						NOTE: DOUBLE CLICK THE ROW TO EDIT DETAILS
					</span>
				</div>


				<v-data-table
					density="compact"
					:headers="headers"
					:items="otReqData"
					@dblclick:row="(item, event) => openEditDialog(event, item)">
					<template v-slot:item.status="{ item }">
						<span v-if="item.status === 0" style="color: blue;">For Approval</span>
						<span v-else-if="item.status === 1" style="color: green;">Approved</span>
						<span v-else-if="item.status === 2" style="color: red;">Disapproved</span>
						<span v-else-if="item.status === 3" style="color: orange;">Cancelled</span>
					</template>
				</v-data-table>
			</v-card-text>
		</v-card>

		<v-dialog v-model="updateDialog" persistent no-click-animation width="500">
			<v-card>
				<v-card-title
					color="white"
					style="background: linear-gradient(135deg, #0047ab, #50c878);">
					Edit
				</v-card-title>
				<v-card-text>
					<v-form ref="editForm" v-model="editValid">
						<!-- <v-date-input
							density="compact"
							hide-details
							label="Date"
							prepend-icon=""
							prepend-inner-icon="mdi-calendar"
							variant="outlined"
							v-model="editData.date"
						/> -->
						<v-text-field
							class="mt-3"
							hide-details
							v-model="editData.date"
							:active="dateMenu"
							:focus="dateMenu"
							label="Date"
							prepend-inner-icon="mdi-calendar"
							readonly
						>
							<v-menu
								v-model="dateMenu"
								:close-on-content-click="true"
								activator="parent"
								transition="scale-transition"
							>
								<v-date-picker
									v-if="dateMenu"
									v-model="editData.date"
									full-width
								></v-date-picker>
							</v-menu>
						</v-text-field>
						<v-text-field
							class="mt-3"
							hide-details
							label="Reason"
							v-model="editData.reason">
						</v-text-field>
						<v-text-field
							class="mt-3"
							hide-details
							v-model="editData.time_duration"
							:active="editMenuStart"
							:focus="editMenuStart"
							label="Time Start"
							prepend-inner-icon="mdi-clock-time-four-outline"
							readonly>
							<v-menu
								v-model="editMenuStart"
								:close-on-content-click="true"
								activator="parent"
								transition="scale-transition">
								<v-time-picker
									format="24hr"
									v-if="editMenuStart"
									v-model="editData.time_duration"
									full-width>
								</v-time-picker>
							</v-menu>
						</v-text-field>
						<v-text-field
							class="mt-3"
							hide-details
							v-model="editData.time_end"
							:active="editMenuEnd"
							:focus="editMenuEnd"
							label="Time End"
							prepend-inner-icon="mdi-clock-time-four-outline"
							readonly
						>
							<v-menu
								v-model="editMenuEnd"
								:close-on-content-click="false"
								activator="parent"
								transition="scale-transition">
								<v-time-picker
									format="24hr"
									v-if="editMenuEnd"
									v-model="editData.time_end"
									full-width>
								</v-time-picker>
							</v-menu>
						</v-text-field>
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn 
								@click="closeEditDialog"
								color="red darken-1"
								text>
								Cancel
							</v-btn>
							<v-btn
								@click="updateOtReq"
								color="green darken-1"
								text>
								Submit
							</v-btn>
						</v-card-actions>
					</v-form>
				</v-card-text>
			</v-card>
		</v-dialog>

		<Snackbar ref="snackbar"></Snackbar>
	</v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Snackbar from '../components/Snackbar.vue';

const valid = ref(false)
const add = ref({})
const empData = ref({})
const otReqData = ref([])
const dateMenu = ref(false)
const menuStart = ref(false)
const menuEnd = ref(false)
const updateDialog = ref(false)
const editData = ref({})
const editValid = ref(false)
const editMenuStart = ref(false)
const editMenuEnd = ref(false)

const snackbar = ref(null)
const headers = ref([
	{ title: 'Date', value: 'date' },
	{ title: 'Reason', value: 'reason' },
	{ title: 'Time Start', value: 'time_duration' },
	{ title: 'Time End', value: 'time_end' },
	{ title: 'Total Hours/Minutes', value: 'total_hrs_mins' },
	{ title: 'Status', value: 'status' },
])

const legendItems = ref([
    { label: 'For Approval', color: 'blue' },
    { label: 'Approved', color: 'green' },
    { label: 'Disapproved/AWOL', color: 'red' },
    { label: 'Cancelled', color: 'orange' },
]);

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

function updateOtReq(){
	let formattedDate;
    try {
        // Check if editData.value.date is already a string in YYYY-MM-DD format
        if (typeof editData.value.date === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(editData.value.date)) {
            formattedDate = editData.value.date;
        } else {
            // Handle Date object or other formats
            const dateObj = new Date(editData.value.date);
            if (isNaN(dateObj.getTime())) {
                throw new Error('Invalid date');
            }
            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const day = String(dateObj.getDate()).padStart(2, '0');
            formattedDate = `${year}-${month}-${day}`;
        }
    } catch (error) {
        console.error('Date formatting error:', error);
        snackbarMessage.value = 'Invalid date format. Please select a valid date.';
        snackbar.value = true;
        return;
    }


	const to_update = {
		id: editData.value.id,
		date: formattedDate,
        reason: editData.value.reason,
        time_duration: editData.value.time_duration,
        time_end: editData.value.time_end
    }
	axios({
		url: 'update_ot_request',
		method: 'post',
		data: {
			to_update
		}
	}).then((res) => {
		console.log(res)
		fetchOTReq()
		snackbar.value.alertUpdate()
		updateDialog.value = false
		editData.value = {}
	}).catch(() => {
		snackbarMessage.value = 'There was an error updating your request. Please try again.'
		snackbar.value = true
	})
}

const openEditDialog = (event) => {
    const item = event.item;
    console.log('Selected item:', item);
    
    // Convert time from 12-hour format to 24-hour format
    const convertTo24HourFormat = (time) => {
        const [timePart, modifier] = time.split(' ');
        let [hours, minutes] = timePart.split(':');
        if (modifier === 'PM' && hours !== '12') {
            hours = parseInt(hours, 10) + 12;
        }
        if (modifier === 'AM' && hours === '12') {
            hours = '00';
        }
        return `${hours}:${minutes}`;
    };

	const dateObj = item.date ? new Date(item.date) : null;

    editData.value = {
		id: item.id,
		date: dateObj,
        reason: item.reason || '',
        time_duration: convertTo24HourFormat(item.time_duration) || '00:00', // Convert to 24-hour format
        time_end: convertTo24HourFormat(item.time_end) || '00:00' // Convert to 24-hour format
    };
    
    updateDialog.value = true;
}

const closeEditDialog = () => {
	updateDialog.value = false
}

const submitRequest = async () => {
    const startTime = add.value.time_duration
    const endTime = add.value.time_end

	console.log(startTime, '&', endTime)

    // const regex = /^(0?[1-9]|1[0-2]):[0-5]\d\s?(AM|PM)?$/i;
	const regex = /^(0?[0-9]|1[0-9]|2[0-3]):[0-5]\d$/
    if(!regex.test(startTime) || !regex.test(endTime)){
        snackbar.value.alertCustom('Invalid duration format. Use HH:MM.')
        return
    }

    try {
        const response = await axios.post('submit_ot_request', {
			date: new Date(add.value.date),
            reason: add.value.reason,
            time_duration: startTime,
            time_end: endTime,
			// total_hrs_mins: total_hrs_minsJ
        })
        snackbar.value.alertSuccess()
        console.log(response.data.message)
        add.value = {}
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
