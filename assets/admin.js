import './styles/admin.scss';

import { MDCTextField } from '@material/textfield';
import { MDCSelect } from '@material/select';

const inputs = document.querySelectorAll('.mdc-text-field');

export function readAsDataURL(blob) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.addEventListener('loadend', (e) => {
      resolve(e.target?.result || '');
    });
    reader.addEventListener('error', (e) => {
      reject(e);
    });
    reader.readAsDataURL(blob);
  });
}

for (let i = 0; i < inputs.length; i += 1) {
  const textField = new MDCTextField(inputs[i]);
}

const deleteButton = document.getElementById('delete-button');
if (deleteButton) {
  deleteButton.addEventListener('click', async () => {
    const response = await fetch(location.href, { method: 'DELETE' });
    if (response.status === 200) {
      location.pathname = location.pathname.split('/').slice(0, -1).join('/');
    }
  });
}

const categorySelect = document.getElementById('category-select');

if (categorySelect) {
  const select = new MDCSelect(categorySelect);
  const categoryInput = document.querySelector('input[name=category]');
  select.required = true;
  select.value = categoryInput.value;
  select.listen('MDCSelect:change', () => {
    categoryInput.value = select.value;
  });
}

const bookUrlUpload = document.getElementById('book-url-upload');

if (bookUrlUpload) {
  const uploadInput = document.querySelector('input[name="url"]');
  bookUrlUpload.addEventListener('click', () => {
    uploadInput.click();
  });
  const link = document.getElementById('upload-pdf');
  uploadInput.addEventListener('change', async (e) => {
    const file = e.target.files[0];

    const pdfImageButton = document.getElementById('pdf-add-button');
    if (pdfImageButton) pdfImageButton.style = 'display:none;';
    link.innerHTML = '';
    const text = document.createTextNode(file.name);
    link.appendChild(text);
    link.className = 'upload-pdf';
  });
  if (!location.pathname.endsWith('add')) {
    link.addEventListener('click', (e) => {
      e.stopPropagation();
    });
  }
}

const bookImageUpload = document.getElementById('book-image-upload');

if (bookImageUpload) {
  const uploadInput = document.querySelector('input[name="cover"]');

  bookImageUpload.addEventListener('click', () => {
    console.log(uploadInput);
    if (uploadInput) {
      uploadInput.click();
    }
  });

  uploadInput.addEventListener('change', async (e) => {
    const url = await readAsDataURL(e.target.files[0]);
    const addImageButton = document.getElementById('image-add-button');
    if (addImageButton) {
      addImageButton.style = 'display:none;';
    }
    const image = document.getElementById('upload-image');
    image.src = url;
    image.className = 'upload-image';
  });
}
