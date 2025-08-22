// PW Toggle (without Libs)
function togglePassword(id, btn) {
  const el = document.getElementById(id);
  if (!el) return;
  el.type = el.type === 'password' ? 'text' : 'password';
  if (btn) btn.textContent = el.type === 'password' ? 'anzeigen' : 'verbergen';
}

// Simple helper, prevents Doppel-Submit
function disableOnSubmit(form) {
  form.addEventListener('submit', () => {
    const btn = form.querySelector('button[type="submit"]');
    if (btn) { btn.disabled = true; setTimeout(()=>btn.disabled=false, 3000); }
  });
}
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form').forEach(disableOnSubmit);
});
