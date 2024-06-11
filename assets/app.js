// assets/app.js
import 'bootstrap';
import './styles/app.scss';

import { createApp } from 'vue';
import JobList from './components/JobList.vue';

const app = createApp({
    components: {
        JobList
    }
});

app.mount('#app');