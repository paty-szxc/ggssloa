<template>
    <v-container>
        <v-card>
			<v-card-title>Official Business Form</v-card-title>
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
						v-model="add.date_filed"
                    />
					<v-text-field
						label="Destination"
						v-model="add.destination">
					</v-text-field>
					<v-text-field
						label="Purpose(s)"
						v-model="add.purpose">
					</v-text-field>
					<v-text-field
						label="Departure (HH:MM)"
						v-model="add.time_departure"
						placeholder="e.g., 1:00">
					</v-text-field>
					<v-text-field
						label="Return (HH:MM)"
						v-model="add.time_return"
						placeholder="e.g., 1:00">
					</v-text-field>
					<v-btn 
						@click="submit"
						color="green-lighten-1">
						Submit
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
					:items="obData"
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
					style="background: linear-gradient(135deg, #0047AB, #50C878); 
                    color: white;">
					Edit Form
				</v-card-title>
				<v-card-text>
					<v-form ref="editForm" v-model="editValid">
						<v-date-input
							density="compact"
							label="Date"
							prepend-icon=""
							prepend-inner-icon="mdi-calendar"
							variant="outlined"
							v-model="editData.date"
						/>
						<v-text-field
							label="Destination"
							v-model="editData.destination">
						</v-text-field>
						<v-text-field
							label="Purpose(s)"
							v-model="editData.purpose">
						</v-text-field>
						<v-text-field
							label="Departure (HH:MM)"
							v-model="editData.time_departure"
							placeholder="e.g., 1:00">
						</v-text-field>
						<v-text-field
							label="Return (HH:MM)"
							v-model="editData.time_return"
							placeholder="e.g., 1:00">
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
								@click="updateLeave"
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
import axios from 'axios';
import { ref, onMounted } from 'vue';
import Snackbar from '../components/Snackbar.vue';

const snackbar = ref(null)
const obData = ref([])
const add = ref({})
const valid = ref(false);
const empData = ref({});
const editData = ref({})
const editValid = ref(false)
const updateDialog = ref(false)

const headers = ref([
	{ title: 'Date', value: 'date' },
	{ title: 'Destination', value: 'destination' },
	{ title: 'Purpose', value: 'purpose' },
	{ title: 'Departure', value: 'time_departure' },
	{ title: 'Return', value: 'time_return' },
	{ title: 'Status', value: 'status' },
])

const legendItems = ref([
    { label: 'For Approval', color: 'blue' },
    { label: 'Approved', color: 'green' },
    { label: 'Disapproved/AWOL', color: 'red' },
    { label: 'Cancelled', color: 'orange' },
]);

const closeEditDialog = () => {
	updateDialog.value = false
}

const openEditDialog = (event) => {
	const item = event.item
	console.log('Selected item:', item)
	editData.value = { ...item }
	updateDialog.value = true
}

function updateLeave(){
	const to_update = {
        id: editData.value.id,
        date: editData.value.date,
        destination: editData.value.destination,
        purpose: editData.value.purpose,
        time_departure: editData.value.time_departure,
        time_return: editData.value.time_return
    }
	axios({
		url: 'update_ob',
		method: 'post',
		data: {
			to_update
		}
	}).then((res) => {
		console.log(res)
		fetchObReq()
		snackbar.value.alertUpdate()
		updateDialog.value = false
		editData.value = {}
	}).catch(() => {
		snackbarMessage.value = 'There was an error updating your request. Please try again.';
		snackbar.value = true
	});
}

const submit = async () => {
    //validate the format before sending the request
    const regex = /^(0|[1-9]\d*):[0-5]\d$/; //regex to match HH:MM format
    if (!regex.test(add.value.time_departure)) {
        snackbar.value.alertCustom('Invalid format. Use HH:MM.');
        return; //prevent submission if format is invalid
    }

	try{
		const res = await axios.post('add_ob', {
			emp_name: empData.value.emp_name,
			date: new Date(add.value.date_filed),
			destination: add.value.destination,
			purpose: add.value.purpose,
			time_departure: add.value.time_departure,
			time_return: add.value.time_return || null,
		});
		snackbar.value.alertSuccess();
		console.log(res.data.message);
		add.value = {}
		fetchObReq()
	} 
	catch(error){
		if(error.res && error.res.data.errors){
		console.log(error.res.data.errors);
		} 
		else{
		console.error('An error occurred:', error);
		}
	}
};

const fetchObReq = async () => {
	try{
		const res = await axios.get('get_ob_data')
		obData.value = res.data
		console.log(res.data)
		
	} 
	catch(error){
		console.error('Error fetching OB Request data', error)
	}
}

const loadEmployeeData = async () => {
	try{
		const res = await axios.get('employee'); 
		empData.value = res.data;
	} 
	catch(error){
		console.error('Error fetching employee data:', error);
	}
};

onMounted(() => {
	loadEmployeeData()
	fetchObReq()
})
</script>