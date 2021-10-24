import axios from 'axios';

const CONFIG = {
    API_URL_ROOT: document.head.querySelector('meta[name="api-base-url"]').content,
}

axios.defaults.baseURL = CONFIG.API_URL_ROOT;

window.API = axios;
