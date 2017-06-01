<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));



include(TEMPLATES_PATH . '/_header.php')
?>

<div class="container container-admin-index">
    <table>
        <tr>
            <td>id</td>
            <td>Title</td>
            <td>Category</td>
            <td>Author</td>
            <td>Created on</td>
        </tr>
    </table>


</div>


<?php include(TEMPLATES_PATH . '/_footer.php') ?>
