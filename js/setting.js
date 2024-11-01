jQuery(document).ready(function( $ ) {
    var baseUrl = dd_settings_data.base_url;

	function add_setting() {
        var bodyElement = document.body;

        var hasClassWithPrefix = Array.from(bodyElement.classList).some(function(className) {
            return className.startsWith('woocommerce_page_dfm-pgppfw');
        });

        if (!hasClassWithPrefix) return;

        // add a 'Settings' tab via JS
        const navTabWrapper = $('.nav-tab-wrapper');
        const currentTabs = $('.nav-tab-wrapper a');
        let activeTab = '';
        if(!currentTabs.hasClass('nav-tab-active')) {
            activeTab = ' nav-tab-active';
        }
        if (navTabWrapper.has('.home').length == 0) {
            navTabWrapper.prepend('<a href="'+baseUrl+'/wp-admin/admin.php?page=dfm-pgppfw" class="nav-tab fs-tab svg-flags-lite home' + activeTab + '">Settings</a>');
        }
    }
    setTimeout(add_setting, 100);
});