<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Site Performance Uploader</title>
      <link rel="stylesheet" href="style.css">
       <script src="script.js"></script>
</head>
<body>
  <h1>Upload Performance Graphs</h1>
  <form action="upload.php" method="POST" enctype="multipart/form-data">
    <div class="upload-area" id="drop-area">
      <p>Drag &amp; drop images here or</p>
      <label for="fileElem" class="button">Select Files</label>
      <input type="file" id="fileElem" name="graphs[]" multiple accept="image/*">
    </div>
    <button type="submit">Upload &amp; Process</button>
  </form>
  <div id="report">
    <?php if (isset($_GET['report'])): ?>
      <h2>Performance Report</h2>
      <?php echo base64_decode($_GET['report']); ?>
    <?php endif; ?>
  </div>
</body>
</html>
