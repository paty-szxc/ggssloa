<template>
    <v-container>
        <v-data-table
            :headers="props.headers"
            :items="props.leaveData"
            item-key="user_id">
            <template v-slot:item.actions="{ item }">
                <span v-if="item.status === 0" style="color: green;">
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
    </v-container>
</template>

<script setup>
import { defineProps } from 'vue';

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

const emit = defineEmits();
const addBtn = () => {
    emit('open-dialog');
};

</script>
