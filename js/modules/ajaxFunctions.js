var AjaxFunctions = (function() {
    var s;

    return{

        settings: function() {
            s = {
                theContent: $('.changeable-content'),
                toDoForm: $('#to-do-form'),
                ajax: '',
                button: $('#to-do-form input[type="checkbox"]'),
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

            var update_done = function(formData){
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
                        
                    },
                    error     : function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
                    }
                });
            }

            setTimeout(function (){

                var formData = $(s.toDoForm).serialize();

                if (s.ajax){
                    s.ajax.abort();
                }

                update_done(formData);

            }, 200);

           
        },


    };

})();