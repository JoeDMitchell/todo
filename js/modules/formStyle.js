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