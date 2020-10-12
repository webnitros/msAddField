Ext.Loader.load([
    MODx.config.assets_url + 'components/minishop2/js/mgr/misc/strftime-min-1.3.js'
]);

msAddField.utils.formatDate = function (string) {
    if (string && string != '0000-00-00 00:00:00' && string != '-1-11-30 00:00:00' && string != 0) {
        var date = /^[0-9]+$/.test(string)
            ? new Date(string * 1000)
            : new Date(string.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1'));
        return date.strftime(msAddField.config['date_format']);
    }
    else {
        return '&nbsp;';
    }
};

msAddField.utils.renderBoolean = function (value) {
    return value
        ? String.format('<span class="green">{0}</span>', _('yes'))
        : String.format('<span class="red">{0}</span>', _('no'));
};

msAddField.utils.getMenu = function (actions, grid, selected) {
    var menu = [];
    var cls, icon, title, action;

    var has_delete = false;
    for (var i in actions) {
        if (!actions.hasOwnProperty(i)) {
            continue;
        }

        var a = actions[i];
        if (!a['menu']) {
            if (a == '-') {
                menu.push('-');
            }
            continue;
        }
        else if (menu.length > 0 && !has_delete && (/^remove/i.test(a['action']) || /^delete/i.test(a['action']))) {
            menu.push('-');
            has_delete = true;
        }

        if (selected.length > 1) {
            if (!a['multiple']) {
                continue;
            }
            else if (typeof(a['multiple']) == 'string') {
                a['title'] = a['multiple'];
            }
        }

        icon = a['icon'] ? a['icon'] : '';
        if (typeof(a['cls']) == 'object') {
            if (typeof(a['cls']['menu']) != 'undefined') {
                icon += ' ' + a['cls']['menu'];
            }
        }
        else {
            cls = a['cls'] ? a['cls'] : '';
        }
        title = a['title'] ? a['title'] : a['title'];
        action = a['action'] ? grid[a['action']] : '';

        menu.push({
            handler: action,
            text: String.format(
                '<span class="{0}"><i class="x-menu-item-icon {1}"></i>{2}</span>',
                cls, icon, title
            ),
            scope: grid
        });
    }

    return menu;
};

msAddField.utils.renderActions = function (value, props, row) {
    var res = [];
    var cls, icon, title, action, item;
    for (var i in row.data.actions) {
        if (!row.data.actions.hasOwnProperty(i)) {
            continue;
        }
        var a = row.data.actions[i];
        if (!a['button']) {
            continue;
        }

        icon = a['icon'] ? a['icon'] : '';
        if (typeof(a['cls']) == 'object') {
            if (typeof(a['cls']['button']) != 'undefined') {
                icon += ' ' + a['cls']['button'];
            }
        }
        else {
            cls = a['cls'] ? a['cls'] : '';
        }
        action = a['action'] ? a['action'] : '';
        title = a['title'] ? a['title'] : '';

        item = String.format(
            '<li class="{0}"><button class="msaddfield-btn msaddfield-btn-default {1}" action="{2}" title="{3}"></button></li>',
            cls, icon, action, title
        );

        res.push(item);
    }

    return String.format(
        '<ul class="msaddfield-row-actions">{0}</ul>',
        res.join('')
    );
};


msAddField.utils.userLink = function (value, id, blank) {
    if (!value) {
        return '';
    }
    else if (!id) {
        return value;
    }

    return String.format(
        '<a href="?a=security/user/update&id={0}" class="ms2-link" target="{1}">{2}</a>',
        id,
        (blank ? '_blank' : '_self'),
        value
    );
};