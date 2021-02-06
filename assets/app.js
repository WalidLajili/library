/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

import { MDCMenu } from '@material/menu';
import { MDCTextField } from '@material/textfield';

const userButton = document.getElementById('header-user');
const menuItem = document.querySelector('.mdc-menu');

if (menuItem) {
  const menu = new MDCMenu(menuItem);

  function openMenu() {
    menu.setAbsolutePosition(window.innerWidth - 20, userButton.offsetTop + userButton.clientHeight);
    menu.open = true;
  }

  userButton.addEventListener('mousedown', openMenu);
}

const inputs = document.querySelectorAll('.mdc-text-field');

for (let i = 0; i < inputs.length; i += 1) {
  const textField = new MDCTextField(inputs[i]);
}
