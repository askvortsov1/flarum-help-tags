import app from "flarum/app";
import Model from "flarum/Model";
import Discussion from "flarum/models/Discussion";
import addShowToAllBadge from "./addShowToAllBadge";
import addShowToAllControl from "./addShowToAllControl";

app.initializers.add("askvortsov-help-tags", () => {
  Discussion.prototype.showToAll = Model.attribute("showToAll");
  Discussion.prototype.canShowToAll = Model.attribute("canShowToAll");

  addShowToAllBadge();
  addShowToAllControl();
});
