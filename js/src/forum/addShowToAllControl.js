import { extend } from "flarum/extend";
import DiscussionControls from "flarum/utils/DiscussionControls";
import DiscussionPage from "flarum/components/DiscussionPage";
import Button from "flarum/components/Button";

export default function addShowToAllControl() {
  extend(DiscussionControls, "moderationControls", function (
    items,
    discussion
  ) {
    if (discussion.canShowToAll()) {
      items.add(
        "showToAll",
        Button.component(
          {
            icon: discussion.showToAll() ? "fas fa-eye-slash" : "fas fa-eye",
            onclick: this.showToAllAction.bind(discussion),
          },
          app.translator.trans(
            discussion.showToAll()
              ? "askvortsov-help-tags.forum.discussion_controls.unshow_to_all_button"
              : "askvortsov-help-tags.forum.discussion_controls.show_to_all_button"
          )
        )
      );
    }
  });

  DiscussionControls.showToAllAction = function () {
    this.save({ showToAll: !this.showToAll() }).then(() => {
      if (app.current.matches(DiscussionPage)) {
        app.current.get("stream").update();
      }

      m.redraw();
    });
  };
}
