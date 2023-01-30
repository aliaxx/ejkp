/**
 * @copyright Copyright (c) 2019
 * @version 1.0.1
 */

var sideNav = function() {
    var cookie = $.cookie('sidenav-state');

    return {
        'toggle': function() {
            if ($('#main-container').hasClass('sidenav-off')) this.show();
            else this.hide();
        },
        'show': function() {
            $('#main-container').removeClass('sidenav-off');
            $.cookie('sidenav-state', 1, {path:'/'});
        },
        'hide': function() {
            $('#main-container').addClass('sidenav-off');
            $.cookie('sidenav-state', 0, {path:'/'});
        },
    }
}