import { createApp } from 'vue'
import { Quasar } from 'quasar'
import { createPinia } from 'pinia'

// Import icon libraries
import '@quasar/extras/material-icons/material-icons.css'

// Import Quasar css
import 'quasar/src/css/index.sass'

// Assumes your root component is App.vue
// and placed in same folder as main.js
import App from './App.vue'
import router from './router'

// Import CSS
import './css/app.scss'

// Import stores
import { useAuthStore } from './stores/auth'

const myApp = createApp(App)

myApp.use(Quasar, {
  plugins: {
    Notify: {},
    Dialog: {},
    Loading: {}
  }
})

const pinia = createPinia()
myApp.use(pinia)
myApp.use(router)

// Initialize auth store
const authStore = useAuthStore()
authStore.initializeAuth()

// Assumes you have a <div id="q-app"></div> in your index.html
myApp.mount('#q-app')
