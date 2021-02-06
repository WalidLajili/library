import './styles/book.scss';

const bookDownload = document.getElementById('book-download');
import { MDCDialog } from '@material/dialog';

if (bookDownload) {
  const dialog = new MDCDialog(document.querySelector('.mdc-dialog'));
  bookDownload.addEventListener('click', () => {
    dialog.open();
  });
}

const bookDownloadLink = document.getElementById('book-download-link');

if (bookDownloadLink) {
  bookDownloadLink.addEventListener('click', () => {
    const id = location.pathname.split('/').slice(-1);

    fetch(location.origin + '/history/add', {
      body: `book=${id[0]}`,
      method: 'post',
      headers: { ['Content-Type']: 'application/x-www-form-urlencoded' },
    });
  });
}
