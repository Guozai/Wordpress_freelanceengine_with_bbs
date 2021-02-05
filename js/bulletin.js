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
            'click a.remove_history_fre': 'RemoveBulletin',
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
                id = button .attr('data-id'),
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
                this.profile.save('ID', id, {
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

        RemoveBulletin: function(event) {
            event.preventDefault();
            var id = $(event.currentTarget) .attr('data-id');
            var last = $(event.currentTarget) .closest('ul').find('li').length;
            var obj = $(event.currentTarget);
            var view = this;
            obj.attr('data-processing','yes');
            $.ajax({
                type: "post",
                url: ae_globals.ajaxURL,
                dataType: 'json',
                data: {
                    action: 'ae-profile-delete-meta',
                    ID : id
                },
                beforeSend: function () {
                    obj.attr('disabled', true).css('opacity', '0.5');
                    view.blockUi.block(obj);
                },
                success: function (data, statusText, xhr) {
                    if (data.success) {
                        AE.pubsub.trigger('ae:notification', {
                            msg: data.msg,
                            notice_type: 'success'
                        });
                        if(last == 3){
                            $('.meta_history_item_'+id).closest('.fre-profile-box').find('.fre-empty-optional-profile').fadeIn();
                        }
                        $('.meta_history_item_'+id).remove();

                    } else {
                        AE.pubsub.trigger('ae:notification', {
                            msg: data.msg,
                            notice_type: 'error'
                        });
                    }
                    obj.attr('disabled', false).css('opacity', '1');
                    view.blockUi.unblock();
                }
            });
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