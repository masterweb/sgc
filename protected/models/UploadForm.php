<?php
class UploadForm extends CFormModel
{
    public $upload_file;

    public function rules()
    {
        return array(
       // array('upload_file', 'file', 'types'=>'jpg,jpeg,gif,png','maxSize'=>10*1024*1024),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'upload_file'=>'Upload File',
        );
    }

}
?>