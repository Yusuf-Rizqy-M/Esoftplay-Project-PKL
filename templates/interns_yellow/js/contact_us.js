document.addEventListener('DOMContentLoaded', function() {
  const fields = document.querySelectorAll('.field-group');
  fields.forEach(field => {
    const input = field.querySelector('input, textarea');
    if(input) {
      input.addEventListener('focus', () => field.style.borderColor = '#FFB300');
      input.addEventListener('blur', () => field.style.borderColor = '#e0e0e0');
    }
  });
});