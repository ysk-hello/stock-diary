/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import { createI18n } from 'vue-i18n';

import ja from '/lang/ja/i18n_ja.json';
import en from '/lang/en/i18n_en.json';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

// https://vue-i18n.intlify.dev/
const i18n = createI18n({
    locale: document.documentElement.lang, // set locale
    //locale:  window.navigator.language, // set locale
    //local: 'en',
    fallbackLocale: 'en', // set fallback locale
    messages: {
        ja : ja,
        en : en
    }
});
app.use(i18n);

// https://github.com/ankurk91/vue-toast-notification
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';
app.use(ToastPlugin);

// https://github.com/kouts/vue-modal/tree/next
// https://next--vue-modal-demo.netlify.app/
import Modal from '@kouts/vue-modal'
import '@kouts/vue-modal/dist/vue-modal.css'
app.component('modal', Modal);

// https://github.com/cloudeep/vuejs-paginate-next
import Paginate from 'vuejs-paginate-next';
app.component('paginate', Paginate);

import Diary from './components/Diary.vue';
app.component('diary', Diary);

import Calendar from './components/Calendar.vue';
app.component('calendar', Calendar);

import SearchCompany from './components/SearchCompany.vue';
app.component('search-company', SearchCompany);

import Favorite from './components/Favorite.vue';
app.component('favorite', Favorite);

import CompanyDiary from './components/CompanyDiary.vue';
app.component('companydiary', CompanyDiary);

import UploadCompany from './components/UploadCompany.vue';
app.component('upload-company', UploadCompany);

import SearchDiary from './components/SearchDiary.vue';
app.component('search-diary', SearchDiary);

import OutputDiary from './components/OutputDiary.vue';
app.component('output-diary', OutputDiary);

import Errors from './components/Errors.vue';
app.component('errors', Errors);

import Contact from './components/Contact.vue';
app.component('contact', Contact);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');
