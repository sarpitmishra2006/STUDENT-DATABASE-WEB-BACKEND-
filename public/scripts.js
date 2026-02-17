document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('studentForm');
  if(!form) return;
  form.addEventListener('submit', function(e){
    const name = form.querySelector('input[name="name"]').value.trim();
    if(name.length < 3){ alert('Enter full name (at least 3 characters)'); e.preventDefault(); }
  });
});
