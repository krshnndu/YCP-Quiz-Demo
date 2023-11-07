(function ($) {
    'use strict';    
    $(document).ready(function () {
        var html_class_prefix = 'ays-quiz-user-dashboard-';
        var $html_id_prefix    = '#ays-user-dashboard-';
        var html_name_prefix = 'ays-quiz-';
        var name_prefix = 'ays_quiz_';
        var unique_id;
        var unique_id_in_class;

        // for details
        $.fn.aysModal = function(action){
            var $this = $(this);
            switch(action){
                case 'hide':
                    $(this).find('.ays-modal-content').css('animation-name', 'zoomOut');
                    setTimeout(function(){
                        $(document).find('html, body').removeClass('modal-open');
                        $(document).find('.ays-modal-backdrop').remove();
                        $this.hide();
                    }, 250);
                    break;
                case 'show':
                default:
                    $this.show();
                    $(this).find('.ays-modal-content').css('animation-name', 'zoomIn');
                    $(document).find('.modal-backdrop').remove();
                    $(document.body).append('<div class="ays-modal-backdrop"></div>');
                    $(document).find('html, body').addClass('modal-open');
                    break;
            }
        }
        
        $(document).on("keydown", function(e){
            if(e.keyCode === 27){
                $(document).find('.ays-modal').aysModal('hide');
                return false;
            }
        });
        
        $(document).find('div.ays-modal').appendTo($(document.body));

        // Modal close
        $(document).on('click', '.ays-close', function () {
            var _this = $(this);
            _this.parents('.ays-modal').aysModal('hide');
        });
        
        /*
        ==========================================
        User History
        ==========================================
        */

        // User History by Quiz 
        $(document).on('change', '.'+html_class_prefix+'quizzes', function(){
            var $_this = $(this);
            var userID = $(this).attr('data-user');
            var quizID = $(this).find(':selected').attr('data-id');
            var uniqueID = $(this).parent().find('input[type="hidden"]').attr('data-unique');
            var uniqueIdInClass = $(this).parent().find('input[type="hidden"]').attr('data-class');
            var parent = $_this.parents('.'+html_class_prefix+'content-result-by-quiz').find('.'+html_class_prefix+'result-by-quiz')
            var quizResultsDiv = $_this.parents('.'+html_class_prefix+'content-result-by-quiz').find('.'+html_class_prefix+'result-by-quiz').find('div.'+html_class_prefix+'results-by-quiz-tables');
           
            var userData = {};

            $_this.parents('.'+html_class_prefix+'content-result-by-quiz').find('div.ays-quiz-user-dashboard-preloader').css('display', 'flex');

            userData.action = 'get_results_by_quiz';
            userData.user_id = userID;
            userData.quiz_id = quizID;
            userData.unique_id = uniqueID;

            $.ajax({
                url: user_dashboard_public_ajax.ajax_url,
                method: 'post',
                dataType: 'json',
                data: userData,
                success: function (response) {
                    if(response.status){
                        $_this.parents('.'+html_class_prefix+'content-result-by-quiz').find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');

                        quizResultsDiv.first().remove();
                        parent.append(response.content);
                        var emptyData = $_this.parents('.'+html_class_prefix+'content-result-by-quiz').find('.'+html_class_prefix+'result-by-quiz table#ays_quiz_user_dashboard_results_by_quiz_'+quizID+'').attr('data-result');

                        if(emptyData == 'not_empty'){                            
                            $_this.parents('.'+html_class_prefix+'content-result-by-quiz').find('#ays_quiz_user_dashboard_results_by_quiz_'+quizID+'').DataTable({
                                "language": {
                                    "sEmptyTable":     quizUserDashboardLangDataTableObj.sEmptyTable,
                                    "sInfo":           quizUserDashboardLangDataTableObj.sInfo,
                                    "sInfoEmpty":      quizUserDashboardLangDataTableObj.sInfoEmpty,
                                    "sInfoFiltered":   quizUserDashboardLangDataTableObj.sInfoFiltered,
                                    "sInfoPostFix":    "",
                                    "sInfoThousands":  ",",
                                    "sLengthMenu":     quizUserDashboardLangDataTableObj.sLengthMenu,
                                    "sLoadingRecords": quizUserDashboardLangDataTableObj.sLoadingRecords,
                                    "sProcessing":     quizUserDashboardLangDataTableObj.sProcessing,
                                    "sSearch":         quizUserDashboardLangDataTableObj.sSearch,
                                    "sUrl":            "",
                                    "sZeroRecords":    quizUserDashboardLangDataTableObj.sZeroRecords,
                                    "oPaginate": {
                                        "sFirst":    quizUserDashboardLangDataTableObj.sFirst,
                                        "sLast":     quizUserDashboardLangDataTableObj.sLast,
                                        "sNext":     quizUserDashboardLangDataTableObj.sNext,
                                        "sPrevious": quizUserDashboardLangDataTableObj.sPrevious,
                                    },
                                    "oAria": {
                                        "sSortAscending":  quizUserDashboardLangDataTableObj.sSortAscending,
                                        "sSortDescending": quizUserDashboardLangDataTableObj.sSortDescending
                                    }
                                }
                            });
                           
                        }

                    }else{
                        $_this.parents('.'+html_class_prefix+'content-result-by-quiz').find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');
                    }
                }
            });

        });

        //User History Details
        $(document).on('click', '.'+html_class_prefix+'details', function(){
            var $_this = $(this);
            var userData = {};
            var resultId = $_this.attr('data-id');
            var resultsContainer = $_this.parents('.ays-quiz-user-results-container');
            var uniqueID = resultsContainer.attr('data-id');
            var parent = $(document).find( '.ays-results-modal-'+uniqueID);

            parent.find('div.ays-quiz-user-dashboard-preloader').css('display', 'flex');
            parent.aysModal('show');
            

            userData.action = 'get_user_reports_info_popup_ajax';
            userData.result_id = resultId;
            userData.unique_id = uniqueID;
            
            $.ajax({
                url: user_dashboard_public_ajax.ajax_url,
                method: 'post',
                dataType: 'json',
                data: userData,
                success: function (response) {
                    if(response.status){
                        parent.find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');
                        parent.find('div.ays-modal-body').html(response.details);
                    }else{
                        swal.fire({
                            type: 'info',
                            html: "<h2>"+ AysQuizUserDashboardObj.loadResource +"</h2><br><h4>"+ AysQuizUserDashboardObj.dataDeleted +"</h4>",
                        }).then(function(response){
                            parent.find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');
                            parent.aysModal('hide');
                        });
                    }
                },
                error: function(){
                    swal.fire({
                        type: 'info',
                        html: "<h2>"+ AysQuizUserDashboardObj.loadResource +"</h2><br><h4>"+ AysQuizUserDashboardObj.dataDeleted +"</h4>",
                    }).then(function(response){
                        parent.find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');
                        parent.aysModal('hide');
                    });
                }
            });

        });

        // Export result to pdf
        $(document).on('click','.ays-quiz-user-dasboard-export-pdf', function (e) {
            e.preventDefault();

            var $this  = $(this);
            var parent = $this.parents('.ays-modal');
            var uniqueID = parent.attr('data-id');
            var result_id    = $this.attr('data-result');
            var pdf_uniqueID = $this.attr('data-unique-id');
            var action       = 'get_user_reports_pdf_ajax';

            $this.parents('.ays-modal').find('div.ays-quiz-user-dashboard-preloader').css('display', 'flex');
            $this.attr('disabled');


            if ( uniqueID === pdf_uniqueID ) {
                $.ajax({
                    url: user_dashboard_public_ajax.ajax_url,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: action,
                        result: result_id,
                    },
                    success: function (response) {
                        if (response.status) {
                            $this.parent().find('#downloadFileF').attr({
                                'href': response.result.fileUrl,
                                'download': response.result.fileName,
                            })[0].click();
                            window.URL.revokeObjectURL(response.result.fileUrl);
                        }else{
                            swal.fire({
                                type: 'info',
                                html: "<h2>"+ AysQuizUserDashboardObj.loadResource +"</h2><br><h4>"+ AysQuizUserDashboardObj.dataDeleted +"</h4>",
                            })
                        }
                        $this.parents('.ays-modal').find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');
                        $this.removeClass('disabled');
                    }
                });
            } else {
                swal.fire({
                    type: 'info',
                    html: "<h2>"+ AysQuizUserDashboardObj.loadResource +"</h2><br><h4>"+ AysQuizUserDashboardObj.dataDeleted +"</h4>",
                })
                $this.parents('.ays-modal').find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');
                $this.removeClass('disabled');
            }
            
        });

        /*
        ==========================================
        Best Score
        ==========================================
        */
        var mode = 'Percentage';
        var quizID = '';

        $(document).on('change', '.'+html_class_prefix+'mode', function(){
            mode = $(this).val();
        });

        $(document).on('change', '.'+html_class_prefix+'best-score-quizzes', function(){
            var $_this = $(this);
            quizID = $(this).find(':selected').attr('data-id');
        });

        $(document).on('click', '.'+html_class_prefix+'check', function(){
            var $_this = $(this);
            var userData = {};
            var quizMode = mode;
            var userID = $_this.attr('data-user');
            var quizId = quizID;
            var  parentDiv = $_this.parents('.'+html_class_prefix+'content-best-score').find('div.'+html_class_prefix+'best-score');

            parentDiv.find('div.ays-quiz-user-dashboard-preloader').css('display', 'flex');

            userData.action = 'user_best_score_by_quiz_ajax';
            userData.user_id = userID;
            userData.quiz_id = quizId;
            userData.mode = quizMode;
            
            $.ajax({
                url: user_dashboard_public_ajax.ajax_url,
                method: 'post',
                dataType: 'json',
                data: userData,
                success: function (response) {
                    if(response.status){
                        parentDiv.find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');
                        parentDiv.children().first().remove(); 
                        parentDiv.append(response.result);
                    }else{
                        swal.fire({
                            type: 'info',
                            html: "<h2>"+ AysQuizUserDashboardObj.loadResource +"</h2><br><h4>"+ AysQuizUserDashboardObj.dataDeleted +"</h4>",
                        }).then(function(response){
                            parentDiv.find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');
                        });
                    }
                },
                error: function(){
                    swal.fire({
                        type: 'info',
                        html: "<h2>"+ AysQuizUserDashboardObj.loadResource +"</h2><br><h4>"+ AysQuizUserDashboardObj.dataDeleted +"</h4>",
                    }).then(function(response){
                        parentDiv.find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');
                    });
                }
            });
        });

        /*
        ==========================================
        User Progress
        ==========================================
        */

        var userProgressMode = 'Score';
        var userProgressQuizID = '';

        $(document).on('change', '.'+html_class_prefix+'user-progress-mode', function(){
            userProgressMode = $(this).val();
        });

        $(document).on('change', '.'+html_class_prefix+'user-progress-quizzes', function(){
            var $_this = $(this);
            userProgressQuizID = $(this).find(':selected').attr('data-id');
        });

        $(document).on('click', '.'+html_class_prefix+'user-progress-check', function(){
            var $_this = $(this);
            var userData = {};
            var userID = $_this.attr('data-user');
            var uniqueID = $(this).parents('.'+html_class_prefix+'content-user-progress').attr('data-id');
            var quizId = userProgressQuizID;
            var quizMode = userProgressMode;
            var parentDiv = $_this.parents('.'+html_class_prefix+'content-user-progress').find('div.'+html_class_prefix+'user-progress');

            parentDiv.find('div.ays-quiz-user-dashboard-preloader').css('display', 'flex');

            userData.action = 'user_progress_by_quiz_ajax';
            userData.user_id = userID;
            userData.quiz_id = quizId;
            userData.mode = quizMode;
            userData.unique_id = uniqueID;

            $.ajax({
                url: user_dashboard_public_ajax.ajax_url,
                method: 'post',
                dataType: 'json',
                data: userData,
                success: function (response) {
                    if(response.status){
                        parentDiv.find('.'+html_class_prefix+'user-progress-container').first().remove();
                        if(response.result != ''){
                            if (typeof google != undefined) {
                                google.charts.load('current', {packages: ['corechart']});
                                google.charts.setOnLoadCallback(drawBasic);
                            }
                    
                            $(document).find( '.'+html_class_prefix+'user-progress-container' ).hover( function(e){
                                var _this  = $(this);
                    
                                var circle = _this.find('svg circle');
                                if (circle.length == 1) {
                                    circle.attr('r',5);
                                    circle.attr('fill-opacity',1);
                                }
                    
                            }, function(e) {
                                var _this  = $(this);
                    
                                var circle = _this.find('svg circle');
                                if (circle.length == 1) {
                                    circle.attr('r',5);
                                    circle.attr('fill-opacity',1);
                                }
                            });
                    
                            function drawBasic() {
                                var quizUserProgressConainter = $(document).find( '.'+html_class_prefix+ 'user-progress-container');
                                quizUserProgressConainter.each( function( e, i ) {
                                    var _this = $(this);
                                    
                                    var uniqueId = _this.attr( 'data-id' );
                                    var mode     = _this.attr( 'data-mode' );
                                    
                                    var thisAysQuizPublicChartData = JSON.parse( atob( window.aysQuizPublicUserProgressData[ uniqueId ] ) );

                                    var divId    = $html_id_prefix + 'user-progress-chart-' + mode + '-' + uniqueId;
                                    var divClass = '.'+html_class_prefix+ 'user-progress-box';
                    
                                    var chartDivId     = _this.find( divId );
                                    var chartDivIdAttr = _this.find( divClass ).attr('id');
                    
                                    var modeText = mode.charAt(0).toUpperCase() + mode.slice(1);
                    
                                    var viewWindow = {};
                                    if (mode == 'Score') {
                                        var viewWindow = {  
                                            max: 100,
                                            min: 0,
                                        }
                                    }
                    
                                    var data = new google.visualization.DataTable();
                                    data.addColumn('number', AysQuizUserDashboardObj.attempt);
                                    data.addColumn('number', mode);
                                    // data.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
                    
                                    data.addRows(
                                        thisAysQuizPublicChartData
                                    );
                    
                                    var options = {
                                        height: 300,
                                        // tooltip: {
                                        //     isHtml: true
                                        // },
                                        hAxis: {
                                            title: AysQuizUserDashboardObj.attempt
                                        },
                                        vAxis: {
                                            title: modeText,
                                            viewWindow,
                                            // format: '0',
                                        },
                                        legend: {position: 'none'}
                                    };
                    
                                    var logChart = new google.visualization.LineChart(document.getElementById( chartDivIdAttr ));
                                    logChart.draw(data, options);
                    
                                    if (thisAysQuizPublicChartData.length == 1) {
                                        setTimeout(function(){
                                            var chartCircle = chartDivId.find( 'svg circl e' );
                                            chartCircle.attr('r',5);
                                            chartCircle.attr('fill-opacity',1);
                                        },500);
                                    }
                                });
                            }
                            parentDiv.find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');
                                parentDiv.append(response.result);
                        }else{
                            parentDiv.find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');
                            var noResult = '<div class="'+html_class_prefix+'user-progress-container"><p>There is no result yet</p></div>';
                            parentDiv.append(noResult);
                        }
                    }else{
                        swal.fire({
                            type: 'info',
                            html: "<h2>"+ AysQuizUserDashboardObj.loadResource +"</h2><br><h4>"+ AysQuizUserDashboardObj.dataDeleted +"</h4>",
                        }).then(function(response){
                            parentDiv.find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');
                        });
                    }
                },
                error: function(){
                    swal.fire({
                        type: 'info',
                        html: "<h2>"+ AysQuizUserDashboardObj.loadResource +"</h2><br><h4>"+ AysQuizUserDashboardObj.dataDeleted +"</h4>",
                    }).then(function(response){
                        parentDiv.find('div.ays-quiz-user-dashboard-preloader').css('display', 'none');
                    });
                }
            });
        });
    });
    
})(jQuery);
