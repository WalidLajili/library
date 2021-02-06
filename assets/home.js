import './styles/home.scss';
import { MDCSelect } from '@material/select';

const categorySelect = document.getElementById('home-category-select');
const input = document.querySelector('input[name="category"]');
if (categorySelect) {
  const select = new MDCSelect(categorySelect);
  if (input && input.value) {
    select.value = input.value;
  }
}
