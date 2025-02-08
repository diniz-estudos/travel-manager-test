import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'
import { API_BASE_URL } from './config.js'
import toastr from 'toastr'
import 'toastr/build/toastr.min.css'
import 'primeicons/primeicons.css'

axios.defaults.baseURL = API_BASE_URL

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": true,
    "progressBar": false,
    "positionClass": "toast-top-full-width",
    "preventDuplicates": true,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

const app = createApp(App)
app.config.globalProperties.$toastr = toastr
app.use(createPinia())
app.use(router)
app.mount('#app')
