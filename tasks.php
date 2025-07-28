<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Handle new task submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['task'])) {
    $task = $_POST['task'];
    $stmt = $conn->prepare("INSERT INTO tasks (user_id, description) VALUES (?, ?)");
    $stmt->bind_param("is", $userId, $task);
    $stmt->execute();
    header("Location: tasks.php");
    exit();
}

// Handle deletion of completed tasks (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_completed') {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE user_id = ? AND completed = 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit();
}

// Handle AJAX request for task completion update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id']) && isset($_POST['completed'])) {
    $task_id = intval($_POST['task_id']);
    $completed = $_POST['completed'] == 'true' ? 1 : 0;

    $stmt = $conn->prepare("UPDATE tasks SET completed = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $completed, $task_id, $userId);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit();
}

// Handle single task deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_single' && isset($_POST['task_id'])) {
    $task_id = intval($_POST['task_id']);
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $userId);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit();
}


// Fetch all tasks for the logged-in user
$stmt = $conn->prepare("SELECT id, description, completed FROM tasks WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);

// Calculate completion percentage
$totalTasks = count($tasks);
$completedTasks = 0;
foreach ($tasks as $task) {
    if ($task['completed']) $completedTasks++;
}
$completionPercent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Tasks</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f9fafb;
      margin: 0;
      padding: 2rem;
      color: #333;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
    }
    h1 {
      color: #4c51bf;
      margin-bottom: 1rem;
    }
    form#addTaskForm {
      margin-bottom: 1.5rem;
      display: flex;
      gap: 0.5rem;
    }
    input[type="text"] {
      padding: 0.6rem;
      width: 300px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 1rem;
      box-sizing: border-box;
      transition: border-color 0.3s;
    }
    input[type="text"]:focus {
      border-color: #667eea;
      outline: none;
    }
   button {
  padding: 0.4rem 0.8rem;
  border: none;
  border-radius: 6px;
  background: linear-gradient(90deg, #e53e3e, #c53030);
  color: white;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.3s ease;
  font-size: 0.9rem;
  margin-left: auto; /* push to right */
}

button:hover {
  background: linear-gradient(90deg, #c53030, #9b2c2c);
}
    .progress-container {
      width: 320px;
      background: #e2e8f0;
      border-radius: 20px;
      overflow: hidden;
      margin-bottom: 1.5rem;
      box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
    }
    .progress-bar {
      height: 20px;
      background: linear-gradient(90deg, #667eea, #764ba2);
      width: <?= $completionPercent ?>%;
      transition: width 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 700;
      font-size: 0.9rem;
      border-radius: 20px;
    }
    ul {
      list-style: none;
      padding: 0;
      max-width: 400px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      width: 100%;
      position: relative;
    }
    li {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #e2e8f0;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    li:last-child {
      border-bottom: none;
    }
    li.done label {
      text-decoration: line-through;
      color: #38a169;
      font-weight: 700;
    }
    input[type="checkbox"] {
      transform: scale(1.3);
      cursor: pointer;
    }

   
/* This aligns delete button to top-right inside each task */
.deleteSingleBtn {
  margin-left: auto;
  background: none;
  color: #e53e3e;
  font-size: 1.1rem;
  border: none;
  cursor: pointer;
  padding: 0.4rem;
  transition: transform 0.2s ease;
}

.deleteSingleBtn:hover {
  transform: scale(1.2);
}

    .completion-message {
      margin-top: 1rem;
      color: #38a169;
      font-weight: 700;
      font-size: 1.1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .completion-message svg {
      fill: #38a169;
      width: 24px;
      height: 24px;
    }
    a.back {
      display: inline-block;
      margin-top: 2rem;
      color: #667eea;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    a.back:hover {
      color: #4c51bf;
    }

    /* Popup confirmation overlay */
    #popupOverlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.4);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 10;
    }
    #popup {
      background: white;
      border-radius: 12px;
      padding: 1.5rem 2rem;
      max-width: 320px;
      text-align: center;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    #popup p {
      margin-bottom: 1.5rem;
      font-size: 1.1rem;
      font-weight: 600;
    }
    #popup button {
      margin: 0 0.5rem;
      width: 100px;
    }

    /* Top corner slide notification */
    #notification {
      position: fixed;
      top: 16px;
      right: 16px;
      background: #333;
      color: white;
      padding: 1rem 1.5rem;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      opacity: 0;
      transform: translateY(-50px);
      transition: opacity 0.3s ease, transform 0.3s ease;
      z-index: 20;
      display: flex;
      align-items: center;
      gap: 1rem;
      font-weight: 600;
    }
    #notification.show {
      opacity: 1;
      transform: translateY(0);
    }
    #notification.success {
      background: #38a169;
    }
    #notification.error {
      background: #e53e3e;
    }
    #notification button {
      background: transparent;
      border: 1.5px solid white;
      border-radius: 4px;
      color: white;
      padding: 0.2rem 0.5rem;
      font-weight: 700;
      cursor: pointer;
    }

    /* Notification styling */
#notification {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9999;
  background-color: #4caf50;
  color: white;
  padding: 12px 18px;
  border-radius: 5px;
  display: none;
}
#notification.show {
  display: block;
}
#notification.error {
  background-color: #f44336;
}

