(function ($) {
    'use strict';

    $(document).on('click', '[data-slug="quiz-maker-add-on-track-users"] .deactivate a', function () {
        swal({
            html:"<h2>Do you want to upgrade to Pro version or permanently delete the plugin?</h2><ul><li>Keep Data: Your data will be saved for upgrade.</li><li>Delete: Your data will be deleted completely.</li></ul>",
            type: 'question',
            showCloseButton: true,
            showCancelButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Keep Data',
            cancelButtonText: 'Delete',
            confirmButtonClass: "ays-quiz-upgrade-button"
        }).then(function(result) {

            if( result.dismiss && result.dismiss == 'close' ){
                return false;
            }

            var upgrade_plugin = false;

            if (result.value) upgrade_plugin = true;

            var data = {action: 'deactivate_plugin_option_qm_track_users', upgrade_plugin: upgrade_plugin};
            $.ajax({
                url: quiz_maker_track_users_admin_ajax.ajax_url,
                method: 'post',
                dataType: 'json',
                data: data,
                success:function () {
                    window.location = $(document).find('[data-slug="quiz-maker-add-on-track-users"]').find('.deactivate').find('a').attr('href');
                }
            });
        });
        return false;
    });
})(jQuery);
