<template>
    <v-container>
        <v-data-table
            density="compact"
            fixed-footer
            fixed-header
            :headers="props.headers"
            :items="props.leaveData"
            item-key="user_id"
            @dblclick:row="(item, event) => editData(event, item)">
            <template v-slot:item.actions="{ item }">
                <span v-if="item.status === 0" style="color: blue;">
                    For Approval
                </span>
                <span v-else-if="item.status === 1" style="color: green;">
                    Approved
                </span>
                <span v-else-if="item.status === 2" style="color: red;">
                    Disapproved
                </span>
                <span v-else-if="item.status === 3" style="color: orange;">
                    Cancelled
                </span>
            </template> 
            <template v-slot:item.filed="{ item }">
                <v-icon v-if="item.filed === 1" color="blue">mdi-check-circle</v-icon>
                <v-icon v-else color="red">mdi-close-circle</v-icon>
            </template>
            <template v-slot:item.with_pay="{ item }">
                <v-icon v-if="item.with_pay === 1" color="blue">mdi-check-circle</v-icon>
                <v-icon v-else color="red">mdi-close-circle</v-icon>
            </template>
            <template v-slot:item.leave_type="{ item }">
                <span v-if="item.leave_type === 1">SICK</span>
                <span v-else-if="item.leave_type === 2">VACATION</span>
                <span v-else-if="item.leave_type === 3">PARENTAL</span>
                <span v-else-if="item.leave_type === 4">PATERNITY</span>
                <span v-else-if="item.leave_type === 5">BEREAVEMENT</span>
            </template>
        </v-data-table>
        <v-fab
            absolute
            @click="addBtn()"
            color="info"
            icon
            right bottom
            size="small">
            <v-icon>mdi-plus</v-icon>
        </v-fab>

        <Snackbar ref="snackbar"></Snackbar>
    </v-container>
</template>

<script setup>
import { defineProps, ref } from 'vue';
import Snackbar from './Snackbar.vue';

const props = defineProps({
    headers: {
        type: Array,
        required: true
    },
    leaveData: {
        type: Array,
        required: true,
        default: () => [] 
    }
});

const snackbar = ref(null)
const emit = defineEmits();
const addBtn = () => {
    emit('open-dialog');
};
// const editData = (item) => {
//     emit('edit-data', item.item);
// };

const editData = (item) => {
    const status = item.item.status;
    if(status === 0){
        emit('edit-data', item.item);
    } 
    else{
        console.log("Edit not allowed for this status:", status);
        snackbar.value.alertCustom("Already approved request can't be edited.")
    }
};
</script>