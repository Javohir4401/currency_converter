<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello World</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <form method="POST">
        <div class="container text-center text-primary">
            <h1>Hello World</h1>
        </div>
        <div class="container">
            <div class="mb-3">
                <label for="name" class="form-label">ISM</label>
                <input type="text" class="form-control" id="name" aria-describedby="emailHelp" name="name" required>
            </div>
            <div class="mb-3">
                <label for="arrived_at" class="form-label">KELGAN VAQTI</label>
                <input type="datetime-local" class="form-control" id="arrived_at" name="arrived_at" required>
            </div>
            <div class="mb-3">
                <label for="left_at" class="form-label">KETGAN VAQTI</label>
                <input type="datetime-local" class="form-control" id="left_at" name="left_at" required>
            </div>
            <button class="btn btn-primary" type="submit" value="Submit">YUBORISH</button>
        </div>
    </form>

    <div class="container mt-4">
    <table class="table table-primary">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ISMI</th>
                <th scope="col">KELGAN VAQTI</th>
                <th scope="col">KETGAN VAQTI</th>
                <th scope="col">QARZDORLIK VAQTI</th>
                <th scope="col">10 KUNLIK JAMI QARZDORLIK</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($records)) {
                foreach ($records as $record) {
                    $debtLast10Days = calculateDebtLast10Days($pdo, $record['name']);
                    echo "<tr>
                        <td>{$record['id']}</td>
                        <td>{$record['name']}</td>
                        <td>{$record['arrived_at']}</td>
                        <td>{$record['left_at']}</td>
                        <td>" . gmdate('H:i', $record['required_of']) . "</td>
                        <td>" . gmdate('H:i', $debtLast10Days) . "</td>
                    </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>