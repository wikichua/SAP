require("./bootstrap");
import route from "ziggy";
import {
    Ziggy
} from "./ziggy";
require('bootstrap-select');

window.moment = require('moment');
window.daterangepicker = require('bootstrap-daterangepicker');
moment().format();

import swal from 'sweetalert2';
const Swal = window.Swal = swal;
const Toast = window.Toast = Swal.mixin({
  toast: true,
  position: 'bottom-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
});

const Handlebars = window.Handlebars = require("handlebars");
require('summernote/dist/summernote-bs4');