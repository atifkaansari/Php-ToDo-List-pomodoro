<?php
require_once 'db.php';

// Get filter parameters
$filter_date = $_GET['date'] ?? '';
$filter_view = $_GET['view'] ?? 'all';

$query = "SELECT * FROM todos";
$params = [];

if ($filter_date) {
    $query .= " WHERE due_date = ?";
    $params[] = $filter_date;
} elseif ($filter_view == 'today') {
    $query .= " WHERE DATE(due_date) = CURDATE()";
} elseif ($filter_view == 'week') {
    $query .= " WHERE due_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)";
} elseif ($filter_view == 'overdue') {
    $query .= " WHERE due_date < NOW() AND completed = 0";
}

$query .= " ORDER BY 
    CASE WHEN due_date IS NULL THEN 1 ELSE 0 END,
    due_date ASC,
    CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 END,
    created_at DESC";

$stmt = $db->prepare($query);
$stmt->execute($params);
$todos = $stmt->fetchAll();

$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo & Pomodoro</title>
    <link rel="stylesheet" href="app.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Todo & Pomodoro</h1>
            <p>Günlük görevlerinizi organize edin ve odaklanın</p>
        </header>

        <main class="main-content">
            <!-- Todo Section -->
            <section class="todo-section">
                <h2 class="section-title">Görevler</h2>

                <!-- Add Todo Form -->
                <form action="add.php" method="POST" class="add-todo-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="task">Görev</label>
                            <input type="text" id="task" name="task" class="form-control" placeholder="Yeni görev ekleyin..." required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="due_date">Bitiş Tarihi</label>
                            <input type="datetime-local" id="due_date" name="due_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="priority">Öncelik</label>
                            <select id="priority" name="priority" class="form-control">
                                <option value="low">Düşük</option>
                                <option value="medium" selected>Orta</option>
                                <option value="high">Yüksek</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ekle
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Todo List -->
                <div class="todos-container">
                    <?php if (empty($todos)): ?>
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <h3>Henüz görev yok</h3>
                            <p>İlk görevinizi ekleyerek başlayın</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($todos as $todo): ?>
                            <div class="todo-item <?php echo $todo['completed'] ? 'completed' : ''; ?>">
                                <div class="todo-header">
                                    <div class="todo-content">
                                        <h3 class="todo-title"><?php echo htmlspecialchars($todo['task']); ?></h3>
                                        <div class="todo-meta">
                                            <span class="priority-badge priority-<?php echo $todo['priority']; ?>">
                                                <?php 
                                                $priority_text = ['low' => 'Düşük', 'medium' => 'Orta', 'high' => 'Yüksek'];
                                                echo $priority_text[$todo['priority']];
                                                ?>
                                            </span>
                                            <?php if ($todo['due_date']): ?>
                                                <span class="countdown" data-due-datetime="<?php echo $todo['due_date']; ?>" data-completed="<?php echo $todo['completed']; ?>">
                                                    <?php echo date('d.m.Y H:i', strtotime($todo['due_date'])); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="todo-actions">
                                        <button class="action-btn complete-btn" onclick="toggleComplete(<?php echo $todo['id']; ?>)">
                                            <i class="<?php echo $todo['completed'] ? 'fas fa-check' : 'far fa-circle'; ?>"></i>
                                        </button>
                                        <button class="action-btn edit-btn" onclick="editTodo(<?php echo $todo['id']; ?>, '<?php echo addslashes($todo['task']); ?>', '<?php echo $todo['due_date']; ?>', '<?php echo $todo['priority']; ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete-btn" onclick="deleteTodo(<?php echo $todo['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Pomodoro Section -->
            <section class="pomodoro-section">
                <h2 class="section-title">Pomodoro</h2>
                
                <div class="timer-container">
                    <div class="timer-circle">
                        <svg class="timer-svg" width="220" height="220" viewBox="0 0 220 220">
                            <circle cx="110" cy="110" r="100" class="timer-bg"></circle>
                            <circle cx="110" cy="110" r="100" class="timer-progress" id="timerProgress"></circle>
                        </svg>
                        <div class="timer-text" id="timeDisplay">25:00</div>
                    </div>
                    <div class="timer-label" id="timerLabel">Çalışma Zamanı</div>
                </div>

                <div class="timer-controls">
                    <button class="btn btn-success" id="startBtn" onclick="startTimer()">
                        <i class="fas fa-play"></i> Başla
                    </button>
                    <button class="btn btn-secondary" id="pauseBtn" onclick="pauseTimer()" style="display: none;">
                        <i class="fas fa-pause"></i> Duraklat
                    </button>
                    <button class="btn btn-secondary" onclick="resetTimer()">
                        <i class="fas fa-reset"></i> Sıfırla
                    </button>
                </div>

                <div class="pomodoro-stats">
                    <div class="stats-title">Günlük İstatistikler</div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-value" id="completedPomodoros">0</span>
                            <span class="stat-label">Pomodoro</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value" id="totalFocusTime">0</span>
                            <span class="stat-label">Dakika</span>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 8px; padding: 24px; width: 90%; max-width: 500px;">
            <h3 style="margin-bottom: 20px; color: var(--primary);">Görevi Düzenle</h3>
            <form action="edit.php" method="POST">
                <input type="hidden" id="editId" name="id">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Görev</label>
                    <input type="text" id="editTask" name="task" required class="form-control">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Bitiş Tarihi</label>
                    <input type="datetime-local" id="editDueDate" name="due_date" class="form-control">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Öncelik</label>
                    <select id="editPriority" name="priority" class="form-control">
                        <option value="low">Düşük</option>
                        <option value="medium">Orta</option>
                        <option value="high">Yüksek</option>
                    </select>
                </div>
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">İptal</button>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Todo Functions
        function toggleComplete(id) {
            fetch('complete.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id
            }).then(() => location.reload());
        }

        function deleteTodo(id) {
            if (confirm('Bu görevi silmek istediğinizden emin misiniz?')) {
                fetch('delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + id
                }).then(() => location.reload());
            }
        }

        function editTodo(id, task, dueDate, priority) {
            document.getElementById('editId').value = id;
            document.getElementById('editTask').value = task;
            document.getElementById('editDueDate').value = dueDate || '';
            document.getElementById('editPriority').value = priority || 'medium';
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Countdown updates
        function updateCountdowns() {
            const countdowns = document.querySelectorAll('.countdown');
            const now = new Date();
            
            countdowns.forEach(countdown => {
                const dueDateStr = countdown.dataset.dueDatetime;
                if (!dueDateStr) return;
                
                const dueDate = new Date(dueDateStr);
                const isCompleted = countdown.dataset.completed === '1';
                
                countdown.classList.remove('urgent', 'today');
                
                if (isCompleted) {
                    countdown.textContent = 'Tamamlandı';
                    return;
                }
                
                const diff = dueDate - now;
                const hours = Math.floor(Math.abs(diff) / (1000 * 60 * 60));
                
                if (diff < 0) {
                    countdown.classList.add('urgent');
                    countdown.textContent = `${hours}sa gecikti`;
                } else if (diff < 24 * 60 * 60 * 1000) {
                    countdown.classList.add('today');
                    countdown.textContent = `${hours}sa kaldı`;
                }
            });
        }

        // Pomodoro Timer
        let timer = {
            isRunning: false,
            timeLeft: 25 * 60,
            totalTime: 25 * 60,
            completedPomodoros: localStorage.getItem('completedPomodoros') || 0,
            totalFocusTime: localStorage.getItem('totalFocusTime') || 0
        };

        let timerInterval;

        function startTimer() {
            if (timer.isRunning) return;
            
            timer.isRunning = true;
            document.getElementById('startBtn').style.display = 'none';
            document.getElementById('pauseBtn').style.display = 'inline-block';
            
            timerInterval = setInterval(() => {
                timer.timeLeft--;
                updateTimerDisplay();
                
                if (timer.timeLeft <= 0) {
                    completePomodoro();
                }
            }, 1000);
        }

        function pauseTimer() {
            timer.isRunning = false;
            clearInterval(timerInterval);
            document.getElementById('startBtn').style.display = 'inline-block';
            document.getElementById('pauseBtn').style.display = 'none';
        }

        function resetTimer() {
            pauseTimer();
            timer.timeLeft = timer.totalTime;
            updateTimerDisplay();
        }

        function completePomodoro() {
            pauseTimer();
            timer.completedPomodoros++;
            timer.totalFocusTime += 25;
            
            localStorage.setItem('completedPomodoros', timer.completedPomodoros);
            localStorage.setItem('totalFocusTime', timer.totalFocusTime);
            
            updateStats();
            resetTimer();
            
            alert('Pomodoro tamamlandı! 🎉');
        }

        function updateTimerDisplay() {
            const minutes = Math.floor(timer.timeLeft / 60);
            const seconds = timer.timeLeft % 60;
            const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            document.getElementById('timeDisplay').textContent = timeString;
            
            // Update progress circle
            const progress = 1 - (timer.timeLeft / timer.totalTime);
            const progressCircle = document.getElementById('timerProgress');
            const circumference = 2 * Math.PI * 100;
            const offset = circumference * (1 - progress);
            
            progressCircle.style.strokeDasharray = circumference;
            progressCircle.style.strokeDashoffset = offset;
        }

        function updateStats() {
            document.getElementById('completedPomodoros').textContent = timer.completedPomodoros;
            document.getElementById('totalFocusTime').textContent = timer.totalFocusTime;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateTimerDisplay();
            updateStats();
            updateCountdowns();
            setInterval(updateCountdowns, 60000);
            
            // Set default datetime
            const input = document.getElementById('due_date');
            if (input) {
                const now = new Date();
                now.setHours(now.getHours() + 1);
                input.value = now.toISOString().slice(0, 16);
            }
        });

        // Modal close on outside click
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>