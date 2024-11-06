<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <h1 class="text-danger text-center">Hello, world!</h1>
      <form method="POST">
        <div class="mb-3">
          <label for="name" class="form-label">Ism</label>
          <input type="text" class="form-control" id="name" name="name">
        </div>
        <div class="mb-3">
          <label for="arrived_at" class="form-label">Kelgan vaqti</label>
          <input type="datetime-local" class="form-control" id="arrived_at" name="arrived_at">
        </div>
        <div class="mb-3">
          <label for="leaved_at" class="form-label">Ketgan vaqti</label>
          <input type="datetime-local" class="form-control" id="leaved_at" name="leaved_at">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>

      <?php

        $dsn = 'mysql:host=127.0.0.1;dbname=data';
        $pdo = new PDO($dsn, 'root', '1112');

        if (isset($_POST['name']) && isset($_POST['arrived_at']) && isset($_POST['leaved_at'])) {
          $name = $_POST['name'];
          $arrived_at = $_POST['arrived_at'];
          $leaved_at = $_POST['leaved_at'];

          $query = "INSERT INTO word (name, arrived_at, leaved_at) VALUES (:name, :arrived_at, :leaved_at)";
          $stmt = $pdo->prepare($query);
          $stmt->bindParam(':name', $name);
          $stmt->bindParam(':arrived_at', $arrived_at);
          $stmt->bindParam(':leaved_at', $leaved_at);
          $stmt->execute();
        }

        $query = "SELECT id, name, arrived_at, leaved_at FROM word";
        $stmt = $pdo->query($query);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?>

      <table class="table mt-4" style="background-color: #e0f7fa;">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Ism</th>
            <th scope="col">Kelgan Vaqti</th>
            <th scope="col">Ketgan Vaqti</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($records as $record): ?>
            <tr>
              <th scope="row"><?php echo htmlspecialchars($record['id']); ?></th>
              <td><?php echo htmlspecialchars($record['name']); ?></td>
              <td><?php echo htmlspecialchars($record['arrived_at']); ?></td>
              <td><?php echo htmlspecialchars($record['leaved_at']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </body>
</html>
