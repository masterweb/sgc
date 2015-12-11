<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/estilosUsuarios.css" type="text/css" />
<div class="container">
    <div class="row">
        <div class="col-md-12">
			
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
				<?php if (Yii::app()->user->hasFlash('error')){ ?>
					<div class="infos">
						<?php echo Yii::app()->user->getFlash('error'); ?>
					</div>
				<?php } ?>
			</div><!-- form -->
            <?php endif; ?>
        </div>
    </div>
</div>