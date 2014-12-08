var AtingoPassForm = new Class({
    Implements: [Options, Events],

    options: {
        storage: null,
        list: null,
        fields: {
            sitename: null,
            url: null,
            token: null
        }
    },

    itemTmpl: '<tr data-id="{id}" data-sitename="{sitename}" data-url="{url}" data-token="{token}">' +
    '<td>{sitename}</td><td>{url}</td><td>{token}</td>' +
    '<td><a href="#edit" title="editar"><i class="fa fa-pencil"></i></a> &nbsp; ' +
    '<a href="#remove" title="eliminar"><i class="fa fa-times"></i></a>' +
    '</td></tr>',

    initialize: function(options) {
        this.setOptions(options);

        this.initializeUI();

        this.render();
    },

    get: function() {
        var value = $(this.options.storage).get('value');
        if('' == value) {
            return [];
        }

        return JSON.decode(value.fromBase64());
    },

    set: function(value) {
        $(this.options.storage).set('value', JSON.encode(value).toBase64());
    },

    save: function(event) {
        event.preventDefault();
        event.stopPropagation();

        var data = {};

        var id = $('ap-id').get('value');

        data.sitename = $('ap-sitename').get('value');
        data.url      = $('ap-url').get('value');
        data.token    = $('ap-token').get('value');

        var current = this.get();

        if(id == '') {
            current.push(data);
        } else {
            current[parseInt(id)] = data;
        }

        this.set(current);

        this.render();
        this.reset();
    },

    render: function() {
        'use strict';

        var html='', data = this.get();

        for(var i=0; i<data.length; i++) {
            data[i].id = i;
            html += this.itemTmpl.substitute(data[i]);
        }

        $(this.options.list).getFirst('tbody').set('html', html);

        $$('table#atingopass-list tbody a[href=#edit]').addEvent('click', this.edit.bind(this));
        $$('table#atingopass-list tbody a[href=#remove]').addEvent('click', this.remove.bind(this));
    },

    edit: function(event) {
        event.preventDefault();
        event.stopPropagation();

        var dataset = $(event.target).getParent('tr').dataset;

        $('ap-id').set('value',       dataset.id);
        $('ap-sitename').set('value', dataset.sitename);
        $('ap-url').set('value',      dataset.url);
        $('ap-token').set('value',    dataset.token);
    },

    remove: function(event) {
        event.preventDefault();
        event.stopPropagation();

        if(confirm('¿?')) {
            var id = $(event.target).getParent('tr').dataset.id;
            var data = this.get(), newData = [];

            for(var i=0; i<data.length; i++) {
                if(id != i) {
                    newData.push(data[i]);
                }
            }

            this.set(newData);
            this.render();
        }
    },

    reset: function() {
        $('ap-id').set('value', '');
        $('ap-sitename').set('value', '');
        $('ap-url').set('value', '');
        $('ap-token').set('value', '');
    },

    keygen: function() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-+*;.:";

        for(var i=0; i < 32; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }

        $('ap-token').set('value', text);
    },

    initializeUI: function() {
        $('ap-button').addEvent('click', this.save.bind(this));
        $('ap-keygen').addEvent('click', this.keygen.bind(this));
    }
});