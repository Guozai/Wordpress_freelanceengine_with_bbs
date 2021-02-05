(function($, Models, Collections, Views) {
    /*
     *
     * E D I T  B U L L E T I N  V I E W S
     *
     */
    Views.Profile = Backbone.View.extend({
        el: '.list-bulletin-wrapper',
        events: {
            // save bulletin
            'submit form.freelance-bulletin-form-save' : 'editBulletin',
            // edit bulletin
            'submit form.freelance-bulletin-form-edit' : 'editBulletin',
            // remove bulletin post
            'click a.remove_history_fre': 'openModalRemoveHistoryFre',
            // show and hide box edit profile
            'click .bulletin-show-edit-tab-btn' : 'showEditTab',
        },
        initialize: function() {
            //set current profile
            if ($('#current_profile').length > 0) {
                this.profile = new Models.Profile(JSON.parse($('#current_profile').html()));
            } else {
                this.profile = new Models.Profile();
            }
            var view = this;
            this.blockUi = new Views.BlockUi();
            this.user = AE.App.user;
            //get id from the url
            var hash = window.location.hash;
            hash && $('ul.nav a[href="' + hash + '"]').tab('show');

            // update value for post content editor
            if (typeof tinyMCE !== 'undefined' && !$('body').hasClass('author')) {
                setTimeout(function(){
                    tinymce.EditorManager.execCommand('mceAddEditor', true, "post_content");
                    if(view.profile.get('post_content')){
                        tinymce.EditorManager.get('post_content').setContent(view.profile.get('post_content'));
                    }
                },1000);
            }
        },

        saveBulletin: function(event) {
            event.preventDefault();
            event.stopPropagation();

            var form = $(event.currentTarget),
                button = form.find('.btn-submit'),
                view = this,
                temp = new Array();

            /**
             * call validator init
             */
            //this.initValidator();
            this.bulletin_validator = form.validate({
                ignore: "",
                rules: {
                    "bulletin[title]": "required",
					"bulletin[category]": {
                        required : {
                            depends: function(element) {
                                var form_ = element.closest('form');
                                var validate_filed = $('#country',form_).attr('data-validate_filed');
                                if( validate_filed == '0' ){
                                    $('.novalidate_if_current',form_).removeClass('error');
                                    return false;
                                }else {
                                    return true;
                                }
                            }
                        }
                    },
					"bulletin[language]": {
                        required : {
                            depends: function(element) {
                                var form_ = element.closest('form');
                                var validate_filed = $('#country',form_).attr('data-validate_filed');
                                if( validate_filed == '0' ){
                                    $('.novalidate_if_current',form_).removeClass('error');
                                    return false;
                                }else {
                                    return true;
                                }
                            }
                        }
                    },
                    "bulletin[content]": "required"
                }
            });

            /**
             * scan all fields in form and set the value to model user
             */
            form.find('input, textarea, select').each(function() {
                view.profile.set($(this).attr('name'), $(this).val());
            });
            /**
             * update input check box to model
             */
            form.find('input[type=checkbox]').each(function(){
                var name = $(this).attr('name');
                if (name !== "et_receive_mail_check") {
                    view.profile.set(name, null);
                }
            });
            form.find('input[type=checkbox]:checked').each(function() {
                var name = $(this).attr('name');
                if (typeof temp[name] !== 'object') {
                    temp[name] = new Array();
                }
                temp[name].push($(this).val());
                view.profile.set(name, temp[name]);
            });
            /**
             * update input radio to model
             */
            form.find('input[type=radio]:checked').each(function() {
                view.profile.set($(this).attr('name'), $(this).val());
            });
            // check form validate and process sign-in
            if (this.bulletin_validator.form() && !form.hasClass("processing")) {
                this.profile.save('', '', {
                    beforeSend: function() {
                        view.blockUi.block(button);
                        form.addClass('processing');
                    },
                    success: function(profile, status, jqXHR) {
                        view.blockUi.unblock();
                        form.removeClass('processing');
                        // trigger event process authentication
                        AE.pubsub.trigger('ae:user:profile', profile, status, jqXHR);

                        // trigger event notification
                        if (status.success) {
                            AE.pubsub.trigger('ae:notification', {
                                msg: status.msg,
                                notice_type: 'success',
                            });
                            location.reload();
                        } else {
                            AE.pubsub.trigger('ae:notification', {
                                msg: status.msg,
                                notice_type: 'error',
                            });
                        }
                    }
                });
            }
        },

        editBulletin: function(event) {
            event.preventDefault();
            event.stopPropagation();

            var form = $(event.currentTarget),
                button = form.find('.btn-submit'),
                view = this,
                temp = new Array();

            /**
             * call validator init
             */
            //this.initValidator();
            this.bulletin_validator = form.validate({
                ignore: "",
                rules: {
                    "bulletin[title]": "required",
					"bulletin[category]": {
                        required : {
                            depends: function(element) {
                                var form_ = element.closest('form');
                                var validate_filed = $('#country',form_).attr('data-validate_filed');
                                if( validate_filed == '0' ){
                                    $('.novalidate_if_current',form_).removeClass('error');
                                    return false;
                                }else {
                                    return true;
                                }
                            }
                        }
                    },
					"bulletin[language]": {
                        required : {
                            depends: function(element) {
                                var form_ = element.closest('form');
                                var validate_filed = $('#country',form_).attr('data-validate_filed');
                                if( validate_filed == '0' ){
                                    $('.novalidate_if_current',form_).removeClass('error');
                                    return false;
                                }else {
                                    return true;
                                }
                            }
                        }
                    },
                    "bulletin[content]": "required"
                }
            });

            /**
             * scan all fields in form and set the value to model user
             */
            form.find('input, textarea, select').each(function() {
                view.profile.set($(this).attr('name'), $(this).val());
            });
            /**
             * update input check box to model
             */
            form.find('input[type=checkbox]').each(function(){
                var name = $(this).attr('name');
                if (name !== "et_receive_mail_check") {
                    view.profile.set(name, null);
                }
            });
            form.find('input[type=checkbox]:checked').each(function() {
                var name = $(this).attr('name');
                if (typeof temp[name] !== 'object') {
                    temp[name] = new Array();
                }
                temp[name].push($(this).val());
                view.profile.set(name, temp[name]);
            });
            /**
             * update input radio to model
             */
            form.find('input[type=radio]:checked').each(function() {
                view.profile.set($(this).attr('name'), $(this).val());
            });
            // check form validate and process sign-in
            if (this.bulletin_validator.form() && !form.hasClass("processing")) {
                //this.profile.set('method', 'update');
                this.profile.save('', '', {
                    beforeSend: function() {
                        view.blockUi.block(button);
                        form.addClass('processing');
                    },
                    success: function(profile, status, jqXHR) {
                        view.blockUi.unblock();
                        form.removeClass('processing');
                        // trigger event process authentication
                        AE.pubsub.trigger('ae:user:profile:update', profile, status, jqXHR);

                        // trigger event notification
                        if (status.success) {
                            AE.pubsub.trigger('ae:notification', {
                                msg: status.msg,
                                notice_type: 'success',
                            });
                            location.reload();
                        } else {
                            AE.pubsub.trigger('ae:notification', {
                                msg: status.msg,
                                notice_type: 'error',
                            });
                        }
                    }
                });
            }
        },

        openModalRemoveHistoryFre: function(event) {
            event.preventDefault();
            var id = $(event.currentTarget) .attr('data-id');
            var last = $(event.currentTarget) .closest('ul').find('li').length;
            $('#modal_delete_meta_history').find('form').attr('data-processing','no');
            this.modalPortfolio = new Views.Modal_Add_Portfolio({
                el: '#modal_delete_meta_history',
                collection: {
                    id : id,
                    last : last
                }
            });
            this.modalPortfolio.openModal();
        },

        showEditTab: function (e) {
            e.preventDefault();
            var obj = $(e.currentTarget);
            var tab_id = obj.attr('data-ctn_edit');
            var tab_hide = obj.attr('data-ctn_hide');
            $('#'+tab_id).fadeIn();
            if(tab_hide){
                $('#'+tab_hide).fadeOut();
            }
            obj.closest('.cnt-bulletin-hide').css('display','none');
        },
    });
})(jQuery, window.AE.Models, window.AE.Collections, window.AE.Views);