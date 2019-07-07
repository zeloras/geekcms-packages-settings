var moduleSettingsAdmin = {
    config: {
        'settings_table': '.settings-container',
        'settings_line': '.settings-container__line',
        'settings_wrap': '.settings-container-wrap',
        'settings_button': '.settings-container-add',
        'settings_remove': '.settings-container-remove',
        'row_id': 'configRow'
    },

    init: function () {
        let self = this;
        let config = self.config;

        $(config.settings_button).unbind('click').on('click', function () {
            self.addConfig(null, null, true);
        });

        $(config.settings_remove).unbind('click').on('click', function () {
            $(this).parents(config.settings_line).remove();
            self.hideButtons();
        });
    },

    /**
     * Append lines
     *
     * @param settings_list_admin
     */
    loadConfig: function (settings_list_admin) {
        let self = this;

        if (settings_list_admin) {
            for (let key in settings_list_admin) {
                self.addConfig(key, settings_list_admin[key]);
            }
        }
    },

    /**
     * Add new config line
     *
     * @param key
     * @param value
     * @param rebind
     */
    addConfig: function (key, value, rebind) {
        let self = this;
        let config = self.config;
        let line = $(config.settings_line);

        key = key || null;
        value = value || null;
        rebind = rebind || null;

        let id = line.length;
        let last = line.first().hide().clone(true);
        let wrap = $(config.settings_wrap);

        last.attr('id', config.row_id + id);


        adminMainComponent.setFormElementValue(last.find('[data-key="key"]'), key);
        adminMainComponent.setFormElementValue(last.find('[data-key="value"]'), value);

        wrap.append(last.show());

        if (rebind) {
            self.init();
            self.hideButtons();
        }
    },

    /**
     * Hide/show remove/add buttons
     */
    hideButtons: function () {
        let self = this;
        let config = self.config;

        $(config.settings_button + ':last').removeClass('hidden');
        $(config.settings_button + ':not(:last)').addClass('hidden');
        $(config.settings_remove + ':last').addClass('hidden');
        $(config.settings_remove + ':not(:last)').removeClass('hidden');

        if ($(config.settings_remove).length <= 2) {
            $(config.settings_remove + ':last').addClass('hidden');
        } else {
            $(config.settings_remove + ':last').removeClass('hidden');
        }
    }
};

if (window.hasOwnProperty('settings_list_admin')) {
    moduleSettingsAdmin.loadConfig(settings_list_admin);
}

$(document).on('mainComponentsAdminLoaded', function () {
    moduleSettingsAdmin.init();
    moduleSettingsAdmin.hideButtons();
});
