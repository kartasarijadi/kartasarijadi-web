<?php
$yourushiki = $sk ?? "Lorem 1 (Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s)

Lorem 2 (Lorem Ipsum is simply dummy text of the printing and typesetting industry.)

Lorem 3 (Lorem Ipsum has been the industry's standard dummy text ever since the 1500s)

Lorem 4 (There are many variations of passages of Lorem Ipsum available, but the majo)"
?>

<form method="POST" action="">
    <textarea name="data" id="ss" cols="30" rows="10"><?= $yourushiki; ?></textarea>
    <button type="submit">submit</button>
</form>