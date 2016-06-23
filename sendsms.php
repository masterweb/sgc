<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Envio de SMS</title>
        <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <form action="sms.php" method="post">
                        <div class="form-group">
                            <label for="">Numero</label>
                            <input type="text" name="numero" id="numero" class="form-control" maxlength="10">
                        </div>
                        <div class="form-group">
                            <label for="">Mensaje</label>
                            <textarea name="mensaje" id="mensaje" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="send" id="send" class="btn">
                            <input type="hidden" name="user" id="user" value="">
                            <input type="hidden" name="pass" id="pass" value="">
                        </div>
                    </form>
                </div>
            </div>
            	
        </div>
    </body>
</html>