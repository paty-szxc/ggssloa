import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import '@mdi/font/css/materialdesignicons.css'

import { VTextField } from 'vuetify/components/VTextField'
import { VDateInput } from 'vuetify/labs/VDateInput'

const vuetify = createVuetify({
    components: {
        VTextField,
        VDateInput
    },

    defaults: {
        VTextField: {
            variant: 'outlined',
            density: 'compact',
        }
    }
})

export default vuetify;