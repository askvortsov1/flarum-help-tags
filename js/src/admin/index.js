import { extend, override } from 'flarum/extend';
import PermissionGrid from 'flarum/components/PermissionGrid';


app.initializers.add('askvortsov/flarum-help-tags', () => {
    override(app, 'getRequiredPermissions', (original, permission) => {
        var required = original(permission);

        required = required.filter(a => ! /viewDiscussions$/.test(a))

        return required;
    });

    extend(PermissionGrid.prototype, 'viewItems', items => {
        items.add('viewTag', {
            icon: 'fas fa-eye',
            label: app.translator.trans('askvortsov-help-tags.admin.permissions.view_tag_label'),
            permission: 'discussion.viewTag',
            allowGuest: true
        }, 100);
    });
});
