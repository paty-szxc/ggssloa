<template>
    <v-container>
        <v-card elevation="6">
            <v-card-title class="pa-5">Personal Information</v-card-title>
            <v-row class="px-5" dense>
                <v-col :cols="cols">
                    <v-text-field
                        hide-details
                        label="Employee No."
                        v-model="empData.emp_id">
                    </v-text-field>
                </v-col>
                <v-col :cols="cols">
                    <v-text-field
                        hide-details
                        label="Employee Name"
                        v-model="empData.emp_name">
                </v-text-field>
                </v-col>
                <v-col :cols="cols">
                    <v-text-field
                        hide-details
                        label="Position"
                        v-model="empData.emp_position">
                    </v-text-field>
                </v-col>
                <v-col :cols="cols">
                    <v-date-input
                        hide-details
                        variant="outlined"
                        density="compact"
                        prepend-icon=""
                        prepend-inner-icon="mdi-calendar"
                        label="Date Hired"
                        v-model="empData.date_hired"
                    >
                    </v-date-input>
                </v-col>
                <v-col :cols="cols">
                    <v-date-input
                        hide-details
                        variant="outlined"
                        density="compact"
                        prepend-icon=""
                        prepend-inner-icon="mdi-calendar"
                        label="Leave Renewal Date"
                        v-model="empData.leave_renewal_date"
                    />
                </v-col>
                <v-col :cols="cols">
                    <v-text-field
                        hide-details
                        label="Total Leave Credits"
                        v-model="formattedLeaveCredits"
                        readonly>
                    </v-text-field>
                    <!-- <div>
                        <label>Total Leave Credits</label>
                        <div>{{ formattedLeaveCredits }}</div>
                    </div> -->
                </v-col>
                <v-col :cols="cols">
                    <v-text-field
                        hide-details
                        v-model="leaveUsed"
                        label="Total Leave Used">
                    </v-text-field>
                </v-col>
                <v-col :cols="cols">
                    <v-text-field
                        hide-details
                        label="Remaining Leave"
                        v-model="remainingLeaveCredits">
                    </v-text-field>
                </v-col>
                <v-col :cols="cols">
                    <v-text-field
                        hide-details
                        v-model="withoutPay"
                        label="Leave w/o Pay">
                    </v-text-field>
                </v-col>
            </v-row>
            <div class="pa-5 text-h6">
                LEGENDS: 
                <span v-for="(item, index) in legendItems" :key="index">
                    <v-chip :color="item.color" dark style="margin-right: 4px;">
                    {{ item.label }}
                    </v-chip>
                </span>
            </div>
            <DataTable
                :headers="headers"
                :leaveData="empData?.leave_details"
                @open-dialog="openAddDialog">
            </DataTable>
        </v-card>

        <v-dialog v-model="addDialog">
            <v-card>
                <v-card-title 
                    style="background: linear-gradient(135deg, #0047AB, #50C878); 
                    color: white;">
                    Add Leave
                </v-card-title>
                <v-card-text>
                    <v-autocomplete 
                        density="compact"
                        item-value="id"
                        item-title="leave_name"
                        :items="leaveTypeData" 
                        label="Leave Type" 
                        variant="outlined" 
                        v-model="add.leave_type">
                    </v-autocomplete>
                    <v-date-input
                        density="compact"
                        label="Leave From:"
                        prepend-icon=""
                        prepend-inner-icon="mdi-calendar"
                        variant="outlined"
                        v-model="add.leave_from"
                    />
                    <v-date-input
                        density="compact"
                        label="Leave To:"
                        prepend-icon=""
                        prepend-inner-icon="mdi-calendar"
                        variant="outlined"
                        v-model="add.leave_to"
                    />
                    <v-text-field
                        label="No. of Days"
                        v-model="numberOfDays">
                    </v-text-field>
                    <div style="display: flex; align-items: center;">
                        <v-checkbox 
                            direction="horizontal" 
                            density="compact" 
                            hide-details="auto" 
                            v-model="add.filed"
                            label="Filed">
                        </v-checkbox>
                        <v-checkbox
                            class="ml-5"
                            direction="horizontal" 
                            density="compact" 
                            hide-details="auto" 
                            v-model="add.with_pay"
                            label="With Pay">
                        </v-checkbox>
                    </div>
                    <v-textarea 
                        label="Reasons" 
                        variant="outlined"
                        v-model="add.reasons">
                    </v-textarea>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn 
                        @click="closeAddDialog"
                        color="red darken-1" 
                        text>
                        Cancel
                    </v-btn>
                    <v-btn 
                        @click="submitForm"
                        color="green darken-1" 
                        text>
                        Submit
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import DataTable from '../components/DataTable.vue';
import { useDisplay } from 'vuetify';

