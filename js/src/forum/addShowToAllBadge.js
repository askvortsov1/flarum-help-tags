import { extend } from "flarum/extend";
import Discussion from "flarum/models/Discussion";
import Badge from "flarum/components/Badge";

export default function addShowToAllBadge() {
  extend(Discussion.prototype, "badges", function (badges) {
    if (this.showToAll()) {
      badges.add(
        "showToAll",
        Badge.component({
          type: "showToAll",
          label: app.translator.trans(
            "askvortsov-help-tags.forum.badge.show_to_all_tooltip"
          ),
          icon: "fas fa-eye",
        }),
        10
      );
    }
  });
}
