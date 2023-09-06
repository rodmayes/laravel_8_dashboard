/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap')
import 'bootstrap';
import 'admin-lte/dist/js/adminlte.min';
import 'jquery/src/jquery';
//import flatpickr from "flatpickr";
//import { Spanish } from "flatpickr/dist/l10n/es"
//latpickr.localize(Spanish);

import $ from 'jquery';
window.$ = window.jQuery = $;

import 'select2/dist/js/select2.full'
import 'bootstrap4-datetimepicker/build/js/bootstrap-datetimepicker.min';

/*
    import 'select2/dist/js/select2.full.min';
    import 'bootstrap4-datetimepicker';
*/
/* Sidebar - Side navigation menu on mobile/responsive mode */
window.toggleNavbar = function (collapseID) {
  document.getElementById(collapseID).classList.toggle('hidden')
  document.getElementById(collapseID).classList.toggle('bg-white')
  document.getElementById(collapseID).classList.toggle('m-2')
  document.getElementById(collapseID).classList.toggle('py-3')
  document.getElementById(collapseID).classList.toggle('px-6')
}

/* Opens sidebar navigation that contains sub-items */
window.openSubNav = function (el) {
  let parent = el.parentElement

  let subnavs = document.getElementsByClassName('subnav')
  for (let i = 0; i < subnavs.length; i++) {
    if (!subnavs[i].classList.contains('hidden')) {
      subnavs[i].classList.add('hidden')
    }
  }

  parent.getElementsByClassName('subnav')[0].classList.remove('hidden')
}

window.initialSubNavLoad = function () {
  let active = document.getElementsByClassName('has-sub sidebar-nav-active')
  if (active[0]) {
    window.openSubNav(active[0])
  }
}

initialSubNavLoad()
/* Opens sidebar navigation that contains sub-items */

