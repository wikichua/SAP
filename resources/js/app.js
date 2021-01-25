require("./bootstrap");

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

const CodeMirror = window.CodeMirror = require('codemirror/src/codemirror');
const SimpleMDE = window.SimpleMDE = require('simplemde/dist/simplemde.min');
const Push = window.Push = require('push.js/bin/push.min');
require('summernote/dist/summernote-bs4');
require('gijgo/js/gijgo');
require('bootstrap-table');
require('bootstrap-table/dist/extensions/resizable/bootstrap-table-resizable');
require('bootstrap-table/dist/extensions/sticky-header/bootstrap-table-sticky-header');
require('bootstrap-table/dist/extensions/reorder-rows/bootstrap-table-reorder-rows');
