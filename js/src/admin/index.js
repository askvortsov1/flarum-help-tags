import { extend, override } from 'flarum/extend';


app.initializers.add('askvortsov/flarum-private-tags', () => {
    override(app, 'getRequiredPermissions', (original, permission) => {
        var required = original(permission);

        required = required.filter(a => ! /viewDiscussions$/.test(a))

        return required;
    });
});
