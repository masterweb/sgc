
<div class="container">
    <div class="row">
        <h1 class="tl_seccion">QIR</h1>
    </div>   

    <div class="row">
        <div class="table-responsive">
            <table class="tables tablesorter" id="keywords" style="width: 100%">
                <thead>
                    <tr>
                        <th style="font-size: 10px;"><span>ID</span></th>
                        <th style="font-size: 10px;"><span>Concesionario</span></th>
                        <th style="font-size: 10px;"><span># Reporte</span></th>
                        <th style="font-size: 10px;"><span>Fecha Registro</span></th>
                        <th style="font-size: 10px;"><span>Modelo</span></th>
                        <th style="font-size: 10px;"><span>Titular</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //$model = new Qir();
                    if ($model) {
                        foreach ($model as $c):
                            ?>
                    <tr  >
                                <td style="font-size: 10px;border-bottom: 1px solid red;"><?php echo $c->id; ?> </td>
                                <td style="width: 20%;font-size: 10px"><?php echo strtoupper($c->dealer['name']) ?> </td>
                                <td style="width: 15%;font-size: 10px"><?php echo $c->num_reporte ?> </td>
                                <td style="width: 10%;font-size: 10px"><?php echo $c->fecha_registro ?> </td>
                                <td style="width: 15%;font-size: 10px"><?php echo strtoupper($c->modeloPostVenta['descripcion']) ?> </td>
                                <td style="width: 34%;font-size: 10px;text-align: justify"><?php echo $c->titular ?> </td>                                
                            </tr>
                            <?php
                        endforeach;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>