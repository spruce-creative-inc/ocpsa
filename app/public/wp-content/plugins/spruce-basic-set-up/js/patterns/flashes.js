import { wait } from "../helpers";

export async function setFlash(msg, type, time) {
  const flash = document.querySelector(".flash");
  const classesToRemove = ["warning", "error", "success", "active"];
  flash.textContent = msg;
  flash.classList.remove(...classesToRemove);
  flash.classList.add(type);
  flash.classList.add("active");
  flash.classList.add("transitioned");
  await wait(time);
  flash.classList.remove("transitioned");
  await wait(200);
  flash.classList.remove(...classesToRemove);
  flash.textContent = "";
}
