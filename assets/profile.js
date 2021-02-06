import './styles/profile.scss';

const newPasswordInput = document.querySelector('input[name=newPassword]');
const passwordInput = document.querySelector('input[name=password]');

passwordInput.addEventListener('change', (e) => {
  if (e.target.value) {
    newPasswordInput.setAttribute('required', true);
  } else {
    newPasswordInput.removeAttribute('required');
  }
});

newPasswordInput.addEventListener('change', (e) => {
  if (e.target.value) {
    passwordInput.setAttribute('required', true);
  } else {
    passwordInput.removeAttribute('required');
  }
});
