<body>
    <link rel="stylesheet" type="text/css" href="../application/views/parser/parse.css">
    <script src="../application/views/ajax/upload_file.js"></script>
    <?php $items = require $_SERVER["DOCUMENT_ROOT"] . "/application/config/formats.php" ?>
    <?php $string_formats = ''; ?>
    <?php foreach ($items as $item): ?>
        <?php $string_formats .= ($item . ', '); ?>
    <?php endforeach; ?>
    <?php $string_formats[strrpos($string_formats, ',')] = '.'; ?>
    <p id="message">Файл должен быть форматом <?php echo $string_formats ?></p>
    <form class="was-validated" id="form-file-ajax" enctype="multipart/form-data" action="/application/scripts/file_upload.php" method="POST">
        <div class="custom-file">
            <input name="upload_file" type="file" id="file" class="custom-file-input" required">
            <label class="custom-file-label any-input" for="validatedCustomFile">Выберите файл...</label>
            <button class="btn btn-primary any-btn" type="submit" value="Upload" name="submit">Разобрать текст</button>
        </div>
    </form>
    <div class="parse-text"><?= $parse_regex; ?></div>
    <div class="parse-text"><?= $parse_words_case; ?></div>
    <div class="parse-text"><?= $dict_parse; ?></div>
    <div class="parse-text"><?= $php_analysis; ?></div>
    <div class="parse-text"><?= $semantic_wordnet; ?></div>
    <br/>
    <div class="parse-text"><?= $text; ?></div>
</body>
</html>