<?php
$file = 'tasks.json';

// Charger les tâches
$tasks = json_decode(file_get_contents($file), true) ?: [];

// Ajouter une tâche
if (isset($_POST['submit'])) {
    $newTask = trim($_POST['task']);
    if ($newTask !== '') {
        $tasks[] = ['task' => $newTask, 'done' => false];
        file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    }
    header("Location: index.php");
    exit;
}

// Marquer comme terminée / non terminée
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    if (isset($tasks[$id])) {
        $tasks[$id]['done'] = !$tasks[$id]['done'];
        file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    }
    header("Location: index.php");
    exit;
}

// Supprimer une tâche
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (isset($tasks[$id])) {
        array_splice($tasks, $id, 1);
        file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    }
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>To-Do List Stylisée</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 50px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            text-align: center;
            margin-bottom: 30px;
        }
        input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 15px;
            border: none;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        ul {
            list-style: none;
            padding: 0;
            max-width: 500px;
            margin: auto;
        }
        li {
            background-color: white;
            margin-bottom: 10px;
            padding: 12px 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        li.done {
            text-decoration: line-through;
            color: gray;
            background-color: #e9ecef;
        }
        a {
            margin-left: 10px;
            text-decoration: none;
            color: #dc3545;
            font-weight: bold;
        }
        a:hover {
            color: #bd2130;
        }
    </style>
</head>
<body>
    <h1>Ma To-Do List</h1>

    <form method="post">
        <input type="text" name="task" placeholder="Nouvelle tâche..." required>
        <button type="submit" name="submit">Ajouter</button>
    </form>

    <ul>
        <?php foreach ($tasks as $id => $task): ?>
            <li class="<?= $task['done'] ? 'done' : '' ?>">
                <span><?= htmlspecialchars($task['task']) ?></span>
                <div>
                    <a href="?toggle=<?= $id ?>">✔️</a>
                    <a href="?delete=<?= $id ?>">🗑️</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