/* Popup overlay */
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  justify-content: center;
  align-items: center;
}

.popup-box {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
}

  </style>
</head>
<body>
  <h1>My Tasks</h1>

  <form method="POST" action="tasks.php" id="addTaskForm">
    <input type="text" name="task" placeholder="New task" required autocomplete="off" />
    <button type="submit">Add Task</button>
  </form>

  <div class="progress-container" aria-label="Task completion progress">
    <div class="progress-bar" style="width: <?= $completionPercent ?>%">
      <?= $completionPercent ?>%
    </div>
  </div>

  <?php if ($completionPercent === 100 && $totalTasks > 0): ?>
    <div class="completion-message" role="alert" aria-live="polite">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 16.2l-3.5-3.5 1.41-1.41L9 13.38l7.09-7.09 1.41 1.41z"/></svg>
      All tasks completed! 🎉
    </div>
  <?php endif; ?>

 <ul id="taskList">
  <?php foreach ($tasks as $task): ?>
    <li class="<?= $task['completed'] ? 'done' : '' ?>" data-task-id="<?= $task['id'] ?>">
      <input 
        type="checkbox" 
        data-task-id="<?= $task['id'] ?>" 
        <?= $task['completed'] ? 'checked' : '' ?> 
        aria-label="Mark task '<?= htmlspecialchars($task['description'], ENT_QUOTES) ?>' as completed"
      />
      <label><?= htmlspecialchars($task['description']) ?></label>

      <!-- 👇 Per-task delete button -->
      <button class="deleteSingleBtn" title="Delete this task">🗑</button>
    </li>
  <?php endforeach; ?>
</ul>



  <a href="dashboard.php" class="back">← Back to Dashboard</a>

 <!-- Popup confirmation -->
<div id="popupOverlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
  <div id="popup" style="background:#fff; padding:20px; border-radius:8px; box-shadow:0 4px 8px rgba(0,0,0,0.2); text-align:center;">
    <p>Are you sure you want to delete all completed tasks?</p>
    <button id="confirmDeleteBtn" style="margin-right:10px;">OK</button>
    <button id="cancelDeleteBtn">Cancel</button>
  </div>
</div>


  <!-- Notification -->
  <div id="notification"></div>

  <!-- Confirmation Popup -->
<div id="popupOverlay" class="popup-overlay" style="display: none;">
  <div class="popup-box">
    <p>Are you sure you want to delete all completed tasks?</p>
    <button id="confirmDeleteBtn">Yes, delete</button>
    <button id="cancelDeleteBtn">Cancel</button>
  </div>
</div>
<script>
  // Show top-right corner notification
  function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    notification.innerHTML = '';

    const text = document.createElement('span');
    text.textContent = message;

    const closeBtn = document.createElement('button');
    closeBtn.textContent = 'X';
    closeBtn.onclick = () => notification.classList.remove('show');

    notification.appendChild(text);
    notification.appendChild(closeBtn);

    notification.className = '';
    notification.classList.add('show', type);

    setTimeout(() => notification.classList.remove('show'), 5000);
  }

  // ✅ Handle checkbox task completion
  document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', () => {
      const taskId = checkbox.dataset.taskId;
      const completed = checkbox.checked;

      checkbox.parentElement.classList.toggle('done', completed);

      fetch('tasks.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ task_id: taskId, completed: completed })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showNotification('Task updated.');
          setTimeout(() => window.location.reload(), 1500);
        } else {
          throw new Error();
        }
      })
      .catch(() => {
        showNotification('Error updating task.', 'error');
        checkbox.checked = !completed;
        checkbox.parentElement.classList.toggle('done', !completed);
      });
    });
  });

  // ✅ Handle individual delete button click
  document.querySelectorAll('.deleteSingleBtn').forEach(button => {
    button.addEventListener('click', e => {
      e.preventDefault();
      const li = button.closest('li');
      const taskId = li.dataset.taskId;

      if (!confirm('Delete this task?')) return;

      fetch('tasks.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'delete_single', task_id: taskId })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showNotification('Task deleted.');
          li.remove();
        } else {
          throw new Error();
        }
      })
      .catch(() => {
        showNotification('Failed to delete task.', 'error');
      });
    });
  });

   // ✅ Show the custom confirmation popup
  const deleteCompletedBtn = document.getElementById('deleteCompletedBtn');
  const popupOverlay = document.getElementById('popupOverlay');
  const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
  const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

  if (deleteCompletedBtn) {
    deleteCompletedBtn.addEventListener('click', () => {
      popupOverlay.style.display = 'flex'; // Show the popup
    });
  }

  // ✅ Confirm deletion
  confirmDeleteBtn.addEventListener('click', () => {
    fetch('tasks.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ action: 'delete_completed' })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        showNotification('All completed tasks deleted.');
        setTimeout(() => window.location.reload(), 1500);
      } else {
        throw new Error();
      }
    })
    .catch(() => {
      showNotification('Error deleting tasks.', 'error');
    });

    popupOverlay.style.display = 'none';
  });

  // ✅ Cancel deletion
  cancelDeleteBtn.addEventListener('click', () => {
    popupOverlay.style.display = 'none';
  });
</script>



  
  </body>
</html>
