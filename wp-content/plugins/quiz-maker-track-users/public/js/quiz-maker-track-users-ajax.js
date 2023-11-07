(function( $ ) {
	'use strict';

    $.fn.serializeFormJSON = function () {
        var o = {},
            a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    /**
     * @return {string}
    */
    function GetFullDateTime(){
        var now = new Date();
        return [[now.getFullYear(), AddZero(now.getMonth() + 1), AddZero(now.getDate())].join("-"), [AddZero(now.getHours()), AddZero(now.getMinutes()), AddZero(now.getSeconds())].join(":")].join(" ");
    }

    $(document).ready(function(){
        var start,end;
        var visibility;
        var count = 0;
        var changedTime = 0;

        //Tab Visibility Change AJAX
        $(document).on('visibilitychange', function(){
            if (document.hidden) {
                start = Date.now();
            } else {
                count ++;
                end = Date.now();
                visibility = end - start;
                var data = {};
    
                data.visibility = visibility;
                data.action = 'get_track_users_hidden_tab';
    
                $.ajax({
                    url: quiz_maker_track_users_public_ajax.ajax_url,
                    method: 'post',
                    dataType: 'json',
                    data: data,
                    success: function (response) {
                        if(response.status){
                            changedTime += response.result;
                            $(document).find('.ays_quiz_track_users').attr('data-hidden',changedTime);
                            $(document).find('.ays_quiz_track_users').attr('data-count',count);
                        }
                    }
                });
            }
        });

        //Start Button AJAX
        $(document).on('click', '.start_button', function(){
            var startDate = GetFullDateTime();
            var form = $(this).parents('form');
            var data = form.serializeFormJSON();
            var quizId = data.quiz_id;
            var userDataStart = {};
            var parents = $(this).parents('.ays-quiz-container');

            userDataStart.quiz_Id = quizId;
            userDataStart.start_date = startDate;
            userDataStart.action = 'get_track_users_start_quiz_date';
            $.ajax({
                url: quiz_maker_track_users_public_ajax.ajax_url,
                method: 'post',
                dataType: 'json',
                data: userDataStart,
                success: function (response) {
                    if(response.status){
                        var hiddenInput = '<input type="hidden" class="ays_quiz_track_users" data-id="'+response.last_inserted_id+'" data-quiz="'+response.quiz_id+'">';
                        parents.append(hiddenInput);
                    }
                }
            });
        });        

        //Finish Quiz AJAX        
        $(document).find('form[id^="ays_finish_quiz_"]').on('getResultId', function(event){ 
            var endDate = GetFullDateTime();
            var form = $(this);
            var data = form.serializeFormJSON();
            var quizId = data.quiz_id;
            var resultId = event.detail.resultId;
            var userDataEnd = {};
            var hiddenTab = $(this).parents('.ays-quiz-container').find('.ays_quiz_track_users').attr('data-hidden');
            var hiddenTabCount = $(this).parents('.ays-quiz-container').find('.ays_quiz_track_users').attr('data-count');
            var copyCount = $(this).parents('.ays-quiz-container').find('.ays_quiz_track_users').attr('data-copy');
            var hintCount = $(this).parents('.ays-quiz-container').find('.ays_quiz_track_users').attr('data-hint');

            userDataEnd.quiz_Id = quizId;
            userDataEnd.end_date = endDate;
            userDataEnd.hidden_tab = hiddenTab;
            userDataEnd.hidden_tab_count = hiddenTabCount;
            userDataEnd.copy_count = copyCount;
            userDataEnd.hint_count = hintCount;
            userDataEnd.result_id = resultId;
            userDataEnd.action = 'get_track_users_end_quiz_date';

            $.ajax({
                url: quiz_maker_track_users_public_ajax.ajax_url,
                method: 'post',
                dataType: 'json',
                data: userDataEnd,
                success: function (response) {
                    if(response.status){

                    }
                }
            });

        });
    });

})( jQuery );
