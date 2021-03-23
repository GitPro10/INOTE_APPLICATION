
<!-- For decoration purpose and make some work easier I used bootstrap and for fancy fonts I used Google Fonts -->

<?php
$alert = false;
$error = false;
include_once("db.php");

// If client inserts a note then do this
if (isset($_POST["submit-note"])) {
  $title = $_POST["title"];
  $text = $_POST["note"];
  $query = mysqli_query($conn, "INSERT INTO `notes` (`TITLE`, `TEXT`, `ADDED TIME`) VALUES ('$title', '$text', current_timestamp());");
  if ($query) {
    $alert = "Added your note";
  } else {
    $error = "Unable to add your note";
  }
}
// If client deletes a note then do this
if (isset($_POST["delete-note-confirm"])) {
  $note_id = $_POST["delete-note-id"];
  $deleteQuery = mysqli_query($conn, "UPDATE `notes` SET `TITLE` = '<p class=\"text-muted\">Deleted Title</p>', `TEXT` = '<p class=\"text-muted\">Deleted Note</p>', `ADDED TIME` = '0' WHERE `notes`.`ID` = $note_id; ");
  if ($deleteQuery) {
    $alert = "Deleted your note";
  } else {
    $error = "Unable to delete your note";
  }
}
// If client edits a note then do this
if (isset($_POST["edit-note-confirm"])) {
  $newNoteTitle = $_POST["edit-note-title"];
  $newNoteText = $_POST["edit-note-text"];
  $newNoteId = $_POST["edit-note-id"];
  
  
  $editQuery = mysqli_query($conn, "UPDATE `notes` SET `TITLE` = '$newNoteTitle', `TEXT` = '$newNoteText', `ADDED TIME` = current_timestamp() WHERE `notes`.`ID` = $newNoteId; ");
  if ($editQuery) {
    $alert = "Edited your note";
  } else {
    $error = "Unable to edit your note";
  }
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
<title>INOTE APPLICATION</title>
  <style>
@import url('https://fonts.googleapis.com/css2?family=Pangolin&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap');
    .form-div,.table,.alert,.modal,.action-btns {
      font-family: 'Pangolin', cursive;
    }
    .heading-title {
      font-family: 'Roboto Slab', serif;
    }
  </style>
</head>
<body>
  <?php
  if ($alert) {
    echo('
    <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success: </strong>'.$alert.'.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
    ');
  }
  if ($error) {
    echo(' <div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Error: </strong>'.$error.'.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>');
  }
  ?>
  <h4 class="text-center heading-title">INOTE APPLICATION</h4>
  <!-- Note Form. Where User Can Insert A Note -->
  <form method="post">
    <div class="container form-div my-3 justify-content-center d-grid gap-2 text-center">
      <input class="form-control" required id="title" name="title" type="text" placeholder="Give a title...">

      <input class="form-control" required id="note" name="note" type="text" placeholder="Add some text...">

      <input name="submit-note" value="Add to Notes" type="submit" class="btn btn-outline-primary d-md-block">

    </div>
  </form>
  <div class="d-flex action-btns my-3 justify-content-around">
    <button name="submit-note" type="submit" class="d-md-block btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete Note</button><button name="submit-note" type="submit" class="d-md-block btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#editModal">Edit Note</button>
  </div>
  <!-- Notes Table -->
  <table class="table container table-hover table-bordered">
    <thead class="text-center">
      <tr>
        <th scope="col">ID</th>
        <th scope="col">TITLE</th>
        <th scope="col">TEXT</th>
        <th scope="col">ADDED TIMESTAMP</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Some php logics to display all notes from database one by one
      $sql = "SELECT * FROM `notes`";
      $result = mysqli_query($conn, $sql);
      $num_rows = mysqli_num_rows($result);
      if ($num_rows == 0) {
        echo('
  <td colspan="4" class="text-center"><strong>No Notes</strong></td>
  ');
      } else {
        while ($total_rows = mysqli_fetch_assoc($result)) {
          $Note_Title = $total_rows["TITLE"];
          $Note_Text = $total_rows["TEXT"];
          $Note_ID = $total_rows["ID"];
          $Note_Timestamp = $total_rows["ADDED TIME"];
          echo('
     <tr>
        <th scope="row">'.$Note_ID.'</th>
        <td>'.$Note_Title.'</td>
        <td>'.$Note_Text.'</td>
        <td>
          '.$Note_Timestamp.'
        </td>
      </tr>
    ');

        }
      }
      ?>
    </tbody>
  </table>



<!-- Modals using bootstrap -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal_title pt-3" id="editModalLabel">Edit A Note by ID Number.</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post">
          <div class="modal_body d-grid gap-2 my-2 container text-center">
            <input required name="edit-note-title" class="form-control" type="text" placeholder="Edit note title...">
            <input required name="edit-note-text" class="form-control" type="text" placeholder="Edit note text...">
          </div>

          <div class="modal-footer">
            <input required name="edit-note-id" class="form-control" type="number" placeholder="Enter Note Id...">
            <input name="edit-note-confirm" value="Change!" class="btn btn-outline-success" type="submit">
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal_title pt-3" id="deleteModalLabel">Delete A Note by ID Number.</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal_body pt-3 text-center">
          <p>
            Deleting a note will not delete your actual note it will change the note to something else.
          </p>
        </div>
        <form method="post">
          <div class="modal-footer">
            <input name="delete-note-id" class="form-control" type="number" required placeholder="Enter Note Id...">
            <input name="delete-note-confirm" value="Delete!" class="btn btn-outline-danger" type="submit">
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>

</body>
</html>