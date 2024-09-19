<?php

namespace app\core\tds\personType;

/**
 * Description of PersonTypeValidator
 *
 * @author Shrishail
 */
class PersonTypeValidator extends \app\cwf\vsla\xmlbo\ValidatorBase {

    public function validatePersonTypeEditForm(): void {
 
        $formView = \app\cwf\vsla\xml\CwfXmlLoader::loadFile($this->modulePath, $this->formName);
        if (!$formView) {
            throw new \Exception('Form view could not be loaded.');
        }
        $this->validateUsingForm($this->bo, $formView);
 
        $this->validateBusinessRules();
    }
 
    private function validateBusinessRules(): void {
     
        if (empty($this->bo->person_type_desc)) {
            $this->bo->addBRule('Person Type description is required.');
        }
 
        $cmm = new \app\cwf\vsla\data\SqlCommand();
        $cmm->setCommandText('SELECT person_type_desc FROM tds.person_type WHERE person_type_desc ILIKE :pperson_type_desc AND person_type_id != :pperson_type_id');
        $cmm->addParam('pperson_type_desc', $this->bo->person_type_desc);
        $cmm->addParam('pperson_type_id', $this->bo->person_type_id);
        $result = \app\cwf\vsla\data\DataConnect::getData($cmm);
        if (count($result->Rows()) > 0) {
            $this->bo->addBRule('Person Type already exists. Duplicate Person Type not allowed.');
        }
    }
}
