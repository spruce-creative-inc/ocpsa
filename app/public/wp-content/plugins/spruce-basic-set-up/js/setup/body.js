import { body, masthead } from "../elements";
import { getMastheadHeight, getMastheadPreBarHeight } from "./elHeights";

export function useBodySetUp() {
  function setMastheadHeight() {
    body.style.setProperty("--offset-masthead", getMastheadHeight());
    body.style.setProperty(
      "--offset-masthead-pre-bar",
      getMastheadPreBarHeight()
    );
  }

  const mastheadResizeObserver = new ResizeObserver((entries) => {
    entries.forEach(() => {
      setMastheadHeight();
    });
  });
  mastheadResizeObserver.observe(masthead);
}
