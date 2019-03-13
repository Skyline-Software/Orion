$.ajaxSetup({async: false});
/*const site_url = "https://api.ulitsarubinshteina.ru";*/
const site_url = "http://api.card.test";
const app = new Vue({
    el: '#app',
    data: {
        methods: methods,
        models: models,
        currentMethod: null,
        currentModel: null,
        response: null,
        authToken: '',
    },
    methods: {
        setModel: function(model) {
            this.currentMethod = null;
            this.currentModel = model;
            location.hash = '' + model.name;
            return false;
        },
        showMain: function() {
            this.currentMethod = null;
            this.currentModel = null;
            location.hash = '';
            return false;
        },
        setMethod: function(method) {
            this.response = '';
            this.authToken = $.cookie('auth_token');
            this.currentModel = null;
            this.currentMethod = method;
            location.hash = '' + method.name;
            return false;
        },
        getJsonFormData: function getJsonFormData() {
            const self = this
                , formData = {}
                , $form = $('#test-form')
            ;
            let parameter
                , $field
                , value
            ;
            for (let i = 0; i < self.currentMethod.parameters.length; i++) {
                parameter = self.currentMethod.parameters[i];
                if (parameter.type === 'text') {
                    $field = $form.find('input[name=' + parameter.name + ']');
                    value = $field.val();
                    if (value.trim().length > 0) {
                        formData[parameter.name] = value;
                    }
                    continue;
                }
                if (parameter.type === 'DATE(dd.mm.yy)') {
                    $field = $form.find('input[name=' + parameter.name + ']');
                    value = $field.val();
                    if (value.trim().length > 0) {
                        formData[parameter.name] = value;
                    }
                    continue;
                }
                if (parameter.type === 'checkbox') {
                    $field = $form.find('input[name=' + parameter.name + ']');
                    if ($field.prop('checked')) {
                        formData[parameter.name] = 'on';
                    }
                }
            }
            formData.method = self.currentMethod.name;
            return formData;
        },
        getEncodedFormData: function getEncodedFormData() {
            const self = this
                , formData = new FormData()
                , $form = $('#test-form')
            ;
            let parameter
                , $field
                , value
            ;
            for (let i = 0; i < self.currentMethod.parameters.length; i++) {
                parameter = self.currentMethod.parameters[i];
                if (parameter.type === 'text') {
                    $field = $form.find('input[name=' + parameter.name + ']');
                    value = $field.val();
                    if (value.trim().length > 0) {
                        formData.append(parameter.name, value);
                    }
                    continue;
                }
                if (parameter.type === 'DATE(dd.mm.yy)') {
                    $field = $form.find('input[name=' + parameter.name + ']');
                    value = $field.val();
                    if (value.trim().length > 0) {
                        formData.append(parameter.name, value);
                    }
                    continue;
                }
                if (parameter.type === 'checkbox') {
                    $field = $form.find('input[name=' + parameter.name + ']');
                    if ($field.prop('checked')) {
                        formData.append(parameter.name, 'on');
                    }
                    continue;
                }
                if (parameter.type === 'file') {
                    $field = $form.find('input[name=' + parameter.name + ']');
                    if ($field[0].files.length > 0) {
                        formData.append(parameter.name, $field[0].files[0]);
                    }
                }
            }
            formData.append('method', self.currentMethod.name);
            return formData;
        },
        getFormData: function getFormData() {
            const self = this;
            let hasFile = false;
            for (let i = 0; i < self.currentMethod.parameters.length; i++) {
                let parameter = self.currentMethod.parameters[i];
                if (parameter.type === 'file') {
                    hasFile = true;
                    break;
                }
            }
            if (hasFile) {
                return {
                    data: self.getEncodedFormData(),
                    type: 'encoded'
                };
            }
            return {
                data: self.getJsonFormData(),
                type: 'json'
            };
        },
        sendForm: function sendForm() {
            const self = this
                , $form = $('#test-form')
            ;
            self.response = '';
            let headers = {};
            if (self.currentMethod.secured) {
                headers = { 'Authorization': 'Bearer ' + $form.find('input[name=token]').val() };
            }
            // const formData = self.getFormData();
            const formData = self.getEncodedFormData();
            console.log(formData);
            $.ajax({
                method: $form.attr('method'),
                url: site_url + formData.get('method'),
                // dataType: 'json',
                // data: formData.type === 'json' ? JSON.stringify(formData.data) : formData.data,
                data: formData,
                headers: headers,
                cache: false,
                contentType: false,
                // contentType: formData.type === 'json' ? 'application/json' : false,
                processData: false
            }).done(function (response) {
                self.response = JSON.stringify(response);
                if (response.status && response.status === 'ok') {
                    if (self.currentMethod.name === '/user/login') {
                        self.authToken = response.result;
                        $.cookie('auth_token', self.authToken);
                    } else if (self.currentMethod.name === '/user/logout') {
                        self.authToken = '';
                        $.removeCookie('auth_token');
                    }
                }
            });
            return false;
        },
        searchMethod: function(name) {
            let method;
            for (let i = 0; i < this.methods.length; i++) {
                method = this.methods[i];
                if (method.name === name) {
                    return method;
                }
            }
        },
        searchModel: function (name) {
            let model;
            for (let i = 0; i < this.models.length; i++) {
                model = this.models[i];
                if (model.name === name) {
                    return model;
                }
            }
        }
    },
    mounted: function () {
        this.authToken = $.cookie('auth_token');
        if (location.hash.length > 1) {
            const method = this.searchMethod(location.hash.substr(1));
            if (method) {
                this.setMethod(method);
                return;
            }
            const model = this.searchModel(location.hash.substr(1));
            if (model) {
                this.setModel(model);
            }
        }
    }
});
window.app = app;