// const leaveType = ref(['Sick', 'Vacation', 'Parental Leave', 'Paternity Leave', 'Bereavement Leave'])
// const value = ref('Vacation')
const empData = ref({})
const add = ref({})
// const forApproval  = ref(true)
const leaveData =  ref([])
const leaveTypeData = ref([])
const headers = ref([
    { title: 'Date Filed', value: 'date_filed' },
    { title: 'Leave From', value: 'leave_from' },
    { title: 'Leave To', value: 'leave_to' },
    { title: 'No. of Days', value: 'no_of_days' },
    { title: 'Leave Type', value: 'leave_type' },
    { title: 'Filed', value: 'filed' },
    { title: 'W/Pay', value: 'with_pay' },
    { title: 'Reasons', value: 'reasons' },
    { title: 'Status', value: 'actions' }
]);


const { name } = useDisplay();
const cols = computed(() => {
    switch(name.value) {
        case 'xs': return 12;
        case 'sm': return 6;
        case 'md': return 4;
        case 'lg': return 3;
        case 'xl': return 3;
        case 'xxl': return 3;
    }
})

const legendItems = ref([
    { label: 'For Approval', color: 'green' },
    { label: 'Disapproved/AWOL', color: 'red' },
    { label: 'Cancelled', color: 'orange' },
]);

const addDialog = ref(false)

const openAddDialog = () => {
    addDialog.value = true
}

const closeAddDialog = () => {
    addDialog.value = false
    add.value = {}
}

function submitForm(){
    const  to_insert = add.value;
    
    if(!add.value.leave_type){
        alert('Fill up empty fields!')
    }
    else{
        console.log('insert')
        axios({
            url: '/add_leave',
            method: 'post',
            data: {
                to_insert
            }
        }).then((res) => {
            console.log(res)
            loadData()
            addDialog.value = false
        })
        // alert('Okay')
    }
}


function loadData(){
    axios({
        url:'/leave_data',
        method: 'get'
    }).then((res) => {
        leaveData.value = res.data
        console.log(res.data);
        
    }).catch(err => {
        console.error('Error fetching leave data:', err)
    });

    axios({
        url: '/employee',
        method: 'get'
    }).then((res) => {
        empData.value = res.data; 

        console.log(empData.value.leave_details.length > 0)
    }).catch(err => {
        console.error('Error fetching employee data:', err);
    });

    axios({
        url: '/leave_type',
        method: 'get'
    }).then((res) => {
        leaveTypeData.value = res.data; 
    }).catch(err => {
        console.error('Error fetching employee data:', err);
    });
}

//onmounted
onMounted(async () => {
    loadData()
});

//computed
const formattedLeaveCredits = computed(() => {
    return `SL: ${empData.value.sick_leave} VL: ${empData.value.vacation_leave}`;
});

const remainingLeaveCredits = computed(() => {
    let cntSick = 0;
    let cntVacation = 0;
    //use optional chaining to avoid errors if empData.value is undefined
    const leaveDetails = empData.value?.leave_details || [];

    if (leaveDetails.length > 0) {
        leaveDetails.forEach(el => {
            if (el.leave_type == 1) {
                cntSick += el.no_of_days; //use += to accumulate
            } else {
                cntVacation += el.no_of_days; //use += to accumulate
            }
        });
    }
    return `SL: ${isNegative(empData.value.sick_leave - cntSick) ? 0 : empData.value.sick_leave - cntSick} VL: ${isNegative(empData.value.vacation_leave - cntVacation) ? 0 : empData.value.vacation_leave - cntVacation}`;
});

function isNegative(value) {
    return value < 0;
}

const leaveUsed = computed(() => {
    let cntLeaveUsed = 0;
    let cntSick = 0
    let cntVacation = 0
    
    const leaveDetails = empData.value?.leave_details || [];
    if (leaveDetails.length > 0) {
        leaveDetails.forEach(el => {
            if(el.leave_type == 1){
                cntSick += el.no_of_days;
            }
            else{
                cntVacation += el.no_of_days
            }
        });
    }
    return `SL: ${cntSick} VL: ${cntVacation}`;
});

const withoutPay = computed(() => {
    let cntwithoutPay = 0;
    const leaveDetails = empData.value?.leave_details || [];
    if (leaveDetails.length > 0) {
        leaveDetails.forEach(el => {
            if(el.with_pay == 0){
                cntwithoutPay ++
            }
        });
    }
    return cntwithoutPay;
});

const numberOfDays = computed(() => {
    const fromDate = new Date(add.value.leave_from);
    const toDate = new Date(add.value.leave_to);
    
    if (fromDate && toDate && fromDate <= toDate){
        const timeDiff = toDate - fromDate;
        const days = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
        add.value.no_of_days = days
        return days;
        // return Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
    }
    return '';
});


</script>

<style scoped>

</style>