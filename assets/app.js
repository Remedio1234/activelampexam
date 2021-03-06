/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/global.scss';

// start the Stimulus application
import './bootstrap';

//Vue js
import Vue from 'vue';
import store        from './js/store'
import App from './js/components/App';
new Vue({
    el: '#app',
    store,
    render: h => h(App)
});