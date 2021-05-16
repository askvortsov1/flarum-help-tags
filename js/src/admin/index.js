import { override } from "flarum/extend";

app.initializers.add("askvortsov/flarum-help-tags", () => {
  override(app, "getRequiredPermissions", (original, permission) => {
    var required = original(permission);

    if (/(viewTag|startDiscussion)$/.test(permission)) {
      return required.filter((a) => !/viewForum$/.test(a));
    }

    return required;
  });

  app.extensionData.for("askvortsov-help-tags").registerPermission(
    {
      icon: "fas fa-eye",
      label: app.translator.trans(
        "askvortsov-help-tags.admin.permissions.view_tag_label"
      ),
      permission: "viewTag",
      tagScoped: true,
      allowGuest: true,
    },
    "view",
    100
  );
});
