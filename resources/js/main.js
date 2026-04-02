import { createApp } from 'vue'
import App from '@/App.vue'
import { registerPlugins } from '@core/utils/plugins'
import '@core-scss/template/index.scss'
import '@styles/styles.scss'

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

const app = createApp(App)

app.config.globalProperties.$baseUrl = 'https://fW.infotech-solucoes.com/storage';

app.use(VueSweetalert2)
app.component('VueDatePicker', VueDatePicker);

registerPlugins(app)
app.mount('#app')