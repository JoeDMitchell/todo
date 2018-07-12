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
            // ADD CLASSES ON ACTIONS
            s.addTask.on('focusin', function(){
                FormStyle.activeInput('active');
            });
            s.addTask.on('focusout', function(){
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