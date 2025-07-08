// main.js - Handles AJAX, UI, and animations

document.addEventListener('DOMContentLoaded', function() {
  // Example: Load students on home page
  if (document.getElementById('students-list')) {
    fetchStudents();
  }
});

// AJAX: Fetch students (with optional search)
function fetchStudents(search = '') {
  const loader = document.getElementById('loader');
  if (loader) loader.style.display = 'block';
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'php/students.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.onload = function() {
    if (loader) loader.style.display = 'none';
    if (xhr.status === 200) {
      const res = JSON.parse(xhr.responseText);
      if (res.success) {
        renderStudents(res.students);
      } else {
        alert('Failed to fetch students.');
      }
    }
  };
  xhr.send('action=fetch&search=' + encodeURIComponent(search));
}

// Render students in table or card layout
function renderStudents(students) {
  const list = document.getElementById('students-list');
  if (!list) return;
  list.innerHTML = '';
  if (students.length === 0) {
    list.innerHTML = '<div class="student-row">No records found.</div>';
    return;
  }
  students.forEach(student => {
    const row = document.createElement('div');
    row.className = 'student-row';
    row.innerHTML = `
      <img src="${student.image_url}" alt="Profile">
      <div style="flex:1">
        <strong>${student.name}</strong><br>
        <span>${student.department} | Semester ${student.semester}</span><br>
        <span>${student.email}</span>
      </div>
      <button class="btn" onclick="showEditModal(${student.student_id})">Edit</button>
      <button class="btn" style="background:var(--danger)" onclick="showDeleteModal(${student.student_id})">Delete</button>
    `;
    list.appendChild(row);
  });
}

// Search students
function searchStudents() {
  const search = document.getElementById('search-input').value;
  fetchStudents(search);
}

document.addEventListener('input', function(e) {
  if (e.target && e.target.id === 'search-input') {
    searchStudents();
  }
});

// Show Edit Modal (AJAX-powered)
function showEditModal(student_id) {
  // Find student data from current DOM (for instant UI) or refetch if needed
  const row = Array.from(document.querySelectorAll('.student-row')).find(r => r.querySelector('button') && r.querySelector('button').onclick.toString().includes(`showEditModal(${student_id})`));
  let name = '', department = '', semester = '', email = '', image_url = '';
  if (row) {
    const info = row.querySelector('div');
    name = info.querySelector('strong').innerText;
    const [dept, sem] = info.querySelectorAll('span')[0].innerText.split(' | Semester ');
    department = dept;
    semester = sem;
    email = info.querySelectorAll('span')[1].innerText;
    image_url = row.querySelector('img').src;
  }
  // Modal HTML
  const modal = document.getElementById('modal');
  const content = document.getElementById('modal-content');
  content.innerHTML = `
    <h3>Edit Student</h3>
    <form id="edit-student-form">
      <label>Name</label>
      <input type="text" name="name" value="${name}" required style="width:100%;padding:0.7em 1em;margin-bottom:1em;">
      <label>Department</label>
      <input type="text" name="department" value="${department}" required style="width:100%;padding:0.7em 1em;margin-bottom:1em;">
      <label>Semester</label>
      <input type="text" name="semester" value="${semester}" required style="width:100%;padding:0.7em 1em;margin-bottom:1em;">
      <label>Email</label>
      <input type="email" name="email" value="${email}" required style="width:100%;padding:0.7em 1em;margin-bottom:1em;">
      <label>Profile Image URL</label>
      <input type="text" name="image_url" value="${image_url}" required style="width:100%;padding:0.7em 1em;margin-bottom:1em;">
      <button type="submit" class="btn">Update</button>
      <button type="button" class="btn" style="background:var(--danger);margin-left:1em;" onclick="closeModal()">Cancel</button>
      <div id="edit-msg" style="margin-top:1em;"></div>
    </form>
  `;
  modal.style.display = 'flex';
  // Animate modal
  setTimeout(() => modal.classList.add('show'), 10);
  document.getElementById('edit-student-form').onsubmit = function(e) {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    data.append('action', 'update');
    data.append('student_id', student_id);
    const msg = document.getElementById('edit-msg');
    msg.innerHTML = '<div class="loader"></div>';
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/students.php', true);
    xhr.onload = function() {
      msg.innerHTML = '';
      if (xhr.status === 200) {
        const res = JSON.parse(xhr.responseText);
        if (res.success) {
          closeModal();
          fetchStudents();
        } else {
          msg.innerHTML = `<span style='color:var(--danger);'>${res.error || 'Update failed.'}</span>`;
        }
      }
    };
    xhr.send(new URLSearchParams([...data]));
  };
}

// Show Delete Modal (AJAX-powered)
function showDeleteModal(student_id) {
  const modal = document.getElementById('modal');
  const content = document.getElementById('modal-content');
  content.innerHTML = `
    <h3>Delete Student</h3>
    <p>Are you sure you want to delete this student record?</p>
    <div style="margin-top:1.5em;">
      <button class="btn" style="background:var(--danger);" id="confirm-delete">Delete</button>
      <button class="btn" style="margin-left:1em;" onclick="closeModal()">Cancel</button>
    </div>
    <div id="delete-msg" style="margin-top:1em;"></div>
  `;
  modal.style.display = 'flex';
  setTimeout(() => modal.classList.add('show'), 10);
  document.getElementById('confirm-delete').onclick = function() {
    const msg = document.getElementById('delete-msg');
    msg.innerHTML = '<div class="loader"></div>';
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/students.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      msg.innerHTML = '';
      if (xhr.status === 200) {
        const res = JSON.parse(xhr.responseText);
        if (res.success) {
          closeModal();
          fetchStudents();
        } else {
          msg.innerHTML = `<span style='color:var(--danger);'>${res.error || 'Delete failed.'}</span>`;
        }
      }
    };
    xhr.send('action=delete&student_id=' + encodeURIComponent(student_id));
  };
}

// Close modal
function closeModal() {
  const modal = document.getElementById('modal');
  modal.classList.remove('show');
  setTimeout(() => { modal.style.display = 'none'; }, 200);
}

// Registration AJAX (to be used on register page)
function registerUser() {
  // TODO: Collect form data, send AJAX POST to php/register.php, handle response
}

// Login AJAX (to be used on login page)
function loginUser() {
  // TODO: Collect form data, send AJAX POST to php/login.php, handle response
}

// Add, Update, Delete student functions to be implemented similarly 