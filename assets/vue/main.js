import { createApp } from 'vue';
import App from "./App";
import router from "./router";
import store from "./store"
import VueToast from "vue-toast-notification"

const app = createApp(App);

app.use(router)
app.use(store)
app.use(VueToast)

app.mount("#app")