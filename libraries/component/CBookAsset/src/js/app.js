var $ = require('jquery');
window.jQuery = $;
window.$ = $;
require('popper.js')
require('bootstrap');
require('bootstrap-switch');
require('codemirror');
require('xml');

import environment from './environment'

window.saveBookContentChanges = () => {
    var form = {};
    $('.editable').each(function(i) {
        form[`${$(this).data('name')}`] = $(this).html()
    })
    console.log(form)
    $.post(environment.apiEndPoint, form)
        .done(function(data) {
            console.log(data)
        })
        .fail(function(error) {
            console.log(error)
        })
}