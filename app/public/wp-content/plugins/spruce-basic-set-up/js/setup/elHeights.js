import { masthead } from "../elements";

export function getMastheadHeight(asPixels = true) {
  if (asPixels) {
    return `${masthead.offsetHeight}px`;
  }
  return masthead.offsetHeight;
}

export function getMastheadPreBarHeight(asPixels = true) {
  const preBar = masthead.querySelector(".masthead__pre-bar");
  if (asPixels) {
    return `${preBar.offsetHeight}px`;
  }
  return preBar.offsetHeight;
}
