(function ($) {
    'use strict';    
    $(document).ready(function () {
        
        // for details
        $(document).find('#ays_quiz_user_dashboard_all_results').DataTable({
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
    });
    
})(jQuery);
