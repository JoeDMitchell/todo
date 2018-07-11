
var AjaxFunctions = (function() {
    var s;

    return{

        settings: function() {
            s = {
                theContent: $('.changeable-content'),
                toDoForm: $('#to-do-form'),
                ajax: '',
                button: $('#to-do-form input[type="checkbox"]'),
                progressBar: $('.progress__bar'),
                total: $('#to-do-form input[type="checkbox"]').length,
                totalChecked: $('#to-do-form input[type="checkbox"]').filter(":checked").length,
            }
        },

        init: function() {
            this.settings();
            this.bindUIActions();
        },

        bindUIActions: function() {
            s.button.off().on('change', function(){
                AjaxFunctions.updateDone();
            })
        },

        updateDone: function(){

            s.theContent.addClass('is-changing');

            var update_done = function(formData,percent){
                s.ajax = $.ajax({
                    type       : "POST",
                    data       : {formData : formData},
                    dataType   : "html",
                    url        : "includes/functions/ajax.php",
                    beforeSend : function(){
                    },
                    success    : function(data){

                        data = $(data);
                        data.fadeIn();
                        s.theContent.html(data);
                        s.theContent.removeClass('is-changing');
                        AjaxFunctions.init();
                        FormStyle.init();

                        s.progressBar.css('width',percent+'%');

                        setTimeout(function (){

                            s.total = s.button.length;
                            s.totalChecked = s.button.filter(":checked").length;
                            var percent = (s.totalChecked / s.total) * 100;

                            s.progressBar.css('width',percent+'%');

                        }, 200);
                        
                    },
                    error     : function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
                    }
                });
            }

            setTimeout(function (){

                var percent = (s.totalChecked / s.total) * 100;

                var formData = $(s.toDoForm).serialize();

                if (s.ajax){
                    s.ajax.abort();
                }

                update_done(formData,percent);

            }, 200);

           
        },


    };

})();
var FormStyle = (function() {
    var s;

    return{

        settings: function() {
            s = {
                addTask: $('input#task'),
            }
        },

        init: function() {
            this.settings();
            this.bindUIActions();
        },

        bindUIActions: function() {
            console.log('boop');
            s.addTask.on('focusin', function(){
                console.log('boop1');
                FormStyle.activeInput('active');
            });
            s.addTask.on('focusout', function(){
                console.log('boop2');
                FormStyle.activeInput('inactive');
            });
        },

        activeInput: function(activity){
            if (activity == 'active'){
                s.addTask.addClass('is-active');
            } else {
                s.addTask.removeClass('is-active');
            }
        },


    };

})();
//// initialise modules

(function() {

	AjaxFunctions.init();
	AjaxFunctions.updateDone();
	FormStyle.init();

})();