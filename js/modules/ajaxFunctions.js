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
                timer: $('.time-remaining'),
                timeHours: $('#time-remaining__hours'),
                timeMins: $('#time-remaining__mins'),
                interval: '',
            }
        },

        init: function() {
            this.settings();
            this.bindUIActions();
        },

        bindUIActions: function() {
            s.button.off().on('change', function(){
                // ON CHECKBOX CHANGE, RUN AJAX CALL
                AjaxFunctions.updateDone();
            })
            s.interval = setInterval(function(){
                // UPDATE TIME EVERY MINUTE
                AjaxFunctions.updateTime();
            }, 60000);
            
        },

        updateDone: function(){

            // THE CONTENT IS CHANGING, MAYBE ADD A LOADER?
            s.theContent.addClass('is-changing');

            var update_done = function(formData,percent){
                s.ajax = $.ajax({
                    type       : "POST",
                    data       : {form : formData},
                    dataType   : "html",
                    url        : "includes/functions/ajax.php",
                    beforeSend : function(){
                    },
                    success    : function(data){

                        // GET THE RETURNED HTML
                        data = $(data);
                        data.fadeIn();
                        // FADE IT IN
                        s.theContent.html(data);
                        s.theContent.removeClass('is-changing');
                        // REMOVE THE LOADER CLASS

                        // RE RUN ESSENTIAL FUNCTIONS
                        clearInterval(s.interval);
                        AjaxFunctions.init();
                        FormStyle.init();

                        // SET PROGRESS BAR WIDTH
                        s.progressBar.css('width',percent+'%');

                        setTimeout(function (){

                            s.total = s.button.length;
                            s.totalChecked = s.button.filter(":checked").length;
                            var percent = (s.totalChecked / s.total) * 100;

                            s.progressBar.css('width',percent+'%');

                            if (s.totalChecked == s.total && s.total > 0){
                                s.theContent.addClass('is-complete');
                                var random = Math.floor(Math.random() * 5) + 1;
                                s.theContent.addClass('is-complete--'+random);
                            }

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

        updateTime: function(){
            var hours = parseInt(s.timeHours.html());
            var mins = parseInt(s.timeMins.html());

            s.timer.removeClass('is-done');

            if (mins > 0){
                mins = mins - 1;
                s.timeMins.addClass('is-changing');
            } else {
                if (hours > 0){
                    hours = hours - 1;
                    mins = 59;
                    s.timeHours.addClass('is-changing');
                    s.timeMins.addClass('is-changing');
                } else {
                    s.timer.addClass('is-done');
                }
            }

            s.timeHours.html(hours);
            s.timeMins.html(mins);

            

            setTimeout(function (){
                s.timeHours.removeClass('is-changing');
                s.timeMins.removeClass('is-changing');
            }, 200);


        }


    };

})();