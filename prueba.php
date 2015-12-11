<?php
if(isset($_POST)):
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
endif;
?>
<form action="prueba.php" method="post" name="form-prueba">
    <select multiple name="marcas[]">
        <option value="volvo">Volvo</option>
        <option value="saab">Saab</option>
        <option value="opel">Opel</option>
        <option value="audi">Audi</option>
    </select>
    <input type="submit" name="send">
</form>
